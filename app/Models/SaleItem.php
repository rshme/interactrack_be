<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $fillable = [
        'sale_id',
        'description',
        'quantity',
        'unit_price',
        'subtotal'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
