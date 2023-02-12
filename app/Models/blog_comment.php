<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class blog_comment extends Model
{
    use HasFactory;

     public $fillable = [
        'description',
        'author_name',
    ];

     public function post()
     {
         return $this->belongsTo(blog_post::class);
     }

//    public function user()
//     {
//         return $this->belongsTo()
//     }
}
