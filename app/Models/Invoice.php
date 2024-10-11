<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'total'];



    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            // توليد رقم فاتورة مميز يحتوي على السنة ورقم تسلسلي
            $year = Carbon::now()->year;
            $lastInvoice = Invoice::orderBy('id', 'desc')->first();
            $invoiceNumber = $lastInvoice ? intval(substr($lastInvoice->invoice_number, -4)) + 1 : 1;
            $invoice->invoice_number = $year . '-' . str_pad($invoiceNumber, 4, '0', STR_PAD_LEFT); // مثال: 2024-0001
        });
    }
}
