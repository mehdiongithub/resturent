<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierAccountRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'paid_amount' => "required",
            'company_id' => "required",
            'store_id' => "required",
            'supplier_id' => "required",
            'bill_paid_date' => "nullable",
            'inventory_id' => "nullable",
            'account_balance' => "nullable",
       
        ];
    }
}
