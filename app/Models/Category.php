<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    #TODO Read about guard and fillable
    protected $fillable = ['title','id','description','parent_id'];

    protected static function newFactory(): Factory
    {
        return CategoryFactory::new();
    }

/*    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }*/
}
