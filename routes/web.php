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
  return view('welcome');
});
Auth::routes();
Route::get('/home', 'HomeController@index');
Route::get('threads', 'ThreadController@index')->name('threads');
Route::get('threads/create', 'ThreadController@create');
Route::get('threads/search', 'SearchController@show');
Route::get('threads/{category}/{thread}', 'ThreadController@show');
Route::patch('threads/{category}/{thread}', 'ThreadController@update');
Route::delete('threads/{category}/{thread}', 'ThreadController@destroy');
Route::post('threads', 'ThreadController@store')->middleware('must-be-confirmed');
Route::get('threads/{category}', 'ThreadController@index');

Route::post('locked-threads/{thread}', 'LockedThreadsController@store')->name('locked-threads.store')->middleware('admin');
Route::delete('locked-threads/{thread}', 'LockedThreadsController@destroy')->name('locked-threads.destroy')->middleware('admin');

Route::get('/threads/{category}/{thread}/replies', 'ReplyController@index');
Route::post('/threads/{category}/{thread}/replies', 'ReplyController@store');
Route::post('/threads/{category}/{thread}/subscriptions', 'ThreadSubscriptionController@store')->middleware('auth');
Route::delete('/threads/{category}/{thread}/subscriptions', 'ThreadSubscriptionController@destroy')->middleware('auth');

Route::delete('/replies/{reply}', 'ReplyController@destroy')->name('replies.destroy');

Route::post('/replies/{reply}/best', 'BestRepliesController@store')->name('best-replies.store');

Route::patch('/replies/{reply}', 'ReplyController@update');
Route::post('/replies/{reply}/favorites', 'FavoriteController@store');
Route::delete('/replies/{reply}/favorites', 'FavoriteController@destroy');
Route::get('/profiles/{user}', 'ProfileController@show')->name('profile');
Route::get('/profiles/{user}/notifications', 'UserNotificationsController@index');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');

Route::get('/register/confirm', 'Auth\RegisterConfirmationController@index')->name('register.confirm');
Route::get('api/users', 'Api\UsersController@index');
Route::post('api/users/{user}/avatar', 'Api\UserAvatarController@store')->middleware('auth')->name('avatar');


