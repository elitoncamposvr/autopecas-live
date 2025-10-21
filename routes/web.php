<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Users\Index as UserIndex;
use App\Livewire\Orders\Index as OrderIndex;
use App\Livewire\Orders\Report as OrderReport;
use App\Livewire\Appointments\Index as AppointmentIndex;
use App\Livewire\Quotes\Index as QuotesIndex;
use App\Livewire\Quotes\Suppliers as QuotesSuppliers;
use App\Livewire\Quotes\Purchases as QuotesPurchases;
use App\Livewire\Suppliers\Index as SuppliersIndex;




Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function() {
    Route::get('/users', UserIndex::class)->name('livewire.users.index');

    Route::get('/orders', OrderIndex::class)->name('livewire.orders.index');
    Route::get('/orders/report', OrderReport::class)->name('livewire.orders.report');

    Route::get('/appointments', AppointmentIndex::class)->name('livewire.appointments.index');

    Route::get('/quotes', QuotesIndex::class)->name('livewire.quotes.index');
    Route::get('/quotes/suppliers', QuotesSuppliers::class)->name('livewire.quotes.suppliers');
    Route::get('/quotes/purchases', QuotesPurchases::class)->name('livewire.quotes.purchases');

    Route::get('/suppliers', SuppliersIndex::class)->name('livewire.suppliers.index');
});

require __DIR__.'/auth.php';
