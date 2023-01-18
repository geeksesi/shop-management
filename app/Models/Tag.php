<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;
    protected $guarded=['name','meta_description','slug'];
    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }
}
