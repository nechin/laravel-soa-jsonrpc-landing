<?php

use App\Services\Action\ActionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('action')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/admin/activity', function (Request $request) {
        $result = (new ActionService())->show($request->get('page', 1));
        return view('activity', ['activities' => $result['result'] ?? []]);
    });

    Route::get('/{any}', function ($any) {
        return view('welcome', ['path' => $any]);
    })->where('any', '.*');
});
