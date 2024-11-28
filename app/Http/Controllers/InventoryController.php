<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryRequest;
use App\Models\Inventory;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\SupplierAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = getUserCompanyId();
        $storeId = getUserStoreId();
    

        // Retrieve products based on company_id and store_id
        $data = Inventory::with('supplier')->where('company_id', $companyId)
            ->where('store_id', $storeId)
            ->orderBy('inventory_date', 'desc') // Orders by inventory_date in descending order
            ->orderBy('created_at', 'desc')       // Then, order by created_at in descending order
            ->get();
    
        // Pass the products to the view
        return view('inventory.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("inventory.create");
    }

    /**
     * Store a newly created resource in storage.
     */

     public function store(InventoryRequest $request)
{

    try {
        // Retrieve necessary IDs from helper functions or request
        $companyId = getUserCompanyId();
        $storeId = getUserStoreId();
       
        // Collect validated data for the inventory
        $inventoryData = [
            'amount' => $request->gross_value,       // Total gross value
            'supplier_id' => $request->supplier_id, // Supplier UUID
            'quantity' => $request->total_qty,      // Total quantity
            'company_id' => $request->company_id ?? $companyId,            // Company ID
            'store_id' => $request->store_id ?? $storeId, // Store ID
            'discount' => $request->total_discount,        // Total discount (optional)
            'total_price' => $request->total_net_value,    // Final price after discount
            'inventory_date' => $request->date,            // Inventory date
        ];

        // Create the inventory record
        $inventory = Inventory::create($inventoryData);

        // Collect validated data for the purchase invoice
        $invoiceData = [
            'amount' => $request->gross_value,       // Total gross value
            'supplier_id' => $request->supplier_id, // Supplier UUID
            'quantity' => $request->total_qty,      // Total quantity
            'company_id' => $request->company_id ?? $companyId,            // Company ID
            'store_id' => $request->store_id ?? $storeId, // Store ID
            'discount' => $request->total_discount,        // Total discount (optional)
            'total_price' => $request->total_net_value,    // Final price after discount
            'invoice_date' => $request->date,              // Invoice date
            'inventory_id' => $inventory->id,              // Link to inventory
        ];

        // Create the purchase invoice record
        $invoice = PurchaseInvoice::create($invoiceData);

        // Loop through the product details and create purchase invoice items
        foreach ($request->product_id as $index => $productId) {
            PurchaseInvoiceItem::create([
                'purchase_invoice_id' => $invoice->id, // Link to the purchase invoice
                'product_id' => $productId,           // Product UUID
                'quantity' => $request->qty[$index],  // Quantity
                'price' => $request->price[$index],   // Price per unit
                'discount' => $request->discount[$index] ?? 0, // Discount (if any)
                'total_price' => $request->total[$index],      // Total price for the item
                'expiry_date' => $request->expiry_date[$index]??Carbon::now(),               // Set expiry date to current date
                'uom' => $request->weight[$index], // Expiry date
       
            ]);
        }

        $accountData = [
            'supplier_id' => $request->supplier_id, // Supplier UUID
            'company_id' => $request->company_id ?? $companyId,            // Company ID
            'store_id' => $request->store_id ?? $storeId, // Store ID
            'total_price' => $request->total_net_value,    // Final price after discount
            'bill_paid_date' => $request->date,              // Invoice date
            'inventory_id' => $inventory->id,              // Link to inventory
            'paid_amount' => $request->amount_to_pay,              // Link to inventory
            'account_balance' => $request->remaining_amount,              // Link to inventory
        ];

        $supplierAccount = SupplierAccount::create($accountData);

        return response()->json([
            'status' => 'success',
            'message' => 'Inventory, invoice, and invoice items created successfully.',
            'data' => [
                'inventory' => $inventory,
                'invoice' => $invoice,
                'supplierAccount' => $supplierAccount,
            ],
        ], 201);
    } catch (\Exception $e) {
        Log::error('Error in inventory creation: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to create inventory. Please try again. ' . $e->getMessage()
        ], 500);
    }
}

     

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $companyId = getUserCompanyId();
        $storeId = getUserStoreId();

    // Use first() to get a single record instead of get()
    $data = PurchaseInvoice::with(['items','items.product','supplierAccount'])->where('inventory_id', $id)
        ->where('company_id', $companyId)
        ->where('store_id', $storeId)
        ->first(); // Retrieves a single product

    // If no product found, return 404 or an appropriate response
    if (!$data) {
        return redirect()->route('inventories.index')->with('error', 'Product not found.');
    }

    // Pass the product to the view (since $data is a single product, not a collection)
    return view('inventory.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $companyId = getUserCompanyId();
        $storeId = getUserStoreId();

    // Use first() to get a single record instead of get()
    $data = PurchaseInvoice::with(['items','items.product','supplierAccount'])->where('inventory_id', $id)
        ->where('company_id', $companyId)
        ->where('store_id', $storeId)
        ->first(); // Retrieves a single product

    // If no product found, return 404 or an appropriate response
    if (!$data) {
        return redirect()->route('inventories.index')->with('error', 'Product not found.');
    }

    // Pass the product to the view (since $data is a single product, not a collection)
    return view('inventory.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateinventory(Request $request, string $id)
{
    try {
        // Retrieve necessary IDs from helper functions or request
        $companyId = getUserCompanyId();
        $storeId = getUserStoreId();

        // Find the existing Inventory record by ID
        $inventory = Inventory::findOrFail($id);

        // Find the PurchaseInvoice that has the given inventory_id
        $purchaseInvoice = PurchaseInvoice::where('inventory_id', $id)->firstOrFail();

        // Get the purchase_invoice_id from the found PurchaseInvoice
        $purchaseInvoiceId = $purchaseInvoice->id;

        // Delete related PurchaseInvoiceItem records based on the purchase_invoice_id
        PurchaseInvoiceItem::where('purchase_invoice_id', $purchaseInvoiceId)->delete();

        $supplierAccount = SupplierAccount::where('inventory_id', $id)->first();

        if ($supplierAccount) {
            // Delete the SupplierAccount record
            $supplierAccount->delete();
        }

        // Delete the PurchaseInvoice record
        $purchaseInvoice->delete();

        // Update the existing Inventory record with validated data
        $inventory->update([
            'amount' => $request->gross_value,         // Total gross value
            'supplier_id' => $request->supplier_id,    // Supplier UUID
            'quantity' => $request->total_qty,         // Total quantity
            'company_id' => $request->company_id ?? $companyId,   // Company ID
            'store_id' => $request->store_id ?? $storeId,           // Store ID
            'discount' => $request->total_discount,    // Total discount (optional)
            'total_price' => $request->total_net_value, // Final price after discount
            'inventory_date' => $request->date,         // Inventory date
        ]);

        // Create the new PurchaseInvoice record
        $invoiceData = [
            'amount' => $request->gross_value,         // Total gross value
            'supplier_id' => $request->supplier_id,    // Supplier UUID
            'quantity' => $request->total_qty,         // Total quantity
            'company_id' => $request->company_id ?? $companyId,  // Company ID
            'store_id' => $request->store_id ?? $storeId,        // Store ID
            'discount' => $request->total_discount,    // Total discount (optional)
            'total_price' => $request->total_net_value, // Final price after discount
            'invoice_date' => $request->date,           // Invoice date
            'inventory_id' => $inventory->id,          // Link to the updated Inventory
        ];

        // Create the new PurchaseInvoice record
        $newInvoice = PurchaseInvoice::create($invoiceData);

        // Loop through the product details and create new PurchaseInvoiceItem records
        foreach ($request->product_id as $index => $productId) {
            PurchaseInvoiceItem::create([
                'purchase_invoice_id' => $newInvoice->id,  // Link to the new purchase invoice
                'product_id' => $productId,                 // Product UUID
                'quantity' => $request->qty[$index],        // Quantity
                'price' => $request->price[$index],         // Price per unit
                'discount' => $request->discount[$index] ?? 0, // Discount (if any)
                'total_price' => $request->total[$index],   // Total price for the item
                'expiry_date' => $request->expiry_date[$index] ?? Carbon::now(), // Expiry date
                'uom' => $request->weight[$index],          // Unit of measure
            ]);
        }

        // Prepare data for SupplierAccount creation
        $accountData = [
            'supplier_id' => $request->supplier_id,     // Supplier UUID
            'company_id' => $request->company_id ?? $companyId, // Company ID
            'store_id' => $request->store_id ?? $storeId, // Store ID
            'total_price' => $request->total_net_value,  // Final price after discount
            'bill_paid_date' => $request->date,          // Bill payment date
            'inventory_id' => $inventory->id,            // Link to inventory
            'paid_amount' => $request->amount_to_pay,    // Amount paid
            'account_balance' => $request->remaining_amount, // Remaining balance
        ];

        // Create the SupplierAccount record
        $account = SupplierAccount::create($accountData);

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Inventory, invoice, and invoice items updated successfully.',
            'data' => [
                'inventory' => $inventory,
                'invoice' => $newInvoice,
                'supplierAccount' => $account,
            ],
        ], 200);

    } catch (\Exception $e) {
        // Log error and return failure response
        Log::error('Error in inventory update: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to update inventory. Please try again. ' . $e->getMessage(),
        ], 500);
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    try {
        // Attempt to find the Inventory record by ID, if not found return a custom error message
        $inventory = Inventory::find($id);

        if (!$inventory) {
            return response()->json([
                'status' => 'error',
                'message' => 'Inventory not found with the provided ID.',
            ], 404);
        }

        // Find the PurchaseInvoice that has the given inventory_id
        $purchaseInvoice = PurchaseInvoice::where('inventory_id', $id)->first();

        if (!$purchaseInvoice) {
            return response()->json([
                'status' => 'error',
                'message' => 'PurchaseInvoice not found for this inventory.',
            ], 404);
        }

        // Get the purchase_invoice_id from the found PurchaseInvoice
        $purchaseInvoiceId = $purchaseInvoice->id;

        // Delete related PurchaseInvoiceItem records based on the purchase_invoice_id
        PurchaseInvoiceItem::where('purchase_invoice_id', $purchaseInvoiceId)->delete();

        // Find and delete SupplierAccount related to the inventory_id, if exists
        $supplierAccount = SupplierAccount::where('inventory_id', $id)->first();

        if ($supplierAccount) {
            $supplierAccount->delete();
        }

        // Delete the PurchaseInvoice record
        $purchaseInvoice->delete();

        // Delete the Inventory record
        $inventory->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Inventory and related records deleted successfully.',
        ], 200);

    } catch (\Exception $e) {
        // Log error and return failure response
        Log::error('Error in inventory deletion: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to delete inventory. Please try again. ' . $e->getMessage(),
        ], 500);
    }
}

}
