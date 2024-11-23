<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Supplier extends Model
{
    use HasFactory,SoftDeletes,HasUuids;
    protected $fillable = [
        'name',        // Add the actual fields you want to allow
        'photo',
        'phone',
        'company_id',
        'store_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function inventory()
    {
        return $this->hasMany(Inventory::class,'supplier_id');
    }
    public function invoices()
    {
        return $this->hasMany(PurchaseInvoice::class,'supplier_id');
    }
}
