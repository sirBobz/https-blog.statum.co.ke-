<?php

use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BlogController::class, 'index'])->name('blog.index');
Route::get('/category/{category:slug}', [BlogController::class, 'index'])->name('blog.category');
Route::get('/tag/{tag:slug}', [BlogController::class, 'index'])->name('blog.tag');

Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/preview/{post:slug}', [BlogController::class, 'preview'])->name('blog.preview')->middleware('auth');
Route::get('/rss.xml', [BlogController::class, 'rss'])->name('blog.rss');
