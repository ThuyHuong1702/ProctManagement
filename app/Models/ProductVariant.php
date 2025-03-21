<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariant extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_variants';

    protected $fillable = [
        'uid', 'uids', 'product_id', 'name', 'price', 'special_price', 'special_price_type',
        'special_price_start', 'special_price_end', 'selling_price', 'sku', 'manage_stock',
        'qty', 'in_stock', 'is_default', 'is_active', 'position'
    ];

    /**
     * Một biến thể thuộc về một sản phẩm
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
