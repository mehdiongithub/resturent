<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            // Validate the supplier_id, ensure it's a valid option or 'add_new'
            'supplier_id' => 'required|uuid|exists:suppliers,id', // Ensures supplier_id is a valid UUID and exists in the suppliers table

            // Validate the date field (invoice date)
            'date' => 'required|date',

            // Validate product_id array
            'product_id' => 'required|array|min:1',
            'product_id.*' => 'required|exists:products,id', // Assuming products table and id column

            // Validate qty array
            'qty' => 'required|array|min:1',
            'qty.*' => 'required|numeric|min:1', // Ensure quantity is a positive number

            // Validate weight array
            'weight' => 'required|array|min:1',
            'weight.*' => 'required|in:kg,g,liter,ml,dozen,unit', // The options you have in your select

            // Validate price array
            'price' => 'required|array|min:1',
            'price.*' => 'required|numeric|min:0', // Ensure price is a positive number

            'expiry_date' => 'required|array|min:1',  // Ensure the array is required and has at least one element
            'expiry_date.*' => 'required|date', // Validate each date in the array individually    

            // Validation for the company_id and store_id (hidden inputs)
            'company_id' => 'required|exists:companies,id', // Assuming companies table and id column
            'store_id' => 'required|exists:stores,id', // Assuming stores table and id column
        ];
    }

    public function messages()
    {
        return [
            'supplier_id.required' => 'Please select a supplier.',
            'supplier_id.in' => 'Invalid supplier selected.',
            'date.required' => 'Please provide an invoice date.',
            'date.date' => 'The date must be a valid date format.',
            'product_id.required' => 'Please select at least one product.',
            'product_id.*.exists' => 'The selected product is invalid.',
            'qty.required' => 'Please provide a quantity for each product.',
            'qty.*.numeric' => 'The quantity must be a number.',
            'qty.*.min' => 'The quantity must be at least 1.',
            'weight.required' => 'Please select a weight unit for each product.',
            'weight.*.in' => 'The weight must be a valid unit (kg, g, liter, ml, dozen, unit).',
            'price.required' => 'Please provide a price for each product.',
            'price.*.numeric' => 'The price must be a number.',
            'price.*.min' => 'The price must be at least 0.',
            'company_id.required' => 'Company ID is required.',
            'company_id.exists' => 'The selected company is invalid.',
            'store_id.required' => 'Store ID is required.',
            'store_id.exists' => 'The selected store is invalid.',
        ];
    }
    public function validatedData()
    {
        return $this->validated();
    }


}
