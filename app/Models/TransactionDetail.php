<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        "transactions_id",
        'products_id',
        'qty',
        'price_satuan',
        'price_total',
        'price_purchase_satuan',
        'price_purchase_total',
    ];
    protected $guarded = ['id'];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class, 'products_id');
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transactions_id');
    }
}
