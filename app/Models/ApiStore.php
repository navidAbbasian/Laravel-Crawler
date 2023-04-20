<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiStore extends Model
{
    use HasFactory;

    protected $table = 'api_store';

    protected $fillable = [
        'raw_api',
        'polished_api'
    ];
//    protected function dirty_api(): Attribute
//    {
//        return Attribute::make(
//            get: fn ($value) => json_decode($value, true),
//            set: fn ($value) => json_encode($value),
//        );
//    }
//    protected function clean_api(): Attribute
//    {
//        return Attribute::make(
//            get: fn ($value) => json_decode($value, true),
//            set: fn ($value) => json_encode($value),
//        );
//    }
}
