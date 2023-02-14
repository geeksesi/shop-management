<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;

     public $fillable = [
        'title',
        'description',
        'id',
    ];

     public function posts()
     {
         return $this->belongsToMany(blogPost::class);
     }


}
