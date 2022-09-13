<?php

namespace App\Models;

use App\Models\Voucher;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VoucherUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        "transactions_id",
        "vouchers_id",
        "discounted_value",
    ];
    protected $guarded = ['id'];

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'vouchers_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transactions_id');
    }
}
