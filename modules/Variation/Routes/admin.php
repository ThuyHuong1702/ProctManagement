<?php
 
use Illuminate\Support\Facades\Route;
use Modules\Variation\Http\Controllers\Admin\VariationController;
 
Route::get('variations', [VariationController::class, 'index'])->name('admin.variations.index');
 
Route::get('variations/create', [VariationController::class, 'create'])->name('admin.variations.create');
 
Route::post('variations', [VariationController::class, 'store'])->name('admin.variations.store');
 
Route::get('variations/{id}', [VariationController::class, 'show'])->name('admin.variations.show');
 
Route::get('variations/{id}/edit', [VariationController::class, 'edit'])->name('admin.variations.edit');
 
Route::put('variations/{id}', [VariationController::class, 'update'])->name('admin.variations.update');
 
Route::delete('variations/{ids}', [VariationController::class, 'destroy'])->name('admin.variations.destroy');
 
Route::get('variations/index/table', [VariationController::class, 'table'])->name('admin.variations.table');
 
Route::delete('variations/{id}', [VariationController::class, 'destroy'])->name('admin.variations.destroy');
Route::delete('admin/variations/bulk-delete', [VariationController::class, 'bulkDelete'])->name('admin.variations.bulkDelete');
 