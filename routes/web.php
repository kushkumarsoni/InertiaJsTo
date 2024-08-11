<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('users/update/{user}',[UserController::class,'update'])->name('users.updateData');
    Route::get('users/pdf',[UserController::class,'pdf'])->name('users.pdf');
    Route::get('users/status/{user}',[UserController::class,'updateStatus'])->name('users.status');
    Route::resource('users',UserController::class)->except('update');

    Route::get('/todos',[TodoController::class,'index'])->name('todos');
    Route::get('/todos/{todo}/{edit}',[TodoController::class,'edit'])->name('todos.edit');
    Route::post('/todos',[TodoController::class,'store'])->name('todos.store');
    Route::put('/todos/{todo}/update',[TodoController::class,'update'])->name('todos.update');
    Route::delete('/todos/{todo}',[TodoController::class,'destroy'])->name('todos.destroy');
});

require __DIR__.'/auth.php';
