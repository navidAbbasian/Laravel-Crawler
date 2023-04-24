<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $table = 'fields';

    protected $fillable = [
        'template_id',
        'source',
        'destination'
    ];

    public function templates()
    {
        return $this->belongsTo(Template::class);
    }
}
