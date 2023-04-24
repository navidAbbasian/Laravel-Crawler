<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $table = 'templates';

    protected $fillable = [
        'endpoint_id',
        'site',
        'table'
        ];

    public function endpoints()
    {
        return $this->belongsTo(Endpoint::class);
    }

    public function fields(){
        return $this->hasMany(Field::class);

    }
}
