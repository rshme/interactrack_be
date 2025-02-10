<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'customer_id', 'user_id', 'type', 'subject', 'description',
        'interaction_date', 'status', 'outcome', 'metadata'
    ];

    protected $casts = [
        'interaction_date' => 'datetime',
        'metadata' => 'array'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
