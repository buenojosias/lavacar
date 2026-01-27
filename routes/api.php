<?php

use App\Models\Customer;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/customers', function (Request $request) {
    $search = $request->get('search');
    $company_id = intval($request->get('company_id'));
    return Customer::query()
        ->withoutGlobalScopes()
        ->where('company_id', $company_id)
        ->when($search, fn(Builder $query) => $query->where('name', 'like', "%{$search}%")->orWhere('whatsapp', 'like', "%{$search}%"))
        // ->unless($search, fn(Builder $query) => $query->limit(2))
        ->orderBy('name', 'asc')
        ->get()
        ->map(fn(Customer $customer): array => [
            'label' => $customer->name,
            'description' => $customer->whatsapp,
            'value' => $customer->id,
        ]);
})->name('api.customers');
