<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $table = 'content';

    protected $fillable = [
        'site_id',
        'title',
        'score',
        'price',
        'discount-price',
        'discount-percent',
        'stock',
        'url',
        'featured_image'
    ];
    public function sites()
    {
        return $this->belongsTo(Site::class,'site_id');

    }
}
