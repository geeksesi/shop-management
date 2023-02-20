<?php

namespace App\Models;

use App\Enums\ProductTypeEnum;
use App\Models\Traits\hasFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory,
        HasFilter;
    protected $table = 'products';
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'price',
        'type',
        'creator_id',
        'category_id',
        'thumbnail'
    ];

    protected $casts = [
        'type' => ProductTypeEnum::class
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Product::class, 'product_order')->withTimestamps();
    }

    public function product_orders(){
        return $this->hasMany(Product_order::class);
    }


}
