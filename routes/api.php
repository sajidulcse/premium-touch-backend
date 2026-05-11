<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SiteSettingsController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SocialLinkController;
use App\Http\Controllers\API\FooterSectionController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\BlogCategoryController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::apiResource('blog-categories', BlogCategoryController::class);

// Auth Routes
Route::post('/login', [AuthController::class, 'login']);
Route::get('/profile', [AuthController::class, 'profile']);
Route::post('/profile', [AuthController::class, 'updateProfile']);

Route::get('/categories', [CategoryController::class, 'apiIndex']);
Route::apiResource('admin/categories', CategoryController::class);
Route::get('/site-info', [SiteSettingsController::class, 'index']);
Route::post('/site-info', [SiteSettingsController::class, 'update']); // Admin
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);
Route::get('/admin-services', [ServiceController::class, 'adminIndex']);
Route::get('/admin-services/{id}', [ServiceController::class, 'adminShow']);
Route::delete('/admin-services/images/{imageId}', [ServiceController::class, 'destroyImage']);
Route::post('/services', [ServiceController::class, 'store']);
Route::match(['POST', 'PUT'], '/services/{id}', [ServiceController::class, 'update']);
Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
Route::get('/social-links', [SocialLinkController::class, 'index']);

// Project Routes
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{slug}', [ProjectController::class, 'show']);
Route::get('/admin-projects', [ProjectController::class, 'adminIndex']);
Route::get('/admin-projects/{id}', [ProjectController::class, 'adminShow']);
Route::post('/projects', [ProjectController::class, 'store']);
Route::match(['POST', 'PUT'], '/projects/{id}', [ProjectController::class, 'update']);
Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
Route::delete('/projects/images/{id}', [ProjectController::class, 'deleteImage']);

// Portfolio Routes
Route::get('/portfolios', [PortfolioController::class, 'index']);
Route::get('/portfolios/{slug}', [PortfolioController::class, 'show']);
Route::get('/admin-portfolios', [PortfolioController::class, 'adminIndex']);
Route::get('/admin-portfolios/{id}', [PortfolioController::class, 'adminShow']);
Route::post('/portfolios', [PortfolioController::class, 'store']);
Route::match(['POST', 'PUT'], '/portfolios/{id}', [PortfolioController::class, 'update']);
Route::delete('/portfolios/{id}', [PortfolioController::class, 'destroy']);
Route::delete('/portfolios/images/{id}', [PortfolioController::class, 'deleteImage']);

// Blog Routes
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/recent-blogs', [BlogController::class, 'recentBlogs']);
Route::get('/admin-blogs', [BlogController::class, 'adminIndex']); // New for Admin
Route::get('/admin-blogs/{id}', [BlogController::class, 'adminShow']);
Route::get('/category-blogs/{slug}', [BlogController::class, 'categoryBlogs']);
Route::get('/blogs/{slug}', [BlogController::class, 'show']);
Route::post('/blogs', [BlogController::class, 'store']); // Admin
Route::match(['POST', 'PUT'], '/blogs/{id}', [BlogController::class, 'update']); // Admin
Route::delete('/blogs/{id}', [BlogController::class, 'destroy']); // Admin
Route::delete('/blogs/images/{id}', [BlogController::class, 'deleteImage']); // Admin
Route::post('/blogs/upload-image', [BlogController::class, 'uploadContentImage']); // Admin
Route::post('/blogs/{id}/react', [BlogController::class, 'react']);
Route::post('/blogs/{id}/view', [BlogController::class, 'incrementView']);

// Comment Routes
Route::get('/comments', [CommentController::class, 'index']); // Admin
Route::post('/comments', [CommentController::class, 'store']); // Public & Admin Reply
Route::put('/comments/{id}/approve', [CommentController::class, 'approve']); // Admin
Route::put('/comments/{id}/disapprove', [CommentController::class, 'disapprove']); // Admin
Route::delete('/comments/{id}', [CommentController::class, 'destroy']); // Admin

Route::prefix('footer')->group(function () {
    Route::get('/', [FooterSectionController::class, 'index']);
    Route::get('/{id}', [FooterSectionController::class, 'show']);
    Route::post('/', [FooterSectionController::class, 'store']);
    Route::put('/{id}', [FooterSectionController::class, 'update']);
    Route::delete('/{id}', [FooterSectionController::class, 'destroy']);
});