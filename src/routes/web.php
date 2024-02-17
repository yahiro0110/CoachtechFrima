<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/home', [ItemController::class, 'home'])->name('home')->middleware('auth', 'verified');;

Route::resource('items', ItemController::class)->middleware('auth', 'verified');
Route::put('/items/{item}/detail', [ItemController::class, 'updateDetail'])->name('items.detail.update')->middleware('auth', 'verified');
Route::post('/items/{item}/images', [ItemController::class, 'updateImages'])->name('items.images.update')->middleware('auth', 'verified');
Route::post('/items/{item}/attach', [ItemController::class, 'attachFavorite'])->middleware('auth', 'verified')->name('items.attach');
Route::delete('/items/{item}/detach', [ItemController::class, 'detachFavorite'])->middleware('auth', 'verified')->name('items.detach');

Route::resource('comments', CommentController::class)->middleware('auth', 'verified');

Route::resource('purchases', PurchaseController::class)->middleware('auth', 'verified');
Route::get('/purchases/create/{item}', [PurchaseController::class, 'create'])->name('purchases.create.withParam')->middleware('auth', 'verified');
Route::get('/purchases/thanks/{purchase}', [PurchaseController::class, 'thanks'])->name('thanks')->middleware('auth', 'verified');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile', [ProfileController::class, 'updateUserDetail'])->name('profile.updateUserDetail');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
