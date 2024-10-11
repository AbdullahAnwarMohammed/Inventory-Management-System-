<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProduct extends Model
{
    use HasFactory;
    protected $fillable = ['product_id', 'name', 'price', 'quantity', 'unit', 'total'];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // علاقة One-to-Many مع SubProductQuantity
    public function quantities()
    {
        return $this->hasMany(SubProductQuantity::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class,'sub_product_id');
    }
}
