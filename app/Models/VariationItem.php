<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariationItem extends Model
{
    protected $guarded = ['id'];

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function variation()
    {
        return $this->belongsTo(Variation::class);
    }
}
