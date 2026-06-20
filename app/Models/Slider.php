<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table ='sliders';
    protected $fillable = ['title', 'short_description', 'image' ,'mobile_image', 'status', 'link'];
}
