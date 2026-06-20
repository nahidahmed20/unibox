<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductColorImage extends Model
{
    protected $guarded = ['id'];

    public function color()
    {
        return $this->belongsTo(ProductColor::class, 'product_color_id');
    }
}
