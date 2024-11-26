<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryRequest;
use App\Models\Inventory;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
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
                'expiry_date' => Carbon::now(),               // Set expiry date to current date
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Inventory, invoice, and invoice items created successfully.',
            'data' => [
                'inventory' => $inventory,
                'invoice' => $invoice,
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
    $data = PurchaseInvoice::with(['items','items.product'])->where('inventory_id', $id)
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
    $data = PurchaseInvoice::with(['items','items.product'])->where('inventory_id', $id)
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
