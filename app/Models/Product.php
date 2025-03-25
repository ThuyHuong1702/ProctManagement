<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';
    protected $fillable = ['brand_id', 'name', 'description', 'short_description', 'price', 'special_price', 'special_price_type', 'special_price_start', 'special_price_end', 'selling_price', 'sku', 'manage_stock', 'qty', 'in_stock', 'is_active', 'new_from', 'new_to'];

    public function setSpecialPriceTypeAttribute($value)
    {
        $this->attributes['special_price_type'] = ($value === 'fixed') ? 1 : 2;
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }
}
