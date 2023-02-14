<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    use HasFactory;

    public $fillable = [
        'title',
        'body',
        'thumbnail',
        'seo_description',
        'id',
    ];

    public function categories()
    {
        return $this->belongsToMany(blog_category::class);
    }

    public function comments()
    {
        return $this->hasMany(blogComment::class);
    }
}