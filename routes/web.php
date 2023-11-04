<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Main\IndexController;
use App\Http\Controllers\Main\PlayerController;
use App\Http\Controllers\Main\PlaylistController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [IndexController::class, 'index'])->name('main.index');
Route::get('/search', [IndexController::class, 'search'])->name('main.search');
Route::get('/playlist/likes', [IndexController::class, 'favoritePlaylist'])->name('main.favoritePlaylist');
Route::get('/playlist/current', [IndexController::class, 'currentPlaylist'])->name('main.currentPlaylist');
Route::get('/playlist/{playlist}', [IndexController::class, 'playlist'])->name('main.playlist');

Route::get('/getInfo', [PlayerController::class, 'getInfo'])->name('main.getInfo');
Route::post('/getLocalInfo', [PlayerController::class, 'getLocalInfo'])->name('main.getLocalInfo');
Route::get('/getVideo/{id}', [PlayerController::class, 'getVideo'])->name('main.getVideo');

Route::post('/setCurrentPlaylist', [PlaylistController::class, 'setCurrentPlaylist'])->name('main.setCurrentPlaylist');
Route::get('/getCurrentPlaylist', [PlaylistController::class, 'getCurrentPlaylist'])->name('main.getCurrentPlaylist');
Route::post('/addPlaylist', [PlaylistController::class, 'addPlaylist'])->name('main.addPlaylist');
Route::post('/{playlist}/removePlaylist', [PlaylistController::class, 'removePlaylist'])->name('main.removePlaylist');
Route::post('/toggleFavorite', [PlaylistController::class, 'toggleFavorite'])->name('main.toggleFavorite');
Route::post('/{playlist}/addToPlaylist/{id}', [PlaylistController::class, 'addToPlaylist'])->name('main.addToPlaylist');

Auth::routes();
