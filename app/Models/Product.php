<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'name', 'brand_id', 'slug', 'price', 'special_price', 'special_price_type',
        'special_price_start', 'special_price_end', 'selling_price', 'sku',
        'manage_stock', 'qty', 'in_stock', 'viewed', 'is_active'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    public function variations()
    {
        return $this->belongsToMany(Variation::class, 'product_variations', 'product_id', 'variation_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

}
