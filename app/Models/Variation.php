<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany(VariationItem::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'variation_items', 'variation_id', 'size_id');
    }
    
}
