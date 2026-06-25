<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $guarded = ['id'];

    public function purchaseDetails()
    {
        return $this->hasMany(
            PurchaseDetail::class,
            'product_size_id'
        );
    }

    public function productSizes()
    {
        return $this->hasMany(ProductSize::class, 'size_id');
    }
}
