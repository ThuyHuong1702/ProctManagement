<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationValue extends Model
{
    use HasFactory;

    protected $fillable = ['variation_id', 'label', 'value', 'position'];

    /**
     * Một giá trị biến thể thuộc về một biến thể.
     */
    public function variation()
    {
        return $this->belongsTo(Variation::class, 'variation_id', 'id');
    }

}
