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

Route::get('/', "AlgorithmController@showBest");

Route::get('/data', "TestDataController@list");

Route::post("/data/generate", "TestDataController@generate");

Route::get("/data/{id}", "TestDataController@read");

Route::get("/data/{id}/download/{column}", "TestDataController@download");


Route::get("/algorithm", function() {
    return view('algorithms');
});

Route::group(['middleware' => 'auth'], function () {

Route::get("/data/{id}/delete", "TestDataController@delete");
Route::get("/newtest", function() {
    return view('newtest');
});
Route::post("/algorithm/upload", "AlgorithmController@upload");
Route::get("/algorithm/{id}/deleteMyAlgo", "AlgorithmController@deleteMyAlgo");
    Route::get("/algorithm/{id}/delete", "AlgorithmController@delete");

});


Route::get("/algorithm/{id}", "AlgorithmController@testList");


Route::get("/algorithm/{algorithm_id}/test/{test_data_id}", "AlgorithmController@runTest");

Route::get("/algorithm/{id}/output", "AlgorithmController@downloadOutput");

Route::get("/submissions", "AlgorithmController@list");

Route::get("/data/{id}/showalgorithms", "AlgorithmController@showAlgorithms");

Route::get("/data/{testa_data_id}/showalgorithms/{algorithm_id}", "AlgorithmController@testTheUntested");

Route::get("/algorithm/{algorithm_id}/testAll", "AlgorithmController@testAll");

// Route::get('/redirect', 'Auth\LoginController@redirectToProvider');

// Route::get('/callback', 'Auth\LoginController@handleProviderCallback');



Route::get('redirect/{driver}', 'Auth\LoginController@redirectToProvider')
    ->name('login.provider')
    ->where('driver', implode('|', config('auth.socialite.drivers')));

Route::get('{driver}/callback', 'Auth\LoginController@handleProviderCallback')
->name('login.callback')
->where('driver', implode('|', config('auth.socialite.drivers')));
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/myalgorithms', 'AlgorithmController@mysubmissions');

Route::get('/posts', 'PostsController@index')->name('allPosts');

Route::post('/posts/create', 'PostsController@create')->name('createPost');

Route::get('/posts/delete/{id}', 'PostsController@destroy')->name('deletePost');
