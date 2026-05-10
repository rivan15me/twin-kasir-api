<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {
    protected $fillable = [
        'customer_id', 'payment_method', 'payment_status',
        'total_amount', 'amount_paid', 'change_amount'
    ];
}