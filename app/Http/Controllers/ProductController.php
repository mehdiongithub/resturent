<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $companyId = getUserCompanyId();
        $storeId = getUserStoreId();
    

        // Retrieve products based on company_id and store_id
        $data = Product::where('company_id', $companyId)
            ->where('store_id', $storeId)
            ->get();
    
        // Pass the products to the view
        return view('product.index', compact('data'));
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $validated = $request->validated();

        $data = Product::create($validated);

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $data,
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
    $data = Product::where('id', $id)
        ->where('company_id', $companyId)
        ->where('store_id', $storeId)
        ->first(); // Retrieves a single product

    // If no product found, return 404 or an appropriate response
    if (!$data) {
        return redirect()->route('products.index')->with('error', 'Product not found.');
    }

    // Pass the product to the view (since $data is a single product, not a collection)
    return view('product.show', compact('data'));
}


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $companyId = getUserCompanyId();
        $storeId = getUserStoreId();
    
        // Use first() to get a single record instead of get()
        $data = Product::where('id', $id)
            ->where('company_id', $companyId)
            ->where('store_id', $storeId)
            ->first(); // Retrieves a single product
    
        // If no product found, return 404 or an appropriate response
        if (!$data) {
            return redirect()->route('products.index')->with('error', 'Product not found.');
        }
    
        // Pass the product to the view (since $data is a single product, not a collection)
        return view('product.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateProduct(ProductRequest $request, string $id)
{
    // Validate the request data
    $validated = $request->validated();

    // Find the product by its ID
    $product = Product::where('id', $id)
        ->where('company_id', getUserCompanyId()) // Ensure the product belongs to the current company
        ->where('store_id', getUserStoreId()) // Ensure the product belongs to the current store
        ->first(); // Retrieve the product, or null if not found

    // Check if the product exists
    if (!$product) {
        return response()->json([
            'message' => 'Product not found.',
        ], 404); // Return a 404 if product is not found
    }

    // Update the product with validated data
    $product->update($validated);

    // Return a success response with the updated product
    return response()->json([
        'message' => 'Product updated successfully',
        'product' => $product,
    ], 200);
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    try {
        // Find the product by ID
        $product = Product::find($id);

        // Check if the product exists
        if (!$product) {
            return response()->json([
                'status' => 'error',
                'message' => 'Product not found.',
            ], 404);
        }

        // Delete the product
        $product->delete();

        // Return success response
        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully.',
        ], 200);

    } catch (\Exception $e) {
        // Return error response if an exception occurs
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred while deleting the product.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

}
