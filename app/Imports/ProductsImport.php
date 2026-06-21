<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\ProductSize;
use App\Models\ProductColor;
use App\Models\Color;
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
                'purchase_price'    => $row['purchase_price'] ?? 0,
                'selling_price'     => $sellingPrice,
                'discount_type'     => $discountType,
                'discount_value'    => $discountValue,
                'main_price'        => $mainPrice,
                'alert_quantity'    => $row['alert_quantity'] ?? 5,
                'short_description' => $row['short_description'] ?? null,
                'description'       => $row['description'] ?? null,
                'status'            => $row['status'] ?? 1,
                'product_type'      => (!empty($row['sizes']) || !empty($row['colors'])) ? 'multiple' : 'single',
                'is_featured'   => (isset($row['is_featured']) && strtolower(trim($row['is_featured'])) === 'yes') ? 1 : 0,
                'is_new'        => (isset($row['is_new']) && strtolower(trim($row['is_new'])) === 'yes') ? 1 : 0,
                'is_purchased'  => (isset($row['is_purchased']) && strtolower(trim($row['is_purchased'])) === 'yes') ? 1 : 0,
                'is_bestseller' => (isset($row['is_bestseller']) && strtolower(trim($row['is_bestseller'])) === 'yes') ? 1 : 0,
                'is_trending'   => (isset($row['is_trending']) && strtolower(trim($row['is_trending'])) === 'yes') ? 1 : 0,
            ]);

            if (isset($row['sizes']) && !empty(trim($row['sizes']))) {
                $sizes = array_map('trim', explode(',', $row['sizes']));
                foreach ($sizes as $size) {
                    ProductSize::create([
                        'product_id'       => $product->id,
                        'size'             => $size,
                        'additional_price' => 0,
                    ]);
                }
            } else {
                ProductSize::create([
                    'product_id'       => $product->id,
                    'size'             => '',
                    'additional_price' => 0,
                ]);
            }

            if (isset($row['colors']) && !empty(trim($row['colors']))) {
                $colorNames = array_map('trim', explode(',', $row['colors']));
                foreach ($colorNames as $colorName) {
                    $colorRecord = Color::firstOrCreate(['name' => $colorName], ['code' => '#000000']);
                    ProductColor::create([
                        'product_id' => $product->id,
                        'color_id'   => $colorRecord->id,
                    ]);
                }
            }

            DB::commit();
            return $product;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e; 
        }
    }
}