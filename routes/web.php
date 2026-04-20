<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $products = \App\Models\Earphone::take(5)->get();
    return view('welcome', compact('products'));
});

Route::get('/headphones', function () {
    return view('headphones');
})->name('headphones');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('headphones');
    })->name('dashboard');

    Route::get('/cart', function () {
        return view('cart');
    })->name('cart');
});
