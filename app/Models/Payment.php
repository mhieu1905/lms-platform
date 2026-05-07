<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['order_id', 'payment_method', 'transaction_code', 'amount', 'status', 'expires_at', 'paid_at', 'response_data'];
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
