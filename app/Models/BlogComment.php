<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogComment extends Model
{
    use HasFactory;

     public $fillable = [
        'description',
        'author_name',
    ];

     public function post()
     {
         return $this->belongsTo(blogPost::class);
     }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
