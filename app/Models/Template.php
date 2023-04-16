<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $table = 'template';
    protected $fillable = [
        'title',
        'score',
        'price',
        'discount-price',
        'discount-percent',
        'stock',
        'url',
        'featured_image'
        ];
}
