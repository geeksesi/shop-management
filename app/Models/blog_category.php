<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class blog_category extends Model
{
    use HasFactory;

     public $fillable = [
        'title',
        'description',
        'id',
    ];

     public function posts()
     {
         return $this->belongsToMany(blog_post::class);
     }


}
