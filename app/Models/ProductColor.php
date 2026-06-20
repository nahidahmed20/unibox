<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function images()
    {
        return $this->hasMany(ProductColorImage::class, 'product_color_id');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_colors', 'product_id', 'color_id');
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class, 'product_color_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    
}
