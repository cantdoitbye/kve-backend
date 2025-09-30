<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\SegmentController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\SubSegmentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // return view('dashboard');
        return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Sub Categories
    Route::resource('sub-categories', SubCategoryController::class);
    
    // Segments
    Route::resource('segments', SegmentController::class);
    
    // Sub Segments
    Route::resource('sub-segments', SubSegmentController::class);
    
    // Products
    Route::resource('products', ProductController::class);
    Route::get('products/sub-categories/{category}', [ProductController::class, 'getSubCategories']);
    Route::get('products/segments/{subCategory}', [ProductController::class, 'getSegments']);
    Route::get('products/sub-segments/{segment}', [ProductController::class, 'getSubSegments']);
    
    // Product Images Management
    Route::prefix('products/{product}/images')->name('products.images.')->group(function () {
        Route::get('/', [ProductImageController::class, 'index'])->name('index');
        Route::post('/', [ProductImageController::class, 'store'])->name('store');
        Route::post('/update-order', [ProductImageController::class, 'updateOrder'])->name('update-order');
    });
    
    // Individual image operations
    Route::prefix('product-images')->name('product-images.')->group(function () {
        Route::delete('/{productImage}', [ProductImageController::class, 'destroy'])->name('destroy');
        Route::post('/{productImage}/set-primary', [ProductImageController::class, 'setPrimary'])->name('set-primary');
    });
});

require __DIR__.'/auth.php';
