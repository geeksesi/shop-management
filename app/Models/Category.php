<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['name','meta_description','slug'];

    public static function create(array $array)
    {
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
}
