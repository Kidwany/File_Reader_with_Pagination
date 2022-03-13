<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileReaderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", function () {
   return view("lines");
});

/*----------- Get log directory ---------*/
Route::get('get-log-directory', [FileReaderController::class, 'getLogDirectory']);
/*----------- Search on file ---------*/
Route::post('/file-reader', [FileReaderController::class, 'readFile']);
