<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SupplierAccount extends Model
{
    use HasFactory,SoftDeletes,HasUuids;

    protected $fillable = [
        'company_id',
        'store_id',  // Nullable fields can also be fillable
        'supplier_id',
        'inventory_id',
        'account_balance',
        'paid_amount',
        'bill_paid_date',
    ];


    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function purchaseInvoices()
    {
        return $this->hasMany(PurchaseInvoice::class, 'inventory_id', 'inventory_id');
    }


}
