<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'type', 'is_global', 'position'];

    /**
     * Một biến thể có nhiều giá trị biến thể.
     */
    public function values()
    {
        return $this->hasMany(VariationValue::class, 'variation_id', 'id');
    }


    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_variations');
    }



}
