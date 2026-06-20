<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(ProductColor::class, 'color_id');
    }

    public function size()
    {
        return $this->belongsTo(ProductSize::class, 'size_id');
    }

}
