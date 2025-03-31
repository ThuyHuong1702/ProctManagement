<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Str;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';
    protected $fillable = ['brand_id', 'category_id', 'name', 'description', 'short_description', 'price', 'special_price', 'special_price_type', 'special_price_start', 'special_price_end', 'selling_price', 'sku', 'manage_stock', 'qty', 'in_stock', 'is_active', 'new_from', 'new_to'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->sku)) {
                $product->sku = self::generateSku($product->name);
            }
            $product->selling_price = $product->getSellingPriceAttribute();
        });

        static::updating(function ($product) {
            $product->selling_price = $product->getSellingPriceAttribute();
        });
    }

    public function setSpecialPriceTypeAttribute($value)
    {
        $this->attributes['special_price_type'] = ($value == 1) ? 1 : 2;
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

    // Phương thức tính giá bán dựa trên logic của trigger
    public function getSellingPriceAttribute()
    {
        $currentDate = Carbon::now();

        if ($this->special_price !== null &&
            $this->special_price_start <= $currentDate &&
            $this->special_price_end >= $currentDate) {

            if ($this->special_price_type == 1) { // fixed
                return max(0, $this->price - $this->special_price);
            } elseif ($this->special_price_type == 2) { // percent
                return max(0, $this->price - ($this->special_price * $this->price / 100));
            }
        }
        return $this->price;
    }

     // Hàm tạo SKU tự động (đúng 7 ký tự)
     public static function generateSku($productName)
     {
         // Lấy tối đa 3 chữ cái đầu tiên từ tên sản phẩm
         $nameAbbreviation = strtoupper(substr(Str::slug($productName, ''), 0, 3));

         // Tạo chuỗi số ngẫu nhiên 4 chữ số
         $randomNumber = mt_rand(1000, 9999);

         // Kết hợp để tạo SKU đúng 7 ký tự
         return str_pad($nameAbbreviation, 3, 'X') . $randomNumber;
     }
}
