<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationValue extends Model
{
    use HasFactory;

    protected $table = 'variation_values';
    protected $fillable = ['variation_id', 'label', 'value', 'position'];

    public function variation()
    {
        return $this->belongsTo(Variation::class, 'variation_id');
    }
}
