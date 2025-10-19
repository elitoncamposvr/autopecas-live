<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Users\Index as UserIndex;
use App\Livewire\Orders\Index as OrderIndex;
use App\Livewire\Orders\Report as OrderReport;
use App\Livewire\Appointments\Index as AppointmentIndex;


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
});

require __DIR__.'/auth.php';
