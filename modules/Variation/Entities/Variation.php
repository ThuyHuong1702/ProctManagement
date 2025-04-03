<?php

namespace Modules\Variation\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Entities\Product;

class Variation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'is_global',
        'position',
    ];

    protected $casts = [
        'is_global' => 'boolean',
        'deleted_at' => 'datetime'
    ];

    public function values(): HasMany
    {
        return $this->hasMany(VariationValue::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_variations', 'variation_id', 'product_id');
    }
}
