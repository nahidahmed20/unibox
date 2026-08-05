<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function productcolors()
    {
        return $this->belongsToMany(
            Color::class,
            'product_colors',
            'product_id',
            'color_id'
        );
    }

    public function colors()
    {
        return $this->hasMany(ProductColor::class);
    }

    public function color() {
        return $this->belongsTo(Color::class, 'color_id');
    }
    public function sizes()
    {
        return $this->hasMany(ProductSize::class, 'product_id');
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function getTotalQuantityAttribute()
    {
        if ($this->variants && $this->variants->count() > 0) {
            return $this->variants->sum('stock');
        }
        return $this->stock ?? 0; 
    }

    public function getFinalPriceAttribute()
    {
        $now = now();
        if ($this->discount_type
            && $this->discount_starts_at
            && $this->discount_ends_at
            && $now->between($this->discount_starts_at, $this->discount_ends_at)) {

            return $this->discount_type === 'percent'
                ? $this->selling_price - ($this->selling_price * $this->discount_value / 100)
                : $this->selling_price - $this->discount_value;
        }
        return $this->selling_price;
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'product_attributes', 'product_id', 'attribute_id');
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class, 'product_id');
    }
    
}
