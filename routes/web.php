<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('user-view.app');
});

Route::get('/dashboard', function () {
    return view('admin-view.dashboard');
})->name('dashboard');

Route::prefix('admin/tools')->name('admin.tools.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\ToolController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\ToolController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Admin\ToolController::class, 'store'])->name('store');
    Route::get('/{id}', [App\Http\Controllers\Admin\ToolController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [App\Http\Controllers\Admin\ToolController::class, 'edit'])->name('edit');
    Route::put('/{id}', [App\Http\Controllers\Admin\ToolController::class, 'update'])->name('update');
    Route::delete('/{id}', [App\Http\Controllers\Admin\ToolController::class, 'destroy'])->name('destroy');
    Route::post('/images/{imageId}/set-primary', [App\Http\Controllers\Admin\ToolController::class, 'setPrimaryImage'])->name('images.set-primary');
    Route::get('/data/datatable', [App\Http\Controllers\Admin\ToolController::class, 'datatable'])->name('datatable');
});

Route::prefix('admin/settings')->name('admin.settings.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('index');
    Route::put('/', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('update');
});
