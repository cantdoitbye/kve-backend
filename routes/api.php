<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('api')->prefix('v1')->group(function () {
    
    // Category Hierarchy APIs
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/{category}', [CategoryController::class, 'show']);
        Route::get('/{category}/sub-categories', [CategoryController::class, 'getSubCategories']);
        Route::get('/{category}/hierarchy', [CategoryController::class, 'getHierarchy']);
    });
    
    Route::prefix('sub-categories')->group(function () {
        Route::get('/', [CategoryController::class, 'getSubCategoriesAll']);
        Route::get('/{subCategory}', [CategoryController::class, 'showSubCategory']);
        Route::get('/{subCategory}/segments', [CategoryController::class, 'getSegments']);
    });
    
    Route::prefix('segments')->group(function () {
        Route::get('/', [CategoryController::class, 'getSegmentsAll']);
        Route::get('/{segment}', [CategoryController::class, 'showSegment']);
        Route::get('/{segment}/sub-segments', [CategoryController::class, 'getSubSegments']);
    });
    
    Route::prefix('sub-segments')->group(function () {
        Route::get('/', [CategoryController::class, 'getSubSegmentsAll']);
        Route::get('/{subSegment}', [CategoryController::class, 'showSubSegment']);
    });
    
    // Product APIs with filtering
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{product}', [ProductController::class, 'show']);
        Route::get('/category/{category}', [ProductController::class, 'getByCategory']);
        Route::get('/sub-category/{subCategory}', [ProductController::class, 'getBySubCategory']);
        Route::get('/segment/{segment}', [ProductController::class, 'getBySegment']);
        Route::get('/sub-segment/{subSegment}', [ProductController::class, 'getBySubSegment']);
    });
    
    // Special endpoints for navbar
    Route::get('navbar/hierarchy', [CategoryController::class, 'getNavbarHierarchy']);
    Route::get('navbar/categories/{category}/children', [CategoryController::class, 'getCategoryChildren']);
});