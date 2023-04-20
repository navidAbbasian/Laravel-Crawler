<?php

use App\Http\Controllers\ApiContentCrawler;
use App\Http\Controllers\ContentCrawler;
use App\Http\Controllers\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get('crawl', [ContentCrawler::class, 'getCrawlerContent']);
//Route::post('crawl', [ContentCrawler::class, 'getCrawlerContent']);

Route::get('trendyol',[ApiContentCrawler::class, 'getCrawlerContent']);

