<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PurchaseInvoice extends Model
{
    use HasFactory,SoftDeletes,HasUuids;

    protected $fillable = [
        'company_id',
        'store_id',
        'inventory_id',
        'supplier_id',
        'quantity',
        'amount',  
        'discount',
        'total_price',
        'invoice_date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function items()
    {
        return $this->hasMany(PurchaseInvoiceItem::class, 'purchase_invoice_id');
    }

    public function supplierAccount()
    {
        return $this->belongsTo(SupplierAccount::class, 'inventory_id', 'inventory_id');
    }


}
