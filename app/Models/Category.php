<?php

namespace App;
#TODO Check namespace

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    #TODO Read about guard and fillable
    protected $guarded = ['name','meta_description','slug'];

    #TODO we dont need create method
    public static function create(array $array)
    {
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
}
