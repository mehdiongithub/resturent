<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PurchaseInvoiceItem extends Model
{
    use HasFactory,SoftDeletes,HasUuids;

    protected $fillable = [
        'purchase_invoice_id',        // Add the actual fields you want to allow
        'product_id',
        'quantity',
        'price',
        'discount',
        'total_price',
        'expiry_date',
    ];


    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


}
