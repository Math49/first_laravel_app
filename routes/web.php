<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ReservationController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HotelController::class, 'welcome'])->name('index');



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/hotels', [HotelController::class, 'index'])->name('hotels');
    Route::get('/hotels/{id}', [HotelController::class, 'show'])->name('hotels.show');
    Route::post('/hotels', [HotelController::class, 'store'])->name('hotels.store');
    Route::put('/hotels/{id}/update', [HotelController::class, 'update'])->name('hotels.update');
    Route::delete('/hotels', [HotelController::class, 'destroy'])->name('hotels.destroy');

    Route::delete('/hotels/{id}', [HotelController::class, 'destroyRoom'])->name('chambres.destroy');
    Route::post('/hotels/{id}', [HotelController::class, 'addRoom'])->name('addRoom');


    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations');
    Route::put('/reservations', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::post('/reservations/{id}', [ReservationController::class, 'store'])->name('reservations.store');
    Route::delete('/reservations', [ReservationController::class, 'destroy'])->name('reservations.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
