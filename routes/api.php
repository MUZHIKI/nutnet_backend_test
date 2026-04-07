<?php

use App\Http\Controllers\AlbumLookupController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->get('/albums/lookup', AlbumLookupController::class)
    ->name('api.albums.lookup');
