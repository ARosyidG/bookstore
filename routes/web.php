<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('books', [\App\Http\Controllers\BookController::class, 'index'])->name('books.index');
Route::get('top-authors', [\App\Http\Controllers\AuthorCOntroller::class, 'index'])->name('top-authors.index');
Route::get('ratings/create', [\App\Http\Controllers\RatingController::class, 'create'])->name('ratings.create');
Route::post('ratings', [\App\Http\Controllers\RatingController::class, 'store'])->name('ratings.store');
Route::get('/ajax/authors/{author}/books', [\App\Http\Controllers\RatingController::class, 'booksByAuthor'])->name('ajax.author.books');
