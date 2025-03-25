<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Variation extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'variations';
    protected $fillable = ['name', 'type', 'is_global', 'position'];
     // Thêm quan hệ với VariationValue
     public function values()
     {
         return $this->hasMany(VariationValue::class, 'variation_id');
     }
}
