<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProductQuantity extends Model
{
    use HasFactory;

    protected $fillable = ['sub_product_id', 'quantity','price','total'];

    public function subProduct()
    {
        return $this->belongsTo(SubProduct::class);
    }

    
}
