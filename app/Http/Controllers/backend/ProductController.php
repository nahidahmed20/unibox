<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductColorImage;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\SubCategory;
use App\Models\Unit;
use App\Models\Variation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use App\Imports\ProductsImport;
use App\Exports\ProductDemoExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view product', only: ['index']),
            new Middleware('permission:create product', only: ['create']),
            new Middleware('permission:edit product', only: ['edit']),
            new Middleware('permission:destroy product', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Product::with(['category', 'subCategory', 'brand', 'unit', 'images', 'colors.images', 'sizes','stocks.color', 'stocks.size'])->latest();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('category_name', fn($row) => $row->category?->name ?? '—')
                ->addColumn('sub_category_name', fn($row) => $row->subCategory?->name ?? '—')
                ->addColumn('brand_name', fn($row) => $row->brand?->name ?? '—')
                ->addColumn('unit_name', fn($row) => $row->unit?->name ?? '—')

                ->addColumn('image', fn($row) =>
                    $row->image
                        ? '<img src="'.asset($row->image).'" width="60" height="60" class="rounded">'
                        : '<span class="text-muted">No Image</span>'
                )

                ->addColumn('stock', fn($row) => number_format($row->stock_quantity, 2))

                ->addColumn('sizes', function($row) {
                    return $row->sizes->pluck('size')->implode(', ');
                })

                ->addColumn('colors', function($row) {
                    $html = '';
                    foreach ($row->colors as $color) {
                        $html .= '<span class="badge" style="background-color: '.$color->code.'; color:#fff; margin-right:3px;">'.$color->name.'</span>';
                    }
                    return $html ?: '—';
                })
                ->addColumn('status', function($row) {
                    return $row->status == 1
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>';
                })

                ->addColumn('stock', function($row) {
                    if ($row->stocks->isEmpty()) {
                        return '<span class="text-muted">No stock</span>';
                    }

                    $html = '<table class="table table-sm table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Color</th>
                                        <th>Size</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead><tbody>';

                    foreach ($row->stocks as $stock) {
                        $colorName = $stock->color?->name ?? '—';
                        $sizeName  = $stock->size?->size ?? '—';
                        $qty       = $stock->quantity;

                        $html .= "<tr>
                                    <td><span class='badge' style='background-color:#0061ff; color:#fff;'>{$colorName}</span></td>
                                    <td>{$sizeName}</td>
                                    <td>{$qty}</td>
                                </tr>";
                    }

                    $html .= '</tbody></table>';

                    return $html;
                })


                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->search['value']) {
                        $search = $request->search['value'];

                        $query->where(function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%")
                            ->orWhere('purchase_price', 'like', "%{$search}%")
                            ->orWhere('selling_price', 'like', "%{$search}%")
                            ->orWhereHas('category', function($q2) use ($search) {
                                $q2->where('name', 'like', "%{$search}%");
                            })
                            ->orWhereHas('subCategory', function($q3) use ($search) {
                                $q3->where('name', 'like', "%{$search}%");
                            })
                            ->orWhereHas('brand', function($q4) use ($search) {
                                $q4->where('name', 'like', "%{$search}%");
                            })
                            ->orWhereHas('unit', function($q5) use ($search) {
                                $q5->where('name', 'like', "%{$search}%");
                            });
                        });
                    }
                })

                ->addColumn('action', function($row) {

                    // View Button
                    $showBtn = '<button class="btn btn-icon btn-soft-info btn-show" data-id="'.$row->id.'" title="View Details">
                                    <i class="fa-regular fa-eye"></i>
                                </button>';

                    // Edit Button
                    $editBtn = '<a href="'.route('products.edit', $row->id).'" class="btn btn-icon btn-soft-primary" title="Edit Product">
                                    <i class="fa-regular fa-pen-to-square"></i>
                                </a>';

                    // Delete Button
                    $deleteForm = '
                        <form class="delete-form d-inline" action="'.route('products.destroy', $row->id).'" method="POST">
                            '.csrf_field().method_field('DELETE').'
                            <button type="button" class="btn btn-icon btn-soft-danger btn-delete" title="Delete Product">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                        </form>
                    ';

                    // Wrapping all buttons in a centered flex div
                    return '<div class="d-flex align-items-center justify-content-center gap-2">'.$showBtn.$editBtn.$deleteForm.'</div>';
                })
                ->rawColumns(['image', 'status', 'colors', 'stock', 'action'])
                ->make(true);

        }

        $categories = Category::select('id', 'name')->get();
        $brands = Brand::select('id', 'name')->get();
        $units = Unit::select('id', 'name')->get();
        $subcategories = SubCategory::select('id', 'name')->get();

        return view('backend.product.index', compact('categories', 'brands', 'units', 'subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $units = Unit::all();
        $variations = Variation::all();
        $colors = Color::all();

        return view('backend.product.create', compact('categories', 'brands', 'units','variations','colors'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|integer',
            'image'          => 'required|image|mimes:jpeg,png,jpg,gif,webp',
            'purchase_price' => 'required|numeric',
            'selling_price'  => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {
            $slug = Str::slug($request->name);
            $sku  = $request->sku ?? 'SKU-' . strtoupper(Str::random(4));

            $mainImage = null;
            $sizeGuide = null;
            if ($request->hasFile('image')) {
                $imageName = time() . '_' . uniqid() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('uploads/products'), $imageName);
                $mainImage = 'uploads/products/' . $imageName;
            }

            if ($request->hasFile('size_guide')) {
                $sizeGuideName = time() . '_' . uniqid() . '.' . $request->size_guide->getClientOriginalExtension();
                $request->size_guide->move(public_path('uploads/products'), $sizeGuideName);
                $sizeGuide = 'uploads/products/' . $sizeGuideName;
            }

            $mainPrice    = $request->main_price;
            $sellingPrice = $request->selling_price;
            $discountType = $request->discount_type;
            $discountAmount = $mainPrice - $sellingPrice;
            $discountValue = 0;

            if ($discountType === 'percent') {
                if ($mainPrice > 0) {
                    $discountValue = ($discountAmount / $mainPrice) * 100;
                }

            } elseif ($discountType === 'fixed') {
                $discountValue = $discountAmount;
            }
            $product = Product::create([
                'name'              => $request->name,
                'slug'              => $slug,
                'sku'               => $sku,
                'barcode'           => $request->barcode,
                'image'             => $mainImage,
                'size_guide'        => $sizeGuide,
                'category_id'       => $request->category_id,
                'subcategory_id'    => $request->subcategory_id,
                'brand_id'          => $request->brand_id,
                'vendor_id'         => $request->vendor_id ?? null,
                'variation_id'      => $request->variation_id ?? null,
                'unit_id'           => $request->unit_id,
                'purchase_price'    => $request->purchase_price,
                'selling_price'     => $request->selling_price,
                'discount_type'     => $request->discount_type,
                'discount_value'    => $discountValue,
                'main_price'        => $request->main_price ?? 0,
                'alert_quantity'    => $request->alert_quantity ?? 0,
                'tax_rate'          => $request->tax_rate ?? 0,
                'weight'            => $request->weight ?? null,
                'dimensions'        => $request->dimensions ?? null,
                'is_featured'       => $request->is_featured ?? 1,
                'is_new'            => $request->is_new ?? 1,
                'is_bestseller'     => $request->is_bestseller ?? 1,
                'is_trending'       => $request->is_trending ?? 1,
                'product_type'      => $request->product_type,
                'status'            => $request->status ?? 1,
                'short_description' => $request->short_description,
                'description'       => $request->description,
            ]);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imgName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/products'), $imgName);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image'      => 'uploads/products/' . $imgName,
                    ]);
                }
            }

            if (!empty($request->variation_sizes)) {
                foreach ($request->variation_sizes as $index => $sizeName) {
                    if ($sizeName) {
                        ProductSize::create([
                            'product_id'       => $product->id,
                            'size_id'             => $sizeName,
                            'additional_price' => $request->size_prices[$index] ?? 0,
                        ]);
                    }
                }
            } 

            if (!empty($request->color_image_names)) {

                foreach ($request->color_image_names as $index => $colorId) {

                    if (!$colorId) continue;

                    $color = ProductColor::create([
                        'product_id' => $product->id,
                        'color_id'   => (int) $colorId,
                    ]);

                    // IMPORTANT: check exists properly
                    if ($request->hasFile("color_images.$index")) {

                        foreach ($request->file("color_images.$index") as $image) {

                            if ($image instanceof \Illuminate\Http\UploadedFile) {

                                $imgName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
                                $image->move(public_path('uploads/product_colors'), $imgName);

                                ProductColorImage::create([
                                    'product_color_id' => $color->id,
                                    'image' => 'uploads/product_colors/'.$imgName,
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('products.index')->with('success','product create successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error','Something wrong!');
        }
    }

    /**
     * Show the Bulk Upload View
     */
    public function bulkUploadView()
    {
        return view('backend.product.bulk_upload');
    }

    /**
     * Process the Bulk Upload Excel File
     */
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
            return redirect()->route('products.index')
                ->with('success', 'Bulk products uploaded successfully!');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return redirect()->back()
                ->with('error', 'Validation Error on Row '.$failures[0]->row().': '.$failures[0]->errors()[0]);
        } catch (\Exception $e) {

            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Download the Demo Excel Format
     */
    public function downloadDemo()
    {
        return Excel::download(new ProductDemoExport, 'Product_Bulk_Upload_Format.xlsx');
    }

    /**
     * Display the specified resource.
     */


    public function show($id)
    {
        // MAIN PRODUCT
        $product = DB::table('products')
            ->where('id', $id)
            ->first();

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // CATEGORY
        $category = DB::table('categories')
            ->select('id', 'name')
            ->where('id', $product->category_id)
            ->first();

        // SUB CATEGORY
        $subCategory = DB::table('sub_categories')
            ->select('id', 'name')
            ->where('id', $product->subcategory_id)
            ->first();

        // BRAND
        $brand = DB::table('brands')
            ->select('id', 'name')
            ->where('id', $product->brand_id)
            ->first();

        // UNIT
        $unit = DB::table('units')
            ->select('id', 'name')
            ->where('id', $product->unit_id)
            ->first();

        $images = DB::table('product_images')
            ->where('product_id', $id)
            ->pluck('image')
            ->map(fn($img) => asset($img))
            ->toArray();

        $colors = DB::table('product_colors as pc')
            ->join('colors as c', 'c.id', '=', 'pc.color_id')
            ->where('pc.product_id', $id)
            ->select(
                'pc.id',
                'c.id as color_id',  
                'c.name',
                'c.code'
            )
            ->get();

        // COLOR IMAGES
        $colorImages = [];
        foreach ($colors as $color) {
            $imgs = DB::table('product_color_images')
                ->where('product_color_id', $color->id)
                ->pluck('image')
                ->map(fn($img) => asset($img))
                ->toArray();

            $colorImages[] = [
                'id'     => $color->color_id,  
                'name'   => $color->name,
                'code'   => $color->code,
                'images' => $imgs,
            ];
        }

        $sizes = DB::table('product_sizes')->where('product_id', $id)->select('id', 'size')->get();

        $stocks = DB::table('product_stocks as s')
            ->leftJoin('colors as c', 'c.id', '=', 's.color_id')
            ->leftJoin('product_sizes as sz', 'sz.id', '=', 's.size_id')  
            ->where('s.product_id', $id)
            ->select(
                's.quantity',
                'c.id as color_id',
                'c.name as color_name',
                'c.code as color_code',
                'sz.id as size_id',
                'sz.size as size'
            )
            ->get()
            ->map(function ($stock) {
                return [
                    'color' => $stock->color_name ? [
                        'id'   => $stock->color_id,
                        'name' => $stock->color_name,
                        'code' => $stock->color_code,
                    ] : null,

                    'size' => $stock->size ? [
                        'id'   => $stock->size_id,
                        'size' => $stock->size,
                    ] : null,

                    'quantity' => $stock->quantity,
                ];
            });

        return response()->json([
            'id'                => $product->id,
            'name'              => $product->name,
            'sku'               => $product->sku,
            'barcode'           => $product->barcode,
            'category'          => $category,
            'subCategory'       => $subCategory,
            'brand'             => $brand,
            'unit'              => $unit,
            'purchase_price'    => $product->purchase_price,
            'selling_price'     => $product->selling_price,
            'discount_type'     => $product->discount_type,
            'main_price'        => $product->main_price,
            'alert_quantity'    => $product->alert_quantity,
            'short_description' => $product->short_description,
            'description'       => $product->description,
            'is_featured'       => $product->is_featured,
            'is_new'            => $product->is_new,
            'is_bestseller'     => $product->is_bestseller,
            'is_trending'       => $product->is_trending,
            'status'            => $product->status,
            'image'             => $product->image ? asset($product->image) : null,
            'size_guide'        => $product->size_guide ? asset($product->size_guide) : null,
            'images'            => $images,
            'colors'            => $colorImages,
            'sizes'             => $sizes,
            'stocks'            => $stocks,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::with([
            'category',
            'subCategory',
            'brand',
            'unit',
            'sizes',
            'colors.images',
            'images'
        ])->findOrFail($id);

        $categories = Category::all();

        $subcategories = SubCategory::where('category_id', $product->category_id)->get();

        $brands = Brand::all();
        $units = Unit::all();
        $colors = Color::all();

       $variations = Variation::all();

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
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'category_id'    => 'required|integer',
            'purchase_price' => 'required|numeric',
            'selling_price'  => 'required|numeric',
        ]);

        DB::beginTransaction();

        try {

            $product = Product::with(['images', 'colors.images'])->findOrFail($id);

            $slug = Str::slug($request->name);

            /* ================= MAIN IMAGE ================= */
            if ($request->hasFile('image')) {
                if ($product->image && file_exists(public_path($product->image))) {
                    @unlink(public_path($product->image));
                }

                $imageName = time().'_'.uniqid().'.'.$request->image->getClientOriginalExtension();
                $request->image->move(public_path('uploads/products'), $imageName);
                $product->image = 'uploads/products/'.$imageName;
            }

            /* ================= SIZE GUIDE ================= */
            if ($request->hasFile('size_guide')) {
                if ($product->size_guide && file_exists(public_path($product->size_guide))) {
                    @unlink(public_path($product->size_guide));
                }

                $sizeGuideName = time().'_'.uniqid().'.'.$request->size_guide->getClientOriginalExtension();
                $request->size_guide->move(public_path('uploads/products'), $sizeGuideName);
                $product->size_guide = 'uploads/products/'.$sizeGuideName;
            }

            /* ================= DISCOUNT ================= */
            $mainPrice     = $request->main_price ?? 0;
            $sellingPrice  = $request->selling_price ?? 0;
            $discountValue = 0;
            $discountAmount = $mainPrice - $sellingPrice;

            if ($request->discount_type === 'percent' && $mainPrice > 0) {
                $discountValue = ($discountAmount / $mainPrice) * 100;
            } elseif ($request->discount_type === 'fixed') {
                $discountValue = $discountAmount;
            }

            /* ================= PRODUCT UPDATE ================= */
            $product->update([
                'name'              => $request->name,
                'slug'              => $slug,
                'barcode'           => $request->barcode,
                'category_id'       => $request->category_id,
                'subcategory_id'    => $request->subcategory_id,
                'brand_id'          => $request->brand_id,
                'variation_id'      => $request->variation_id ?? null,
                'unit_id'           => $request->unit_id,
                'purchase_price'    => $request->purchase_price,
                'selling_price'     => $request->selling_price,
                'main_price'        => $request->main_price,
                'discount_type'     => $request->discount_type,
                'discount_value'    => $discountValue,
                'alert_quantity'    => $request->alert_quantity ?? 0,
                'product_type'      => $request->product_type,
                'short_description' => $request->short_description,
                'description'       => $request->description,
                'status'            => $request->status ?? 1,
                'is_featured'       => $request->is_featured ?? 1,
                'is_new'            => $request->is_new ?? 1,
                'is_bestseller'     => $request->is_bestseller ?? 1,
                'is_trending'       => $request->is_trending ?? 1,
            ]);

            /* ================= PRODUCT IMAGES (FIXED) ================= */
            if ($request->hasFile('images')) {
                // DELETE OLD IMAGES (DB + FILE)
                foreach ($product->images as $oldImage) {
                    if ($oldImage->image && file_exists(public_path($oldImage->image))) {
                        @unlink(public_path($oldImage->image));
                    }
                    $oldImage->delete();
                }

                // ADD NEW IMAGES
                foreach ($request->file('images') as $image) {
                    $imgName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
                    $image->move(public_path('uploads/products'), $imgName);
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image'      => 'uploads/products/'.$imgName,
                    ]);
                }
            }

            /* ================= SIZES ================= */
            if ($request->variation_sizes) {
                ProductSize::where('product_id', $product->id)->delete();
                foreach ($request->variation_sizes as $index => $sizeName) {
                    if ($sizeName) {
                        ProductSize::create([
                            'product_id'       => $product->id,
                            'size'             => $sizeName,
                            'additional_price' => $request->size_prices[$index] ?? 0,
                        ]);
                    }
                }
            }

            if ($request->has('color_image_ids')) {
                foreach ($request->color_image_ids as $index => $colorId) {
                    if (!$colorId) continue;
                    $color = ProductColor::firstOrCreate([
                        'product_id' => $product->id,
                        'color_id'   => $colorId,
                    ]);
                    $newImages = $request->color_images[$index] ?? null;
                    /* ================= IF NEW IMAGES EXISTS ================= */
                    if (!empty($newImages)) {
                        foreach ($color->images as $oldImage) {
                            if ($oldImage->image && file_exists(public_path($oldImage->image))) {
                                unlink(public_path($oldImage->image));
                            }
                            $oldImage->delete();
                        }

                        // 🔥 INSERT NEW IMAGES
                        foreach ($newImages as $image) {
                            if ($image instanceof \Illuminate\Http\UploadedFile) {
                                $imgName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
                                $image->move(public_path('uploads/product_colors'), $imgName);
                                ProductColorImage::create([
                                    'product_color_id' => $color->id,
                                    'image' => 'uploads/product_colors/'.$imgName,
                                ]);
                            }
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('products.index')->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::with(['images', 'sizes', 'colors.images'])->findOrFail($id);
        if($product->is_purchased == 1)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Product is purchased, cannot delete!',
            ]);
        }
        DB::beginTransaction();

        try {
            // 🔹 Delete main image
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            // 🔹 Delete multiple images
            foreach ($product->images as $img) {
                if (file_exists(public_path($img->image))) {
                    unlink(public_path($img->image));
                }
                $img->delete();
            }

            $product->sizes()->delete();
            foreach ($product->colors as $color) {
                foreach ($color->images as $colorImg) {
                    if (file_exists(public_path($colorImg->image))) {
                        unlink(public_path($colorImg->image));
                    }
                    $colorImg->delete();
                }
                $color->delete();
            }
            $product->delete();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Product deleted successfully!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong: '.$e->getMessage(),
            ]);
        }
    }


    public function getSubcategories($category_id)
    {
        $subcategories = SubCategory::where('category_id', $category_id)->get();
        return response()->json($subcategories);
    }

    public function deleteColor($id)
    {
        $color = ProductColor::with('images')->findOrFail($id);

        // delete images from storage
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

}
