<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;
    protected $fillable = ['invoice_id', 'sub_product_id', 'quantity', 'price', 'total'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function subProduct()
    {
        return $this->belongsTo(SubProduct::class, 'sub_product_id');
    }

    // إضافة علاقة للوصول إلى المنتج الرئيسي عبر SubProduct
    public function product()
    {
        return $this->hasOneThrough(Product::class, SubProduct::class, 'id', 'id', 'sub_product_id', 'product_id');
    }
}
