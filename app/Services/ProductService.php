<?php

namespace App\Services;

use App\Models\PriceHistory;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductColorImage;
use App\Models\ProductImage;
use App\Models\ProductStock;
use App\Models\ProductVariant;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Str;

class ProductService
{
    public function handleStore(array $data, $request)
    {
        return DB::transaction(function () use ($data, $request) {
            
            $slug = Str::slug($data['name']);
            $sku  = !empty($data['sku']) ? $data['sku'] : 'SKU-' . strtoupper(Str::random(6));

            $mainImage = $this->uploadFile($request, 'image', 'uploads/products');
            $sizeGuide = $this->uploadFile($request, 'size_guide', 'uploads/products');

            $discountValue = $this->calculateDiscount(
                $data['main_price'] ?? 0, 
                $data['selling_price'] ?? 0, 
                $data['discount_type'] ?? null
            );

            $product = Product::create([
                'name'              => $data['name'],
                'slug'              => $slug,
                'sku'               => $sku,
                'barcode'           => !empty($data['barcode']) ? $data['barcode'] : null,
                'category_id'       => $data['category_id'],
                'subcategory_id'    => !empty($data['subcategory_id']) ? $data['subcategory_id'] : null,
                'brand_id'          => !empty($data['brand_id']) ? $data['brand_id'] : null,
                'vendor_id'         => !empty($data['vendor_id']) ? $data['vendor_id'] : null,
                'unit_id'           => !empty($data['unit_id']) ? $data['unit_id'] : null,
                'variation_id'      => !empty($data['variation_id']) ? $data['variation_id'] : null,
                'purchase_price'    => $data['purchase_price'] ?? 0,
                'selling_price'     => $data['selling_price'] ?? 0,
                'discount_type'     => !empty($data['discount_type']) ? $data['discount_type'] : null,
                'discount_value'    => $discountValue,
                'main_price'        => $data['main_price'] ?? 0,
                'max_price'         => $data['max_price'] ?? 0,
                'alert_quantity'    => $data['alert_quantity'] ?? 0,
                'stock'             => (($data['product_type'] ?? '') === 'single') ? ($data['stock'] ?? 0) : 0,
                'tax_rate'          => $data['tax_rate'] ?? 0,
                'weight'            => $data['weight'] ?? null,
                'dimensions'        => $data['dimensions'] ?? null,
                'image'             => $mainImage,
                'size_guide'        => $sizeGuide,
                'short_description' => $data['short_description'] ?? null,
                'description'       => $data['description'] ?? null,
                'extra_note'        => $data['extra_note'] ?? null,
                'is_featured'       => $data['is_featured'] ?? 1,
                'is_new'            => $data['is_new'] ?? 1,
                'is_purchased'      => 0, 
                'is_bestseller'     => $data['is_bestseller'] ?? 0,
                'is_trending'       => $data['is_trending'] ?? 0,
                'product_type'      => $data['product_type'] ?? 'single',
                'status'            => $data['status'] ?? 1,
            ]);

            if ($request->hasFile('images')) {
                $galleryPath = public_path('uploads/products');
                if (!File::exists($galleryPath)) {
                    File::makeDirectory($galleryPath, 0755, true, true);
                }

                foreach ($request->file('images') as $image) {
                    if ($image instanceof UploadedFile && $image->isValid()) {
                        $imgName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                        $image->move($galleryPath, $imgName);
                        $product->images()->create([
                            'image' => 'uploads/products/' . $imgName,
                        ]);
                    }
                }
            }

            /* ================= NEW VARIANT MATRIX SAVE ================= */
            if (($data['product_type'] ?? '') === 'multiple' && !empty($data['variants'])) {
                $totalVariantStock = 0; 

                foreach ($data['variants'] as $variantData) {
                    $variant = $product->variants()->create([
                        'color_id'       => !empty($variantData['color_id']) ? $variantData['color_id'] : null,
                        'size_id'        => !empty($variantData['size_id']) ? $variantData['size_id'] : null, // এখানে size_id করা হয়েছে
                        'sku'            => !empty($variantData['sku']) ? $variantData['sku'] : $sku . '-' . strtoupper(Str::random(4)),
                        'purchase_price' => $variantData['purchase_price'] ?? 0,
                        'selling_price'  => $variantData['selling_price'] ?? 0,
                        'stock'          => $variantData['stock'] ?? 0,
                    ]);
                    
                    $totalVariantStock += ($variantData['stock'] ?? 0);
                }

                $product->update(['stock' => $totalVariantStock]);
            }

            $colorIds = $data['color_image_ids'] ?? $data['color_image_names'] ?? [];
            if (!empty($colorIds)) {
                $colorPath = public_path('uploads/product_colors');
                if (!File::exists($colorPath)) {
                    File::makeDirectory($colorPath, 0755, true, true);
                }

                foreach ($colorIds as $index => $colorId) {
                    if (empty($colorId)) continue;

                    $colorRecord = $product->colors()->create([
                        'color_id' => (int) $colorId,
                    ]);

                    if ($request->hasFile("color_images.$index")) {
                        $newImages = $request->file("color_images.$index");
                        $imagesArray = is_array($newImages) ? $newImages : [$newImages];

                        foreach ($imagesArray as $image) {
                            if ($image instanceof UploadedFile && $image->isValid()) {
                                $imgName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                                $image->move($colorPath, $imgName);
                                
                                $colorRecord->images()->create([
                                    'image' => 'uploads/product_colors/' . $imgName,
                                ]);
                            }
                        }
                    }
                }
            }

            return $product;
        });
    }

    private function calculateDiscount($mainPrice, $sellingPrice, $discountType)
    {
        $discountAmount = $mainPrice - $sellingPrice;
        $discountValue = 0;

        if ($discountType === 'percent' && $mainPrice > 0) {
            $discountValue = ($discountAmount / $mainPrice) * 100;
        } elseif ($discountType === 'fixed') {
            $discountValue = $discountAmount;
        }

        return $discountValue;
    }

    private function uploadFile($request, $fieldName, $path)
    {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);
            if ($file instanceof UploadedFile && $file->isValid()) {
                $destinationPath = public_path($path);
                
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true, true);
                }

                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPath, $fileName);
                return $path . '/' . $fileName;
            }
        }
        return null;
    }

    public function handleUpdate($id, array $data, $request)
    {
        return DB::transaction(function () use ($id, $data, $request) {
            $product = Product::with(['images', 'colors.images', 'variants'])->findOrFail($id);
            $slug = Str::slug($data['name']);

            /* ================= MAIN IMAGE UPDATE ================= */
            $mainImage = $product->image;
            if ($request->hasFile('image')) {
                if ($product->image && File::exists(public_path($product->image))) {
                    File::delete(public_path($product->image)); 
                }
                $mainImage = $this->uploadFile($request, 'image', 'uploads/products');
            }

            /* ================= SIZE GUIDE UPDATE ================= */
            $sizeGuide = $product->size_guide;
            if ($request->hasFile('size_guide')) {
                if ($product->size_guide && File::exists(public_path($product->size_guide))) {
                    File::delete(public_path($product->size_guide));
                }
                $sizeGuide = $this->uploadFile($request, 'size_guide', 'uploads/products');
            }

            /* ================= DISCOUNT CALCULATION ================= */
            $discountValue = $this->calculateDiscount(
                $data['main_price'] ?? 0, 
                $data['selling_price'] ?? 0, 
                $data['discount_type'] ?? null
            );

            $product->update([
                'name'              => $data['name'],
                'slug'              => $slug,
                'barcode'           => !empty($data['barcode']) ? $data['barcode'] : $product->barcode,
                'category_id'       => $data['category_id'],
                'subcategory_id'    => !empty($data['subcategory_id']) ? $data['subcategory_id'] : null,
                'brand_id'          => !empty($data['brand_id']) ? $data['brand_id'] : null,
                'vendor_id'         => !empty($data['vendor_id']) ? $data['vendor_id'] : null,
                'unit_id'           => !empty($data['unit_id']) ? $data['unit_id'] : null,
                'variation_id'      => !empty($data['variation_id']) ? $data['variation_id'] : null,
                'purchase_price'    => $data['purchase_price'] ?? 0,
                'selling_price'     => $data['selling_price'] ?? 0,
                'discount_type'     => !empty($data['discount_type']) ? $data['discount_type'] : null,
                'discount_value'    => $discountValue,
                'main_price'        => $data['main_price'] ?? 0,
                'max_price'         => $data['max_price'] ?? 0,
                'alert_quantity'    => $data['alert_quantity'] ?? 0,
                'stock'             => (($data['product_type'] ?? '') === 'single') ? ($data['stock'] ?? 0) : 0,
                'tax_rate'          => $data['tax_rate'] ?? 0,
                'weight'            => $data['weight'] ?? null,
                'dimensions'        => $data['dimensions'] ?? null,
                'image'             => $mainImage,
                'size_guide'        => $sizeGuide,
                'short_description' => $data['short_description'] ?? null, 
                'description'       => $data['description'] ?? null,  
                'extra_note'        => $data['extra_note'] ?? null,     
                'is_featured'       => $data['is_featured'] ?? 1,
                'is_new'            => $data['is_new'] ?? 1,
                'is_bestseller'     => $data['is_bestseller'] ?? 0,
                'is_trending'       => $data['is_trending'] ?? 0,
                'product_type'      => $data['product_type'] ?? 'single',
                'status'            => $data['status'] ?? 1,
            ]);

            /* ================= PRODUCT GALLERY IMAGES ================= */
            if ($request->hasFile('images')) {
                foreach ($product->images as $oldImage) {
                    if ($oldImage->image && File::exists(public_path($oldImage->image))) {
                        File::delete(public_path($oldImage->image));
                    }
                    $oldImage->delete();
                }

                $galleryPath = public_path('uploads/products');
                if (!File::exists($galleryPath)) {
                    File::makeDirectory($galleryPath, 0755, true, true);
                }

                foreach ($request->file('images') as $image) {
                    if ($image instanceof UploadedFile && $image->isValid()) {
                        $imgName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                        $image->move($galleryPath, $imgName);
                        $product->images()->create([
                            'image' => 'uploads/products/' . $imgName,
                        ]);
                    }
                }
            }

            /* ================= NEW VARIANT MATRIX SAVE/UPDATE ================= */
            if (($data['product_type'] ?? '') === 'multiple' && !empty($data['variants'])) {
                $product->variants()->forceDelete(); 
                $totalVariantStock = 0;

                foreach ($data['variants'] as $variantData) {
                    $product->variants()->create([
                        'color_id'       => !empty($variantData['color_id']) ? $variantData['color_id'] : null, 
                        'size_id'        => !empty($variantData['size_id']) ? $variantData['size_id'] : null, // এখানে size_id করা হয়েছে    
                        'sku'            => !empty($variantData['sku']) ? $variantData['sku'] : $product->sku . '-' . strtoupper(Str::random(4)),     
                        'purchase_price' => $variantData['purchase_price'] ?? 0,
                        'selling_price'  => $variantData['selling_price'] ?? 0,
                        'stock'          => $variantData['stock'] ?? 0,
                    ]);
                    
                    $totalVariantStock += ($variantData['stock'] ?? 0);
                }
                
                $product->update(['stock' => $totalVariantStock]);

            } elseif (($data['product_type'] ?? '') === 'single') {
                $product->variants()->forceDelete();
                $product->update(['stock' => $data['stock'] ?? 0]);
            }

            /* ================= COLOR SPECIFIC IMAGES ================= */
            $colorIds = $data['color_image_ids'] ?? $data['color_image_names'] ?? [];
            if (!empty($colorIds)) {
                $colorPath = public_path('uploads/product_colors');
                if (!File::exists($colorPath)) {
                    File::makeDirectory($colorPath, 0755, true, true);
                }

                foreach ($colorIds as $index => $colorId) {
                    if (empty($colorId)) continue;

                    $colorRecord = $product->colors()->firstOrCreate([
                        'color_id' => (int) $colorId,
                    ]);

                    if ($request->hasFile("color_images.$index")) {
                        $newImages = $request->file("color_images.$index");
                        
                        foreach ($colorRecord->images as $oldImage) {
                            if ($oldImage->image && File::exists(public_path($oldImage->image))) {
                                File::delete(public_path($oldImage->image));
                            }
                            $oldImage->delete();
                        }

                        $imagesArray = is_array($newImages) ? $newImages : [$newImages];

                        foreach ($imagesArray as $image) {
                            if ($image instanceof UploadedFile && $image->isValid()) {
                                $imgName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                                $image->move($colorPath, $imgName);
                                
                                $colorRecord->images()->create([
                                    'image' => 'uploads/product_colors/' . $imgName,
                                ]);
                            }
                        }
                    }
                }
            }

            return $product;
        });
    }

    public function bulkPriceUpdate(array $data)
    {
        return DB::transaction(function () use ($data) {
            $query = Product::query();

            if (!empty($data['category_id'])) $query->where('category_id', $data['category_id']);
            if (!empty($data['brand_id']))    $query->where('brand_id', $data['brand_id']);

            $products = $query->get();

            foreach ($products as $product) {
                $oldPrice = $product->selling_price;

                $newPrice = $data['type'] === 'percent'
                    ? $oldPrice + ($oldPrice * $data['value'] / 100)
                    : $oldPrice + $data['value'];

                $newPrice = max(0, round($newPrice, 2));

                $product->update(['selling_price' => $newPrice]);

                PriceHistory::create([
                    'product_id' => $product->id,
                    'old_price'  => $oldPrice,
                    'new_price'  => $newPrice,
                    'changed_by' => auth()->id(),
                ]);
            }

            return $products->count();
        });
    }
}