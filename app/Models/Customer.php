<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $guarded  = ['id'];
    public function user() {
        return $this->belongsTo(User::class);
    }
}
