<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SiteSettingsController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\API\FooterSectionController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\BlogCategoryController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\EstimatorLeadController;
use App\Http\Controllers\Api\HandoverSnapshotController;
use App\Http\Controllers\Api\VideoController;
use App\Http\Controllers\Api\TeamMemberController;
use App\Http\Controllers\Api\CareerOpeningController;
use App\Http\Controllers\Api\HomeHeroSlideController;
use App\Http\Controllers\Api\HomeIdentityController;
use App\Http\Controllers\Api\ProcessStepController;
use App\Http\Controllers\Api\ClientReviewController;
use App\Http\Controllers\Api\DesignPhilosophyController;
use App\Http\Controllers\Api\FormFieldController;
use App\Http\Controllers\Api\ConsultationRequestController;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\AddonController;
use App\Http\Controllers\Api\EstimatorSettingController;
use App\Http\Controllers\Api\SystemSettingsController;
use Illuminate\Support\Facades\Route;

// Public Category & Site Info Routes
Route::get('/categories', [CategoryController::class, 'apiIndex']);
Route::get('/site-info', [SiteSettingsController::class, 'index']);

// Public Service Routes
Route::get('/services', [ServiceController::class, 'index']);
Route::get('/services/{id}', [ServiceController::class, 'show']);

// Public Project Routes
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{slug}', [ProjectController::class, 'show']);

// Public Portfolio Routes
Route::get('/portfolios', [PortfolioController::class, 'index']);
Route::get('/portfolios/{slug}', [PortfolioController::class, 'show']);

// Public Blog Routes
Route::get('/blogs', [BlogController::class, 'index']);
Route::get('/recent-blogs', [BlogController::class, 'recentBlogs']);
Route::get('/category-blogs/{slug}', [BlogController::class, 'categoryBlogs']);
Route::get('/blogs/{slug}', [BlogController::class, 'show']);
Route::post('/blogs/{id}/react', [BlogController::class, 'react']);
Route::post('/blogs/{id}/view', [BlogController::class, 'incrementView']);
Route::get('/blog-categories', [BlogCategoryController::class, 'index']);

// Public Comment Routes
Route::post('/comments', [CommentController::class, 'store']); // Public submit
Route::put('/comments/{id}', [CommentController::class, 'update']); // Public edit
Route::delete('/comments/{id}', [CommentController::class, 'destroy']); // Public delete

// Public Footer Routes
Route::get('/footer', [FooterSectionController::class, 'index']);
Route::get('/footer/{id}', [FooterSectionController::class, 'show']);

// Estimator Public Routes
Route::get('estimator/config', [EstimatorLeadController::class, 'config']);
Route::post('estimator/send-otp', [EstimatorLeadController::class, 'sendOtp']);
Route::post('estimator/estimate', [EstimatorLeadController::class, 'store']);
Route::get('estimator/download-pdf/{id}', [EstimatorLeadController::class, 'downloadPdf']);
Route::post('estimator/consultation-request/{id}', [EstimatorLeadController::class, 'requestConsultation']);

// Form Fields Public
Route::get('form-fields/active', [FormFieldController::class, 'activeFields']);

// Consultation Public Submit
Route::post('consultation-requests', [ConsultationRequestController::class, 'store']);

// Public Homepage Content Endpoints
Route::get('/home-hero-slides', [HomeHeroSlideController::class, 'index']);
Route::get('/home-identity', [HomeIdentityController::class, 'index']);
Route::get('/process-steps', [ProcessStepController::class, 'index']);
Route::get('/client-reviews', [ClientReviewController::class, 'index']);
Route::get('/design-philosophies', [DesignPhilosophyController::class, 'index']);
Route::get('/team-members', [TeamMemberController::class, 'index']);
Route::get('/career-openings', [CareerOpeningController::class, 'index']);
Route::get('/videos', [VideoController::class, 'index']);
Route::get('/handover-snapshots', [HandoverSnapshotController::class, 'index']);


// Authentication & Rate-limited Public Entrance Routes
Route::middleware(['throttle:login'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
    Route::post('/resend-verification', [AuthController::class, 'resendVerification']);
});

Route::post('/reset-password', [AuthController::class, 'resetPassword']);


// Secure Administrative Routes
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Auth profile endpoints
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    // Admin Dashboard
    Route::get('/admin/dashboard-stats', [DashboardController::class, 'index'])->middleware('permission:dashboard.view');

    // Site Settings Setup
    Route::post('/site-info', [SiteSettingsController::class, 'update'])->middleware('permission:settings.edit');
    Route::post('footer', [FooterSectionController::class, 'store'])->middleware('permission:settings.edit');
    Route::put('footer/{id}', [FooterSectionController::class, 'update'])->middleware('permission:settings.edit');
    Route::delete('footer/{id}', [FooterSectionController::class, 'destroy'])->middleware('permission:settings.edit');

    // System Settings & Security Setup
    Route::prefix('admin/settings')->group(function () {
        Route::get('/mail', [SystemSettingsController::class, 'getMailSettings'])->middleware('permission:settings.view');
        Route::post('/mail', [SystemSettingsController::class, 'updateMailSettings'])->middleware('permission:settings.edit');
        Route::get('/sms', [SystemSettingsController::class, 'getSmsSettings'])->middleware('permission:settings.view');
        Route::get('/sms/history', [SystemSettingsController::class, 'getSmsHistory'])->middleware('permission:settings.view');
        Route::post('/sms', [SystemSettingsController::class, 'updateSmsSettings'])->middleware('permission:settings.edit');
        Route::post('/sms/test', [SystemSettingsController::class, 'sendTestSms'])->middleware('permission:settings.edit');
        Route::get('/marketing', [SystemSettingsController::class, 'getMarketingSettings'])->middleware('permission:settings.view');
        Route::post('/marketing', [SystemSettingsController::class, 'updateMarketingSettings'])->middleware('permission:settings.edit');
        Route::get('/audit-logs', [SystemSettingsController::class, 'getAuditLogs'])->middleware('permission:settings.view');
        Route::get('/activity-logs', [\App\Http\Controllers\Api\ActivityLogController::class, 'index'])->middleware('permission:settings.view');
        Route::get('/security-insights', [SystemSettingsController::class, 'getSecurityInsights'])->middleware('permission:settings.security');
        Route::post('/revoke-token/{id}', [SystemSettingsController::class, 'revokeToken'])->middleware('permission:settings.security');
        Route::post('/clear-cache', [SystemSettingsController::class, 'clearCache'])->middleware('permission:settings.edit');
    });

    // User Management (Super Admin only)
    Route::prefix('admin/users')->middleware('permission:users.view')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store'])->middleware('permission:users.create');
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update'])->middleware('permission:users.edit');
        Route::delete('/{id}', [UserController::class, 'destroy'])->middleware('permission:users.delete');
        Route::post('/{id}/toggle-status', [UserController::class, 'toggleStatus'])->middleware('permission:users.toggle_status');
        Route::post('/{id}/reset-password', [UserController::class, 'resetPassword'])->middleware('permission:users.edit');
    });

    // Role & Permission Management (Super Admin only)
    Route::get('admin/roles', [RoleController::class, 'index'])->middleware('permission:roles.view');
    Route::post('admin/roles', [RoleController::class, 'store'])->middleware('permission:roles.create');
    Route::get('admin/roles/{id}', [RoleController::class, 'show'])->middleware('permission:roles.view');
    Route::put('admin/roles/{id}', [RoleController::class, 'update'])->middleware('permission:roles.edit');
    Route::delete('admin/roles/{id}', [RoleController::class, 'destroy'])->middleware('permission:roles.delete');
    Route::get('admin/permissions', [PermissionController::class, 'index'])->middleware('permission:permissions.view|roles.view');

    // Global Category Management
    Route::get('admin/categories', [CategoryController::class, 'index'])->middleware('permission:categories.view');
    Route::post('admin/categories', [CategoryController::class, 'store'])->middleware('permission:categories.create');
    Route::put('admin/categories/{id}', [CategoryController::class, 'update'])->middleware('permission:categories.edit');
    Route::delete('admin/categories/{id}', [CategoryController::class, 'destroy'])->middleware('permission:categories.delete');

    // Services Management
    Route::get('/admin-services', [ServiceController::class, 'adminIndex'])->middleware('permission:services.view');
    Route::get('/admin-services/{id}', [ServiceController::class, 'adminShow'])->middleware('permission:services.view');
    Route::post('/services', [ServiceController::class, 'store'])->middleware('permission:services.create');
    Route::match(['POST', 'PUT'], '/services/{id}', [ServiceController::class, 'update'])->middleware('permission:services.edit');
    Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->middleware('permission:services.delete');
    Route::delete('/admin-services/images/{imageId}', [ServiceController::class, 'destroyImage'])->middleware('permission:services.edit');

    // Projects Management
    Route::get('/admin-projects', [ProjectController::class, 'adminIndex'])->middleware('permission:projects.view');
    Route::get('/admin-projects/{id}', [ProjectController::class, 'adminShow'])->middleware('permission:projects.view');
    Route::post('/projects', [ProjectController::class, 'store'])->middleware('permission:projects.create');
    Route::match(['POST', 'PUT'], '/projects/{id}', [ProjectController::class, 'update'])->middleware('permission:projects.edit');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->middleware('permission:projects.delete');
    Route::delete('/projects/images/{id}', [ProjectController::class, 'deleteImage'])->middleware('permission:projects.edit');

    // Portfolios Management
    Route::get('/admin-portfolios', [PortfolioController::class, 'adminIndex'])->middleware('permission:portfolios.view');
    Route::get('/admin-portfolios/{id}', [PortfolioController::class, 'adminShow'])->middleware('permission:portfolios.view');
    Route::post('/portfolios', [PortfolioController::class, 'store'])->middleware('permission:portfolios.create');
    Route::match(['POST', 'PUT'], '/portfolios/{id}', [PortfolioController::class, 'update'])->middleware('permission:portfolios.edit');
    Route::delete('/portfolios/{id}', [PortfolioController::class, 'destroy'])->middleware('permission:portfolios.delete');
    Route::delete('/portfolios/images/{id}', [PortfolioController::class, 'deleteImage'])->middleware('permission:portfolios.edit');

    // Blogs Management
    Route::get('/admin-blogs', [BlogController::class, 'adminIndex'])->middleware('permission:blogs.view');
    Route::get('/admin-blogs/{id}', [BlogController::class, 'adminShow'])->middleware('permission:blogs.view');
    Route::post('/blogs', [BlogController::class, 'store'])->middleware('permission:blogs.create');
    Route::match(['POST', 'PUT'], '/blogs/{id}', [BlogController::class, 'update'])->middleware('permission:blogs.edit');
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy'])->middleware('permission:blogs.delete');
    Route::delete('/blogs/images/{id}', [BlogController::class, 'deleteImage'])->middleware('permission:blogs.edit');
    Route::post('/blogs/upload-image', [BlogController::class, 'uploadContentImage'])->middleware('permission:blogs.create');
    Route::post('/blog-categories', [BlogCategoryController::class, 'store'])->middleware('permission:blog_categories.create');
    Route::put('/blog-categories/{id}', [BlogCategoryController::class, 'update'])->middleware('permission:blog_categories.edit');
    Route::delete('/blog-categories/{id}', [BlogCategoryController::class, 'destroy'])->middleware('permission:blog_categories.delete');

    // Comments Moderation
    Route::get('/comments', [CommentController::class, 'index'])->middleware('permission:comments.view');
    Route::put('/comments/{id}/approve', [CommentController::class, 'approve'])->middleware('permission:comments.approve');
    Route::put('/comments/{id}/disapprove', [CommentController::class, 'disapprove'])->middleware('permission:comments.approve');
    Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->middleware('permission:comments.delete');

    // Gallery Management (Photos / Handover Snapshots)
    Route::get('handover-snapshots/{id}', [HandoverSnapshotController::class, 'show'])->middleware('permission:gallery.view');
    Route::post('handover-snapshots', [HandoverSnapshotController::class, 'store'])->middleware('permission:gallery.create');
    Route::post('handover-snapshots/{id}', [HandoverSnapshotController::class, 'update'])->middleware('permission:gallery.edit');
    Route::delete('handover-snapshots/{id}', [HandoverSnapshotController::class, 'destroy'])->middleware('permission:gallery.delete');
    
    // Gallery Management (Videos)
    Route::get('videos', [VideoController::class, 'index'])->middleware('permission:gallery.view');
    Route::get('videos/{id}', [VideoController::class, 'show'])->middleware('permission:gallery.view');
    Route::post('videos', [VideoController::class, 'store'])->middleware('permission:gallery.create');
    Route::post('videos/{id}', [VideoController::class, 'update'])->middleware('permission:gallery.edit');
    Route::delete('videos/{id}', [VideoController::class, 'destroy'])->middleware('permission:videos.delete|gallery.delete');

    // Team Members
    Route::get('team-members/{id}', [TeamMemberController::class, 'show'])->middleware('permission:team.view');
    Route::post('team-members', [TeamMemberController::class, 'store'])->middleware('permission:team.create');
    Route::post('team-members/{id}', [TeamMemberController::class, 'update'])->middleware('permission:team.edit');
    Route::delete('team-members/{id}', [TeamMemberController::class, 'destroy'])->middleware('permission:team.delete');

    // Career openings
    Route::get('career-openings/{id}', [CareerOpeningController::class, 'show'])->middleware('permission:careers.view');
    Route::post('career-openings', [CareerOpeningController::class, 'store'])->middleware('permission:careers.create');
    Route::put('career-openings/{id}', [CareerOpeningController::class, 'update'])->middleware('permission:careers.edit');
    Route::delete('career-openings/{id}', [CareerOpeningController::class, 'destroy'])->middleware('permission:careers.delete');

    // Homepage Setup Components
    Route::apiResource('home-hero-slides', HomeHeroSlideController::class)->except(['index', 'update'])->middleware('permission:homepage.manage');
    Route::post('home-hero-slides/{id}', [HomeHeroSlideController::class, 'update'])->middleware('permission:homepage.manage');
    Route::post('home-identity', [HomeIdentityController::class, 'update'])->middleware('permission:homepage.manage');
    Route::apiResource('process-steps', ProcessStepController::class)->except(['index', 'update'])->middleware('permission:homepage.manage');
    Route::post('process-steps/{id}', [ProcessStepController::class, 'update'])->middleware('permission:homepage.manage');
    Route::apiResource('client-reviews', ClientReviewController::class)->except(['index', 'update'])->middleware('permission:homepage.manage');
    Route::post('client-reviews/{id}', [ClientReviewController::class, 'update'])->middleware('permission:homepage.manage');
    Route::apiResource('design-philosophies', DesignPhilosophyController::class)->except(['index', 'update'])->middleware('permission:homepage.manage');
    Route::post('design-philosophies/{id}', [DesignPhilosophyController::class, 'update'])->middleware('permission:homepage.manage');

    // Consultations Intake
    Route::get('consultation-requests', [ConsultationRequestController::class, 'index'])->middleware('permission:consultations.view');
    Route::get('consultation-requests/{id}', [ConsultationRequestController::class, 'show'])->middleware('permission:consultations.view');
    Route::put('consultation-requests/{id}', [ConsultationRequestController::class, 'update'])->middleware('permission:consultations.view');
    Route::delete('consultation-requests/{id}', [ConsultationRequestController::class, 'destroy'])->middleware('permission:consultations.delete');

    // Consultations Admin Aliases
    Route::get('admin/consultations', [ConsultationRequestController::class, 'index'])->middleware('permission:consultations.view');
    Route::get('admin/consultations/{id}', [ConsultationRequestController::class, 'show'])->middleware('permission:consultations.view');
    Route::put('admin/consultations/{id}', [ConsultationRequestController::class, 'update'])->middleware('permission:consultations.view');
    Route::delete('admin/consultations/{id}', [ConsultationRequestController::class, 'destroy'])->middleware('permission:consultations.delete');
    
    // Custom Form Fields Management
    Route::apiResource('admin/form-fields', FormFieldController::class)->middleware('permission:form_fields.manage');

    // Estimator Settings & Logs Management
    Route::apiResource('admin/estimator/packages', PackageController::class)->middleware('permission:estimator.settings.manage');
    Route::apiResource('admin/estimator/rooms', RoomController::class)->middleware('permission:estimator.settings.manage');
    Route::apiResource('admin/estimator/addons', AddonController::class)->middleware('permission:estimator.settings.manage');
    Route::get('admin/estimator/settings', [EstimatorSettingController::class, 'show'])->middleware('permission:estimator.settings.manage');
    Route::post('admin/estimator/settings', [EstimatorSettingController::class, 'update'])->middleware('permission:estimator.settings.manage');
    
    Route::get('admin/estimator/leads', [EstimatorLeadController::class, 'index'])->middleware('permission:estimator.leads.view');
    Route::get('admin/estimator/leads/{id}', [EstimatorLeadController::class, 'show'])->middleware('permission:estimator.leads.view');
    Route::delete('admin/estimator/leads/{id}', [EstimatorLeadController::class, 'destroy'])->middleware('permission:estimator.leads.delete');
    Route::get('admin/estimator/reports', [EstimatorLeadController::class, 'reports'])->middleware('permission:estimator.leads.view');
});