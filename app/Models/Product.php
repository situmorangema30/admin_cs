<?php

namespace App\Models;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'product_categories_id',
        'price',
        'purchase_price',
        'short_description',
        'description',
        'status',
        'new_product',
        'best_seller',
        'featured',
    ];
    protected $guarded = ['id'];
    public $timestamps = false;

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_categories_id');
    }
}
