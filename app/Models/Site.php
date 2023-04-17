<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $table= 'site';

    protected $fillable = [
        'template_id',
        'site_url'
    ];

    public function templates()
    {
        return $this->hasOne(Template::class , 'id');
    }
    public function contents()
    {
        return $this->hasOne(Content::class);
    }
}
