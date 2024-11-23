<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import the Storage facade


class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = getUserCompanyId();
        $storeId = getUserStoreId();
    

        // Retrieve suppliers based on company_id and store_id
        $data = Supplier::where('company_id', $companyId)
            ->where('store_id', $storeId)
            ->get();
    
        // Pass the suppliers to the view
        return view('supplier.index', compact('data'));
    }

    public function supplierData(){
        $companyId = getUserCompanyId();
        $storeId = getUserStoreId();
    

        // Retrieve suppliers based on company_id and store_id
        $data = Supplier::where('company_id', $companyId)
            ->where('store_id', $storeId)
            ->orderBy('created_at', 'desc')  // Order by created_at descending
            ->get();

        return response()->json([
            'data' => $data
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request)
    {
        $validated = $request->validated();

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filePath = $file->store('suppliers/photos', 'public'); // Store in 'storage/app/public/suppliers/photos'
            $validated['photo'] = $filePath; // Add file path to the validated data
        }

        $data = Supplier::create($validated);

        return response()->json([
            'message' => 'Supplier created successfully',
            'supplier' => $data,
        ], 200);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $companyId = getUserCompanyId();
        $storeId = getUserStoreId();

        // Use first() to get a single record instead of get()
        $data = Supplier::where('id', $id)
            ->where('company_id', $companyId)
            ->where('store_id', $storeId)
            ->first(); // Retrieves a single supplier

        // If no supplier found, return 404 or an appropriate response
        if (!$data) {
            return redirect()->route('suppliers.index')->with('error', 'Supplier not found.');
        }

        // Pass the supplier to the view (since $data is a single supplier, not a collection)
        return view('supplier.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $companyId = getUserCompanyId();
        $storeId = getUserStoreId();

        // Use first() to get a single record instead of get()
        $data = Supplier::where('id', $id)
            ->where('company_id', $companyId)
            ->where('store_id', $storeId)
            ->first(); // Retrieves a single supplier

        // If no supplier found, return 404 or an appropriate response
        if (!$data) {
            return redirect()->route('suppliers.index')->with('error', 'Supplier not found.');
        }

        // Pass the supplier to the view (since $data is a single supplier, not a collection)
        return view('supplier.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateSupplier(SupplierRequest $request, string $id)
{
    // Fetch the supplier record or throw an exception if not found
    $supplier = Supplier::findOrFail($id);

    // Extract validated data from the request
    $validated = $request->validated();

    // Check if a new photo is uploaded
    if ($request->hasFile('photo')) {
        $file = $request->file('photo');
        $filePath = $file->store('suppliers/photos', 'public'); // Store in 'storage/app/public/suppliers/photos'

        // Delete the old photo if it exists
        if ($supplier->photo && Storage::disk('public')->exists($supplier->photo)) {
            Storage::disk('public')->delete($supplier->photo);
        }


        $validated['photo'] = $filePath; // Add new file path to validated data
    }

    // Update the supplier record with validated data
    $supplier->update($validated);

    // Return a success response
    return response()->json([
        'status' => 'success',
        'message' => 'Supplier updated successfully!',
        'supplier' => $supplier,
    ]);
}

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the Supplier by ID
            $Supplier = Supplier::find($id);
    
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
                'message' => 'Supplier deleted successfully.',
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
