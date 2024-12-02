<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierAccountRequest;
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
    // Fetch the companyId and storeId from the helper functions
    $companyId = getUserCompanyId();
    $storeId = getUserStoreId();

    // Retrieve the supplier data along with invoices and supplier accounts
    $data = Supplier::with([
        'invoices',
        'supplier_account'
    ])
    ->where('id', $supplierId)
    ->where('company_id', $companyId)
    ->where('store_id', $storeId)
    ->first();

    // Check if the supplier data exists
    if (!$data) {
        abort(404, 'Supplier not found');
    }

    // Calculate the remaining balance
    $totalPaidAmount = $data->supplier_account->sum('paid_amount');
    $totalInvoiceAmount = $data->invoices->sum('total_price');
    $remainingBalance = $totalInvoiceAmount - $totalPaidAmount;

    // Pass the data and remainingBalance to the view
    return view('account.create', compact('data', 'remainingBalance'));
}

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierAccountRequest $request)
    {
        $validated = $request->validated();

        $data = SupplierAccount::create($validated);

        return response()->json([
            'message' => 'Trancation complete successfully',
            'supplier' => $data,
        ], 200);

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
        $companyId = getUserCompanyId();
        $storeId = getUserStoreId();

        // Use first() to get a single record instead of get()
        $data = SupplierAccount::where('id', $id)
            ->where('company_id', $companyId)
            ->where('store_id', $storeId)
            ->first(); // Retrieves a single supplier Account

            return response()->json([
                'data' => $data,
            ], 200);

      
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierAccountRequest $request, string $id)
    {
        $data = SupplierAccount::findOrFail($id);

        // Extract validated data from the request
        $validated = $request->validated();
        $data->update($validated);

    // Return a success response
    return response()->json([
        'status' => 'success',
        'message' => 'Supplier Account transction updated successfully!',
        'supplier' => $data,
    ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the Supplier by ID
            $Supplier = SupplierAccount::find($id);
    
            // Check if the Supplier exists
            if (!$Supplier) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Supplier not found.',
                ], 404);
            }
    
            // Delete the Supplier
            $Supplier->delete();
    
            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Supplier Account tansction deleted successfully.',
            ], 200);
    
        } catch (\Exception $e) {
            // Return error response if an exception occurs
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the Supplier.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
