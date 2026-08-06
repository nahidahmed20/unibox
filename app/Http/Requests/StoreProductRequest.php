<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'              => 'required|string|max:255',
            'sku'               => 'nullable|string|max:100|unique:products,sku', 
            'barcode'           => 'nullable|string|max:100|unique:products,barcode',
            'category_id'       => 'required|integer',
            'subcategory_id'    => 'nullable|integer',
            'brand_id'          => 'nullable|integer',
            'vendor_id'         => 'nullable|integer',
            'unit_id'           => 'nullable|integer',
            'variation_id'      => 'nullable|integer',
            'purchase_price'    => 'nullable|numeric',
            'selling_price'     => 'required|numeric',
            'main_price'        => 'nullable|numeric',
            'max_price'         => 'nullable|numeric',
            'discount_type'     => 'nullable|string|in:fixed,percent',
            'alert_quantity'    => 'nullable|numeric',
            'stock'             => 'nullable|integer',
            'tax_rate'          => 'nullable|numeric',
            'weight'            => 'nullable|numeric',
            'dimensions'        => 'nullable|string|max:100',
            'image'             => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
            'size_guide'        => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*'          => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
            'short_description' => 'nullable|string',
            'description'       => 'nullable|string',
            'is_featured'       => 'nullable|integer|in:0,1',
            'is_new'            => 'nullable|integer|in:0,1',
            'is_bestseller'     => 'nullable|integer|in:0,1',
            'is_trending'       => 'nullable|integer|in:0,1',
            'status'            => 'nullable|integer|in:0,1',
            'product_type'             => 'required|string|in:single,multiple',
            'variants'                 => 'nullable|array',
            'variants.*.sku'           => 'required_if:product_type,multiple|string|distinct',
            'variants.*.purchase_price'=> 'nullable|numeric',
            'variants.*.selling_price' => 'required_if:product_type,multiple|numeric',
            'variants.*.stock'         => 'required_if:product_type,multiple|integer',
            'variants.*.color_id'      => 'nullable|integer',
            'variants.*.size_id'       => 'nullable|integer',
            'color_image_ids'   => 'nullable|array',
            'color_image_names' => 'nullable|array',
            'color_images'      => 'nullable|array',
            'attribute_ids'     => 'nullable|array',
            'color_images.*.*'  => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'extra_note'        => 'nullable|string|max:1000',
            'is_calculator'     => 'nullable|integer|in:0,1',
            'price_per_sqft'    => 'nullable|required_if:is_calculator,1|numeric|min:0',
        ];
    }
}