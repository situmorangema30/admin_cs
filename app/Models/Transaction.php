<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'sub_total',
        'total',
        'total_purchase',
        'additional_request',
        'payment_method',
        'status',
    ];
    protected $guarded = ['id'];

    public function transactionDetail()
    {
        return $this->hasMany(TransactionDetail::class, 'transactions_id');
    }

    public function voucherUsage()
    {
        return $this->hasMany(VoucherUsage::class, 'transactions_id');
    }
}
