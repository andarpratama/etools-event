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

Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [App\Http\Controllers\RobotsController::class, 'index'])->name('robots');

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
    Route::delete('/images/{imageId}', [App\Http\Controllers\Admin\ToolController::class, 'deleteImage'])->name('images.delete');
    Route::get('/data/datatable', [App\Http\Controllers\Admin\ToolController::class, 'datatable'])->name('datatable');
});

Route::prefix('admin/settings')->name('admin.settings.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('index');
    Route::put('/', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('update');
});

Route::get('/admin/storage-link', [App\Http\Controllers\Admin\StorageLinkController::class, 'create'])->name('admin.storage-link');

Route::prefix('admin/portfolios')->name('admin.portfolios.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\PortfolioController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\PortfolioController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Admin\PortfolioController::class, 'store'])->name('store');
    Route::get('/{id}', [App\Http\Controllers\Admin\PortfolioController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [App\Http\Controllers\Admin\PortfolioController::class, 'edit'])->name('edit');
    Route::put('/{id}', [App\Http\Controllers\Admin\PortfolioController::class, 'update'])->name('update');
    Route::delete('/{id}', [App\Http\Controllers\Admin\PortfolioController::class, 'destroy'])->name('destroy');
    Route::get('/data/datatable', [App\Http\Controllers\Admin\PortfolioController::class, 'datatable'])->name('datatable');
});
