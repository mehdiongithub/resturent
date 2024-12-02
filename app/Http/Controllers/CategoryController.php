<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = getUserCompanyId();
        $storeId = getUserStoreId();


        // Retrieve categorys based on company_id and store_id
        $data = Category::where('company_id', $companyId)
            ->where('store_id', $storeId)
            ->get();

        // Pass the categorys to the view
        return view('category.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();

        $data = Category::create($validated);

        return response()->json([
            'message' => 'Category created successfully',
            'data' => $data,
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
        $data = Category::where('id', $id)
            ->where('company_id', $companyId)
            ->where('store_id', $storeId)
            ->first(); // Retrieves a single category

        // If no category found, return 404 or an appropriate response
        if (!$data) {
            return redirect()->route('categories.index')->with('error', 'category not found.');
        }

        // Pass the category to the view (since $data is a single category, not a collection)
        return view('category.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $companyId = getUserCompanyId();
        $storeId = getUserStoreId();

        // Use first() to get a single record instead of get()
        $data = Category::where('id', $id)
            ->where('company_id', $companyId)
            ->where('store_id', $storeId)
            ->first(); // Retrieves a single category

        // If no category found, return 404 or an appropriate response
        if (!$data) {
            return redirect()->route('categories.index')->with('error', 'category not found.');
        }

        // Pass the category to the view (since $data is a single category, not a collection)
        return view('category.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        // Validate the request data
        $validated = $request->validated();

        // Find the category by its ID
        $category = Category::where('id', $id)
            ->where('company_id', getUserCompanyId()) // Ensure the category belongs to the current company
            ->where('store_id', getUserStoreId()) // Ensure the category belongs to the current store
            ->first(); // Retrieve the category, or null if not found

        // Check if the category exists
        if (!$category) {
            return response()->json([
                'message' => 'category not found.',
            ], 404); // Return a 404 if category is not found
        }

        // Update the category with validated data
        $category->update($validated);

        // Return a success response with the updated category
        return response()->json([
            'message' => 'category updated successfully',
            'category' => $category,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the category by ID
            $category = Category::find($id);
    
            // Check if the category exists
            if (!$category) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'category not found.',
                ], 404);
            }
    
            // Delete the category
            $category->delete();
    
            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'category deleted successfully.',
            ], 200);
    
        } catch (\Exception $e) {
            // Return error response if an exception occurs
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the category.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
