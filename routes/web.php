<?php

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

Route::get('/', function () {
    return redirect("/algorithm");
});

Route::get('/data', "TestDataController@list");

Route::post("/data/generate", "TestDataController@generate");

Route::get("/data/{id}", "TestDataController@read");

Route::get("/data/{id}/download/{column}", "TestDataController@download");

Route::get("/data/{id}/delete", "TestDataController@delete");

Route::get("/algorithm", "AlgorithmController@list");

Route::post("/algorithm/upload", "AlgorithmController@upload");

Route::get("/algorithm/{id}/delete", "AlgorithmController@delete");

Route::get("/algorithm/{id}", "AlgorithmController@testList");

Route::get("/algorithm/{algorithm_id}/test/{test_data_id}", "AlgorithmController@runTest");

Route::get("/algorithm/{id}/output", "AlgorithmController@downloadOutput");
