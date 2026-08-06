<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'attributes' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
