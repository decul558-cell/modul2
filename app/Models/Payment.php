<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'midtrans_order_id',
        'amount',
        'status',
        'transaction_id',
        'payment_type',
        'midtrans_response',
        'paid_at',
    ];

    protected $casts = [
        'midtrans_response' => 'array',
        'paid_at'           => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
