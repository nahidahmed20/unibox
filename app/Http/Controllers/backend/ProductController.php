<?php

namespace App\Http\Controllers\backend;

use App\Exports\ProductDemoExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Imports\ProductsImport;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductColorImage;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\ProductStock;
use App\Models\ProductVariant;
use App\Models\StockAdjustment;
use App\Models\StockAdjustmentItem;
use App\Models\SubCategory;
use App\Models\Unit;
use App\Models\Variation;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Milon\Barcode\Facades\DNS1DFacade as DNS1D;

class ProductController extends Controller implements HasMiddleware
{
    protected $productService;

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view product', only: ['index']),
            new Middleware('permission:create product', only: ['create']),
            new Middleware('permission:edit product', only: ['edit']),
            new Middleware('permission:destroy product', only: ['destroy']),
        ];
    }

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::with(['category', 'subCategory', 'brand', 'unit', 'images', 'variants.color'])->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('category_name', fn($row) => $row->category?->name ?? '—')
                ->addColumn('sub_category_name', fn($row) => $row->subCategory?->name ?? '—')
                ->addColumn('brand_name', fn($row) => $row->brand?->name ?? '—')
                ->addColumn('unit_name', fn($row) => $row->unit?->name ?? '—')
                ->addColumn('image', fn($row) => $row->image
                    ? '<img src="' . asset($row->image) . '" width="60" height="60" class="rounded">'
                    : '<span class="text-muted">No Image</span>'
                )
                ->addColumn('stock', function ($row) {
                    if ($row->product_type === 'multiple') {
                        $totalStock = $row->variants->sum('stock');
                        return '<span class="badge bg-info text-white">' . $totalStock . ' Qty</span><br><small class="text-muted">(' . $row->variants->count() . ' Variants)</small>';
                    }
                    return '<span class="badge bg-secondary">' . ($row->stock ?? 0) . ' Qty</span>';
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })
                ->addColumn('action', function ($row) {
                    $showBtn = '<button class="btn btn-icon btn-soft-info btn-show" data-id="' . $row->id . '" title="View Details"><i class="fa-regular fa-eye"></i></button>';
                    $editBtn = '<a href="' . route('products.edit', $row->id) . '" class="btn btn-icon btn-soft-primary" title="Edit Product"><i class="fa-regular fa-pen-to-square"></i></a>';
                    $deleteForm = '<form class="delete-form d-inline" action="' . route('products.destroy', $row->id) . '" method="POST">' . csrf_field() . method_field('DELETE') . '<button type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Product"><i class="fa-regular fa-trash-can"></i></button></form>';
                    return '<div class="d-flex align-items-center justify-content-center gap-2">' . $showBtn . $editBtn . $deleteForm . '</div>';
                })
                ->rawColumns(['image', 'status', 'stock', 'action'])
                ->make(true);
        }

        $categories = Category::select('id', 'name')->get();
        $brands = Brand::select('id', 'name')->get();
        $units = Unit::select('id', 'name')->get();
        $subcategories = SubCategory::select('id', 'name')->get();

        return view('backend.product.index', compact('categories', 'brands', 'units', 'subcategories'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $units = Unit::all();
        $variations = Variation::with('sizes')->where('status', 1)->get();
        $colors = Color::all();

        return view('backend.product.create', compact('categories', 'brands', 'units', 'variations', 'colors'));
    }
   
    public function store(StoreProductRequest $request)
    {
        try {
            $this->productService->handleStore($request->validated(), $request);
            return redirect()->route('products.index')->with('success', 'Product created successfully!');
         } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
         }
    }

    public function bulkUploadView()
    {
        return view('backend.product.bulk_upload');
    }

    public function bulkUpload(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:5120',
        ], [
            'file.required' => 'Please select an excel file to upload.',
            'file.mimes' => 'Only .xlsx, .xls, and .csv files are allowed.',
            'file.max' => 'File size must not exceed 5MB.',
        ]);

        try {
            Excel::import(new ProductsImport, $request->file('file'));
            return redirect()->route('products.index')->with('success', 'Bulk products uploaded successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return redirect()->back()->with('error', 'Validation Error on Row ' . $failures[0]->row() . ': ' . $failures[0]->errors()[0]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function downloadDemo()
    {
        return Excel::download(new ProductDemoExport, 'Product_Bulk_Upload_Format.xlsx');
    }

    public function show($id)
    {
        $product = Product::with([
            'category:id,name',
            'subCategory:id,name',
            'brand:id,name',
            'unit:id,name',
            'images',
            'colors.color',
            'colors.images',
            'variants.color', 
            'variants.size' // 👈 পরিবর্তন ১: সাইজ রিলেশনটি লোড করা হলো
        ])->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $colorImages = $product->colors->map(function ($pColor) {
            return [
                'id'     => $pColor->color->id ?? '',
                'name'   => $pColor->color->name ?? '',
                'code'   => $pColor->color->code ?? '',
                'images' => $pColor->images->pluck('image')->map(fn($img) => asset($img))->toArray(),
            ];
        });

        $stocks = $product->variants->map(function ($variant) {
            return [
                'quantity' => $variant->stock,
                'color'    => $variant->color ? [
                    'id'   => $variant->color->id,
                    'name' => $variant->color->name,
                    'code' => $variant->color->code,
                ] : null,
                'size'     => $variant->size ? [
                    'id'   => $variant->size->id,
                    'name' => $variant->size->name ?? $variant->size->size, 
                ] : null,
            ];
        });

        $sizes = $product->variants->pluck('size')->filter()->unique('id')->map(function($size) {
            return [
                'id'   => $size->id,
                'name' => $size->name,
            ];
        })->values();

        return response()->json([
            'id'                => $product->id,
            'name'              => $product->name,
            'sku'               => $product->sku,
            'barcode'           => $product->barcode,
            'category'          => $product->category,
            'subCategory'       => $product->subCategory,
            'brand'             => $product->brand,
            'unit'              => $product->unit,
            'purchase_price'    => $product->purchase_price,
            'selling_price'     => $product->selling_price,
            'discount_type'     => $product->discount_type,
            'main_price'        => $product->main_price,
            'alert_quantity'    => $product->alert_quantity,
            'short_description' => $product->short_description,
            'description'         => $product->description,
            'is_featured'       => $product->is_featured,
            'is_new'            => $product->is_new,
            'is_bestseller'     => $product->is_bestseller,
            'is_trending'       => $product->is_trending,
            'status'            => $product->status,
            'image'             => $product->image ? asset($product->image) : null,
            'size_guide'        => $product->size_guide ? asset($product->size_guide) : null,
            'images'            => $product->images->pluck('image')->map(fn($img) => asset($img))->toArray(),
            'colors'            => $colorImages,
            'sizes'             => $sizes,
            'stocks'            => $stocks,
        ]);
    }

    public function edit(string $id)
    {
        $product = Product::with([
            'category',
            'subCategory',
            'brand',
            'unit',
            'variants.color', 
            'variants.size', 
            'colors.images',
            'images'
        ])->findOrFail($id);

        $categories = Category::all();
        $subcategories = SubCategory::where('category_id', $product->category_id)->get();
        $brands = Brand::all();
        $units = Unit::all();
        $colors = Color::all();
        $variations = Variation::where('status', 1)->get();

        $selectedVariationId = $product->variation_id ?? null;

        return view('backend.product.edit', compact( 
            'product',
            'categories',
            'subcategories',
            'brands',
            'units',
            'variations',
            'colors',
            'selectedVariationId'
        ));
    }

    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $this->productService->handleUpdate($id, $request->validated(), $request);
            return redirect()->route('products.index')->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        $product = Product::with(['images', 'variants', 'colors.images'])->findOrFail($id);

        if ($product->is_purchased == 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product is already purchased, cannot be deleted!',
            ], 400); 
        }

        DB::beginTransaction();
        try {
            if ($product->image && File::exists(public_path($product->image))) {
                File::delete(public_path($product->image));
            }
            if ($product->size_guide && File::exists(public_path($product->size_guide))) {
                File::delete(public_path($product->size_guide));
            }

            foreach ($product->images as $img) {
                if ($img->image && File::exists(public_path($img->image))) {
                    File::delete(public_path($img->image));
                }
            }
            $product->images()->delete(); 

            foreach ($product->colors as $color) {
                foreach ($color->images as $colorImg) {
                    if ($colorImg->image && File::exists(public_path($colorImg->image))) {
                        File::delete(public_path($colorImg->image));
                    }
                }
                $color->images()->delete(); 
            }
            $product->colors()->delete(); 

            if (method_exists($product, 'variants')) {
                $product->variants()->delete();
            }

            $product->delete();
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully!',
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product deletion failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while deleting!', 
            ], 500); 
        }
    }

    public function getSubcategories($category_id)
    {
        $subcategories = SubCategory::where('category_id', $category_id)->get();
        return response()->json($subcategories);
    }

    /**
     * নতুন ভ্যারিয়েশন টেবিল অনুযায়ী সাইজ/আইটেম লোড করার ডাইনামিক মেথড (AJAX)
     */
    public function getSizesByVariation($variation_id)
    {
        $sizes = DB::table('variation_items')
            ->join('sizes', 'variation_items.size_id', '=', 'sizes.id')
            ->where('variation_items.variation_id', $variation_id)
            ->select('sizes.id', 'sizes.name')
            ->get();

        return response()->json($sizes);
    }

    public function deleteColor($id)
    {
        $color = ProductColor::with('images')->findOrFail($id);

        foreach ($color->images as $img) {
            if (file_exists(public_path($img->image))) {
                unlink(public_path($img->image));
            }
            $img->delete();
        }

        $color->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }
   
    public function stockAdjustmentIndex(Request $request)
    {
        if ($request->ajax()) {
            $data = StockAdjustment::select('id', 'adjustment_no', 'adjustment_date', 'type', 'reason', 'note')->latest();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('type', function ($row) {
                    return $row->type === 'addition'
                        ? '<span class="badge bg-soft-success text-success fw-bold px-3 py-2 rounded-pill"><i class="fa fa-plus me-1"></i> Addition</span>'
                        : '<span class="badge bg-soft-danger text-danger fw-bold px-3 py-2 rounded-pill"><i class="fa fa-minus me-1"></i> Subtraction</span>';
                })
                ->addColumn('reason', function ($row) {
                    return '<span class="badge bg-light text-dark border px-3 py-2 text-capitalize">'. e($row->reason) .'</span>';
                })
                ->addColumn('action', function($row) {
                    $showBtn = '<button class="btn btn-icon btn-soft-info btn-show" data-id="'.$row->id.'" title="View Details"><i class="fa-regular fa-eye"></i></button>';
                    $editBtn = '<a href="'.route('stock-adjustments.edit', $row->id).'" class="btn btn-icon btn-soft-primary" title="Edit Adjustment"><i class="fa-regular fa-pen-to-square"></i></a>';
                    $deleteForm = '<form class="delete-form d-inline" action="'.route('stock-adjustments.destroy', $row->id).'" method="POST">'.csrf_field().method_field('DELETE').'<button type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Adjustment"><i class="fa-regular fa-trash-can"></i></button></form>';

                    return '<div class="d-flex align-items-center justify-content-center gap-2">'.$showBtn.$editBtn.$deleteForm.'</div>';
                })
                ->rawColumns(['type', 'reason', 'action'])
                ->make(true);
        }

        return view('backend.stock_adjustments.index');
    }

    public function stockAdjustmentcreate()
    {
        $products = Product::select('id', 'name', 'sku')->where('status', 1)->get();
        $variations = Variation::where('status', 1)->get(); // ভ্যারিয়েশন লিস্ট পাঠানো হলো যাতে ডাইনামিকালি সাইজ চুজ করা যায়
        $colors = DB::table('colors')->select('id', 'name')->get();

        return view('backend.stock_adjustments.create', compact('products', 'variations', 'colors'));
    }
    
    public function stockAdjustmentstore(Request $request)
    {
        $request->validate([
            'adjustment_date' => 'required|date',
            'type'            => 'required|in:addition,subtraction',
            'products'        => 'required|array|min:1',
            'products.*.id'   => 'required|exists:products,id',
            'products.*.qty'  => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $adjustmentNo = $request->reference_no ?? ('ADJ-' . strtoupper(Str::random(6)));

            $adjustment = StockAdjustment::create([
                'adjustment_no'   => $adjustmentNo,
                'adjustment_date' => $request->adjustment_date,
                'type'            => $request->type,
                'reason'          => $request->reason,
                'note'            => $request->note,
                'created_by'      => auth()->id() ?? 1, 
            ]);

            foreach ($request->products as $item) {
                $colorId = !empty($item['color_id']) ? $item['color_id'] : null;
                $sizeId  = !empty($item['size_id']) ? $item['size_id'] : null;

                StockAdjustmentItem::create([
                    'stock_adjustment_id' => $adjustment->id,
                    'product_id'          => $item['id'], 
                    'color_id'            => $colorId,
                    'size_id'             => $sizeId,
                    'quantity'            => $item['qty'],
                ]);

                $productStock = ProductStock::firstOrCreate(
                    [
                        'product_id' => $item['id'], 
                        'color_id'   => $colorId,
                        'size_id'    => $sizeId,
                    ],
                    ['quantity' => 0]
                );

                if ($request->type === 'addition') {
                    $productStock->increment('quantity', $item['qty']);
                } else {
                    $productStock->decrement('quantity', $item['qty']);
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Stock adjusted successfully!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong! ' . $e->getMessage()
            ], 500);
        }
    }

    public function stockAdjustmentShow($id)
    {
        $adjustment = StockAdjustment::with(['items.product', 'items.color', 'items.size'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'data'    => $adjustment
        ]);
    }

    public function stockAdjustmentEdit($id)
    {
        $adjustment = StockAdjustment::with(['items.product', 'items.color', 'items.size'])->findOrFail($id);
        $products = Product::select('id', 'name', 'sku')->latest()->get();
        $variations = Variation::where('status', 1)->get();

        return view('backend.stock_adjustments.edit', compact('adjustment', 'products', 'variations'));
    }

    public function stockAdjustmentupdate(Request $request, $id)
    {
        $request->validate([
            'adjustment_date' => 'required|date',
            'type'            => 'required|in:addition,subtraction',
            'reason'          => 'required|string',
            'note'            => 'nullable|string',
            'items'           => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $adjustment = StockAdjustment::with('items')->findOrFail($id);
                $oldType = $adjustment->type;

                foreach ($adjustment->items as $oldItem) {
                    $stock = ProductStock::where([
                        'product_id' => $oldItem->product_id,
                        'color_id'   => $oldItem->color_id,
                        'size_id'    => $oldItem->size_id,
                    ])->first();

                    if ($stock) {
                        if ($oldType === 'addition') {
                            $stock->decrement('quantity', $oldItem->quantity);
                        } else {
                            $stock->increment('quantity', $oldItem->quantity);
                        }
                    }
                }

                $adjustment->items()->delete();

                $adjustment->update([
                    'adjustment_date' => $request->adjustment_date,
                    'type'            => $request->type,
                    'reason'          => $request->reason,
                    'note'            => $request->note,
                ]);

                foreach ($request->items as $item) {
                    $adjustment->items()->create([
                        'product_id' => $item['product_id'],
                        'color_id'   => $item['color_id'] ?? null,
                        'size_id'    => $item['size_id'] ?? null,
                        'quantity'   => $item['quantity'],
                    ]);

                    $stock = ProductStock::firstOrCreate([
                        'product_id' => $item['product_id'],
                        'color_id'   => $item['color_id'] ?? null,
                        'size_id'    => $item['size_id'] ?? null,
                    ]);

                    if ($request->type === 'addition') {
                        $stock->increment('quantity', $item['quantity']);
                    } else {
                        $stock->decrement('quantity', $item['quantity']);
                    }
                }
            });

            return redirect()->route('stock-adjustments.index')->with('success', 'Stock Adjustment updated successfully with inventory recalculation!');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function stockAdjustmentDestroy($id)
    {
        DB::beginTransaction();
        try {
            $adjustment = StockAdjustment::with('items')->findOrFail($id);
            foreach ($adjustment->items as $item) {
                $stock = ProductStock::where([
                    'product_id' => $item->product_id,
                    'color_id'   => $item->color_id,
                    'size_id'    => $item->size_id,
                ])->first();
                if ($stock) {
                    if ($adjustment->type === 'addition') {
                        $stock->decrement('quantity', $item->quantity);
                    } else {
                        $stock->increment('quantity', $item->quantity);
                    }
                }
            }

            $adjustment->items()->delete();
            $adjustment->delete();
            DB::commit();
            return response()->json([
                'status'  => 'success',
                'message' => 'Stock Adjustment deleted successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function barcodeIndex()
    {
        $products = Product::select('id', 'name', 'sku', 'selling_price', 'image')
            ->where('status', 1)
            ->orderBy('name')
            ->get();

        return view('backend.product.barcode', compact('products'));
    }


    public function barcodePrint(Request $request)
    {
        $request->validate([
            'products'           => 'required|array|min:1',
            'products.*.qty'     => 'required|integer|min:1|max:500',
            'label_width'        => 'required|numeric|min:10|max:200',
            'label_height'       => 'required|numeric|min:10|max:200',
            'columns'            => 'required|integer|min:1|max:10',
            'gap'                => 'nullable|numeric|min:0|max:30',
        ]);

        $productIds = array_keys($request->products);
        $products   = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $labels = [];

        foreach ($request->products as $id => $item) {
            $product = $products->get($id);
            if (!$product) {
                continue;
            }

            $code = $product->sku ?: ('PID' . str_pad($product->id, 8, '0', STR_PAD_LEFT));

            $barcodeSvg = DNS1D::getBarcodeSVG($code, 'C128', 1.6, 45, 'black', false);

            $qty = (int) $item['qty'];

            for ($i = 0; $i < $qty; $i++) {
                $labels[] = [
                    'name'    => $product->name,
                    'sku'     => $code,
                    'price'   => $product->selling_price,
                    'barcode' => $barcodeSvg,
                ];
            }
        }

        return view('backend.product.barcode-print', [
            'labels'       => $labels,
            'label_width'  => $request->label_width,
            'label_height' => $request->label_height,
            'columns'      => $request->columns,
            'gap'          => $request->gap ?? 2,
            'show_price'   => $request->boolean('show_price', true),
            'show_name'    => $request->boolean('show_name', true),
        ]);
    }
}
