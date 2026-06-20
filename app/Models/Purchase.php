<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = ['id'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'purchase_details')
            ->withPivot('buying_price', 'quantity');
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class);
    }
}
