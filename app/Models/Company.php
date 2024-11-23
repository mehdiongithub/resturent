<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;


class Company extends Model
{
    use HasFactory, SoftDeletes, HasUuids;

    protected $guarded = ['*'];

    /**
     * Get the products for the company.
     */
    public function products()
    {
        return $this->hasMany(Product::class);

    }
    public function suppliers()
    {
        return $this->hasMany(Supplier::class);

    }
    public function inventories()
    {
        return $this->hasMany(Inventory::class);

    }
    public function invoices()
    {
        return $this->hasMany(PurchaseInvoice::class);

    }



}
