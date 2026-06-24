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

    public static function updateStock($productId, $colorId, $sizeId, $quantity)
    {
        return self::updateOrCreate(
            [
                'product_id' => $productId,
                'color_id'   => $colorId,
                'size_id'    => $sizeId, 
            ],
            [
                'quantity'   => \Illuminate\Support\Facades\DB::raw("quantity + " . (int)$quantity)
            ]
        );
    }

}
