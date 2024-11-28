<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class SupplierAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $companyId = getUserCompanyId();
    $storeId = getUserStoreId();

    // Retrieve suppliers with the sum of total_price, sum of paid_amount, and latest bill_paid_date
    $data = Supplier::with([
        'invoices' => function ($query) {
            $query->select('supplier_id', DB::raw('SUM(total_price) as total_price_sum'))
                  ->groupBy('supplier_id');
        },
        'supplier_account' => function ($query) {
            $query->select(
                'supplier_id',
                DB::raw('SUM(paid_amount) as sum_paid_amount'),
                DB::raw('MAX(bill_paid_date) as latest_bill_paid_date')
            )->groupBy('supplier_id');
        }
    ])
    ->where('company_id', $companyId)
    ->where('store_id', $storeId)
    ->get();

    // Pass the data to the view
    return view('account.index', compact('data'));
}



    /**
     * Show the form for creating a new resource.
     */
    public function create($supplierId)
    {
        // Fetch the supplier based on the supplierId
        $companyId = getUserCompanyId();
        $storeId = getUserStoreId();

        $data = Supplier::with([
            'invoices',
            'supplier_account'
        ])
        ->where('id',$supplierId)
        ->where('company_id', $companyId)
        ->where('store_id', $storeId)
        ->first();

        if (!$data) {
            abort(404, 'Supplier not found');
        }
        
        // You can pass the supplier data to the view
        return view('account.create', compact('data'));
    
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
