<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Color;
use App\Models\Size; 
use App\Models\ProductVariant; 
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class ProductsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $categoryName = !empty($row['category']) ? trim($row['category']) : 'Uncategorized';
        $category = Category::firstOrCreate(
            ['name' => $categoryName], 
            ['slug' => Str::slug($categoryName), 'status' => 1]
        );
        
        $subcategoryId = null;
        if (!empty($row['subcategory'])) {
            $subcategory = Subcategory::firstOrCreate(
                ['name' => trim($row['subcategory']), 'category_id' => $category->id],
                ['slug' => Str::slug($row['subcategory']), 'status' => 1]
            );
            $subcategoryId = $subcategory->id;
        }

        $brandId = null;
        if (!empty($row['brand'])) {
            $brand = Brand::firstOrCreate(
                ['name' => trim($row['brand'])], 
                ['slug' => Str::slug($row['brand']), 'status' => 1]
            );
            $brandId = $brand->id;
        }

        $unitId = null;
        if (!empty($row['unit'])) {
            $unit = Unit::firstOrCreate(['name' => trim($row['unit'])], ['status' => 1]);
            $unitId = $unit->id;
        }

        $mainPrice    = isset($row['main_price']) ? (float) $row['main_price'] : 0;
        $sellingPrice = isset($row['selling_price']) ? (float) $row['selling_price'] : 0;
        $purchasePrice = isset($row['purchase_price']) ? (float) $row['purchase_price'] : 0;
        $discountType = isset($row['discount_type']) ? strtolower(trim($row['discount_type'])) : null;
        
        $discountAmount = $mainPrice - $sellingPrice;
        $discountValue = 0;

        if ($discountType === 'percent' && $mainPrice > 0) {
            $discountValue = ($discountAmount / $mainPrice) * 100;
        } elseif ($discountType === 'fixed') {
            $discountValue = $discountAmount;
        }

        $productName = !empty($row['name']) ? trim($row['name']) : 'Unnamed Product - ' . time();
        $slug = Str::slug($productName) . '-' . Str::random(4);
        $sku = !empty($row['sku']) ? $row['sku'] : 'SKU-' . strtoupper(Str::random(6));

        $mainImage = null;
        if (isset($row['main_image']) && !empty(trim($row['main_image']))) {
            $mainImage = 'uploads/products/' . trim($row['main_image']);
        }

        DB::beginTransaction();

        try {
            $productType = (!empty(trim($row['sizes'])) || !empty(trim($row['colors']))) ? 'multiple' : 'single';
            $stock = ($productType === 'single') ? ($row['stock'] ?? 0) : 0;

            $product = Product::create([
                'name'              => $productName,
                'slug'              => $slug,
                'sku'               => $sku,
                'barcode'           => $row['barcode'] ?? null,
                'image'             => $mainImage, 
                'category_id'       => $category->id,
                'subcategory_id'    => $subcategoryId,
                'brand_id'          => $brandId,
                'unit_id'           => $unitId,
                'purchase_price'    => $purchasePrice,
                'selling_price'     => $sellingPrice,
                'discount_type'     => $discountType,
                'discount_value'    => $discountValue,
                'main_price'        => $mainPrice,
                'alert_quantity'    => $row['alert_quantity'] ?? 5,
                'stock'             => $stock,
                'short_description' => $row['short_description'] ?? null,
                'description'       => $row['description'] ?? null,
                'status'            => $row['status'] ?? 1,
                'product_type'      => $productType,
                'is_featured'   => (isset($row['is_featured']) && strtolower(trim($row['is_featured'])) === 'yes') ? 1 : 0,
                'is_new'        => (isset($row['is_new']) && strtolower(trim($row['is_new'])) === 'yes') ? 1 : 0,
                'is_purchased'  => (isset($row['is_purchased']) && strtolower(trim($row['is_purchased'])) === 'yes') ? 1 : 0,
                'is_bestseller' => (isset($row['is_bestseller']) && strtolower(trim($row['is_bestseller'])) === 'yes') ? 1 : 0,
                'is_trending'   => (isset($row['is_trending']) && strtolower(trim($row['is_trending'])) === 'yes') ? 1 : 0,
            ]);

            // ================= NEW VARIANT MATRIX LOGIC =================
            if ($productType === 'multiple') {
                $sizesArray = [];
                $colorsArray = [];

                // Sizes
                if (isset($row['sizes']) && !empty(trim($row['sizes']))) {
                    $sizesArray = array_map('trim', explode(',', $row['sizes']));
                }

                // Colors
                if (isset($row['colors']) && !empty(trim($row['colors']))) {
                    $colorNames = array_map('trim', explode(',', $row['colors']));
                    foreach ($colorNames as $colorName) {
                        $colorRecord = Color::firstOrCreate(['name' => $colorName], ['code' => '#000000']);
                        $colorsArray[] = $colorRecord->id;
                        $product->colors()->firstOrCreate(['color_id' => $colorRecord->id]);
                    }
                }

                $combinations = [];
                if (!empty($colorsArray) && !empty($sizesArray)) {
                    foreach ($colorsArray as $colorId) {
                        foreach ($sizesArray as $sizeName) {
                            $combinations[] = ['color_id' => $colorId, 'size_name' => $sizeName];
                        }
                    }
                } elseif (!empty($colorsArray)) {
                    foreach ($colorsArray as $colorId) {
                        $combinations[] = ['color_id' => $colorId, 'size_name' => null];
                    }
                } elseif (!empty($sizesArray)) {
                    foreach ($sizesArray as $sizeName) {
                        $combinations[] = ['color_id' => null, 'size_name' => $sizeName];
                    }
                }

                $totalVariantStock = 0;
                $defaultVariantStock = isset($row['stock']) && $row['stock'] > 0 ? (int) $row['stock'] : 1;

                foreach ($combinations as $index => $comb) {
                    $colorCode = '';
                    if ($comb['color_id']) {
                        $cModel = Color::find($comb['color_id']);
                        $colorCode = $cModel ? strtoupper(substr($cModel->name, 0, 3)) : '';
                    }
                    $sizeCode = $comb['size_name'] ? strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $comb['size_name'])) : '';
                    $variantSku = $product->sku . ($colorCode ? '-' . $colorCode : '') . ($sizeCode ? '-' . $sizeCode : '') . '-' . ($index + 1);
                    $variantSizeId = $comb['size_name']; 

                    ProductVariant::create([
                        'product_id'     => $product->id,
                        'color_id'       => $comb['color_id'],
                        'size_id'        => $variantSizeId, 
                        'sku'            => $variantSku,
                        'purchase_price' => $purchasePrice,
                        'selling_price'  => $sellingPrice,
                        'stock'          => $defaultVariantStock,
                    ]);

                    $totalVariantStock += $defaultVariantStock;
                }

                $product->update(['stock' => $totalVariantStock]);
            }

            DB::commit();
            return $product;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e; 
        }
    }
}