<?php

use Illuminate\Support\Facades\Route;
use Modules\Variation\Http\Controllers\Admin\VariationController;

Route::get('variations', [VariationController::class, 'index'])->name('admin.variations.index');

Route::get('variations/create', [VariationController::class, 'create'])->name('admin.variations.create');

Route::post('variations', [VariationController::class, 'store'])->name('admin.variations.store');

Route::get('variations/{id}', [VariationController::class, 'show'])->name('admin.variations.show');

Route::get('variations/{id}/edit', [VariationController::class, 'edit'])->name('admin.variations.edit');

Route::put('variations/{id}', [VariationController::class, 'update'])->name('admin.variations.update');

Route::delete('variations/{ids}', [VariationController::class, 'delete'])->name('admin.variations.admin.products.delete');

Route::get('variations/index/table', [VariationController::class, 'table'])->name('admin.variations.table');
