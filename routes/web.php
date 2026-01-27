<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard');

    Route::middleware('admin')->group(function () {
        Route::livewire('/estabelecimentos', 'pages::companies.index')->name('companies.index');
        Route::livewire('/estabelecimentos/{company}', 'pages::companies.show')->name('companies.show');
        Route::livewire('/estabelecimentos/{company}/agendamentos', 'pages::bookings.index')->name('companies.bookings');
    });

    Route::livewire('/clientes', 'pages::customers.index')->name('customers.index');
    Route::livewire('/clientes/cadastro', 'pages::customers.create')->name('customers.create');
    Route::livewire('/clientes/{customer}', 'pages::customers.show')->name('customers.show');

    Route::livewire('/agendamentos', 'pages::bookings.index')->name('bookings.index');
    Route::livewire('/agendamentos/{booking}', 'pages::bookings.show')->name('bookings.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
