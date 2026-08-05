<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // One Attribute has many Options
    public function options()
    {
        return $this->hasMany(AttributeOption::class);
    }
}
