<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\CheckUserRoleMiddleware;
use App\Http\Controllers\ActivityLogController;

Route::get('/', function () {
    return redirect('/tickets');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('tickets', TicketController::class)->except(['destroy']);
    
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->middleware(CheckUserRoleMiddleware::class.':admin,regular')->name('tickets.create');
    Route::post('/tickets/store', [TicketController::class, 'store'])->middleware(CheckUserRoleMiddleware::class.':admin,regular')->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{ticket}/edit', [TicketController::class, 'edit'])->middleware(CheckUserRoleMiddleware::class.':admin,agent')->name('tickets.edit');
    Route::put('/tickets/{ticket}', [TicketController::class, 'update'])->middleware(CheckUserRoleMiddleware::class.':admin,agent')->name('tickets.update');


    // Route for adding comments to tickets
    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])->name('comments.store');
    
    Route::middleware(CheckUserRoleMiddleware::class.':admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/activitylogs', [ActivityLogController::class, 'index'])->name('admin.activitylogs.index');

        Route::get('/admin/labels', [LabelController::class, 'index'])->name('admin.labels.index');
        Route::patch('/admin/labels/{label}', [LabelController::class, 'update'])->name('admin.labels.update');
        Route::post('/admin/labels', [LabelController::class, 'store'])->name('admin.labels.store');
        Route::delete('/admin/labels/{label}', [LabelController::class, 'destroy'])->name('admin.labels.destroy');

        Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
        Route::patch('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::post('/admin/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
