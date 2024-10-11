<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'balance'];

     // العلاقة مع العميل
     public function customer()
     {
         return $this->belongsTo(Customer::class);
     }
 
     // العلاقة مع معاملات المحفظة
     public function transactions()
     {
         return $this->hasMany(WalletTransaction::class);
     }
}
