<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.dashboard');
    }
    public function table()
    {
        return view('datatable.index');
    }

    public function login(Request $request)
{
    // Validate incoming data
    $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
    ]);

    // Attempt to log the user in
    $credentials = $request->only('email', 'password');

    if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
        // Authentication successful, send a success response
        return response()->json([
            'success' => true,
            'redirect' => route('dashboard')  // Return the URL to redirect to
        ]);
    } else {
        // Authentication failed, send an error response
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials, please try again.'
        ]);
    }
}


public function logout(Request $request)
{
    // Perform the logout
    Auth::logout();

    // Invalidate the session to ensure the user is fully logged out
    $request->session()->invalidate();

    // Regenerate the CSRF token
    $request->session()->regenerateToken();

    // Return a JSON response with a redirect URL to the login page
    return response()->json([
        'success' => true,
        'redirect' => route('login')  // Send the redirect URL for the login page
    ]);
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
