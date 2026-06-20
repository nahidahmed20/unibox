<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $guarded = ['id'];

    public function images()
    {
        return $this->hasMany(ProductColorImage::class, 'color_id');
    }

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'product_color',
            'color_id',
            'product_id'
        );
    }

    
}
