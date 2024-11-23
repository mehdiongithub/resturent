<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('getUserCompanyId')) {
    function getUserCompanyId()
    {
        // Check if the user is logged in
        if (Auth::check()) {
            return Auth::user()->company_id; // Assuming 'company_id' exists in the users table
        }

        return null; // Return null if no user is logged in
    }
}

if (!function_exists('getUserStoreId')) {
    function getUserStoreId()
    {
        // Check if the user is logged in
        if (Auth::check()) {
            return Auth::user()->store_id; // Assuming 'store_id' exists in the users table
        }

        return null; // Return null if no user is logged in
    }
}
