<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function color()
    {
        return $this->belongsTo(ProductColor::class,'color_id');
    }

    public function size()
    {
        return $this->belongsTo(ProductSize::class, 'size_id');
    }
}
