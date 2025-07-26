<?php

use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Auth;


Route::prefix('reviews')->group(function () {
    Route::post('/store', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/form',[ReviewController::class, 'showForm'] )->name('reviews.form');
    Route::get('/', [ReviewController::class, 'displayReviews']);
    Route::get('/get', [ReviewController::class, 'getReviews'])->name('reviews.get');
    Route::get('/approved/data', [ReviewController::class, 'approvedReviewsData'])->name('reviews.approvedData');
    Route::get('/approved', [ReviewController::class, 'showApprovedReviews'])->name('reviews.approvedViews');
});


Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::prefix('reviews')->group(function () {
        Route::get('/data', [ReviewController::class, 'getReviewsData'])->name('reviews.data');
        Route::get('/', [ReviewController::class, 'index'])->name('reviews.index');
        Route::post('/update/status', [ReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
        Route::put('/{id}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::get('/{id}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
   
// Route::delete('/remove-image', [ReviewController::class, 'removeImage'])->name('reviews.removeImage');


    });
});


Auth::routes(['register' => false]);


Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
