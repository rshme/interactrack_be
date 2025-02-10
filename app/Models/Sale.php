<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id',
        'user_id',
        'invoice_number',
        'amount',
        'tax',
        'status',
        'invoice_date',
        'due_date',
        'notes'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function getTotalAttribute()
    {
        return $this->amount + $this->tax;
    }
}
