<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', function (Request $request) {
    $validated = $request -> validate([
        'email' => 'required|string|email',
        'password' => 'required|string'
    ]); // 422
    if (Auth::attempt($validated)){
        $user = User::where('email', $validated['email']) -> first();
        $token = $user -> createToken('loginToken');
        return response()->json([ 'token' => $token -> plainTextToken ]);
    } else {
        return response()->json([ 'message' => 'Nem.' ], 401);
    }
});
