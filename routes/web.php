<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PbAPIController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
// use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Storage;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Admin Route Starts

Route::prefix('admin')->group(function(){
    Route::get('/login',[AdminController::class, 'Index'])->name('login_from');
    Route::get('/register',[AdminController::class, 'AdminRegister'])->name('admin.register');
    Route::post('/login/owner',[AdminController::class, 'Login'])->name('admin.login');
    Route::get('/dashboard',[AdminController::class, 'Dashboard'])->name('admin.dashboard')->middleware('admin');
    Route::get('/logout',[AdminController::class, 'AdminLogout'])->name('admin.logout')->middleware('admin');
    Route::post('/register/create',[AdminController::class, 'AdminRegisterCreate'])->name('admin.register.create');

    Route::get('/currency-list', [PbAPIController::class, 'CurrencyList'])->name('pb.currencyList');
    


    Route::post('token/', [PaymentController::class, 'token'])->name('pb.token');
    Route::get('createpayment/', [PaymentController::class, 'createpayment'])->name('pb.createpayment');
    Route::get('executepayment/', [PaymentController::class, 'executepayment'])->name('pb.executepayment')->middleware('cors');

    Route::resources([
        'orders' => OrderController::class,
    ]);

});

// Admin Route Ends

Route::get('/make-payment/{amount}', [PbAPIController::class, 'makePayment'])->name('pb.makePayment');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');







require __DIR__.'/auth.php';
