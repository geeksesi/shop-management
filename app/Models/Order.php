<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'price',
        'payment_status',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_order')
            ->withPivotValue('quantity', 2);
    }

    public function product_orders()
    {
        return $this->hasMany(Product_order::class);
    }

    public static function get_product_ids(Order $order): array{
        $product_ids = [];
        foreach ($order->products as $product) {
            $product_ids[] = $product->pivot->product_id;
        }
        return $product_ids;
    }


}
