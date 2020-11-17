<?php

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
Route::get('decks/{id}/historial', 'DeckController@historial')->middleware('auth')->name('historial');
Route::get('decks/{id}/inspector/{unico}', 'DeckController@inspector')->middleware('auth')->name('inspector');
Route::get('decks/{id}/rate', 'twitter\TwitterController@limite')->middleware('auth')->name('limite');

Route::get('/', 'DeckController@noticias')->middleware('auth');


Route::post('/', 'DeckController@noticiasCrear')->middleware('auth')->name('noticias');

Route::get('/puede', 'twitter\TwitterController@unrt')->middleware('auth')->name('puede');



Route::resource('decks','DeckController')->middleware('auth');
Route::post('panel.deck.nuevo/{id}','DeckController@newUser')->name('nuevouser');
Route::post('panel.deck.admin/{id}','DeckController@newAdmin')->name('nuevoadmin');

Route::get('/twitter', 'twitter\TwitterController@index')->middleware('auth')->name('twitter');
Route::get('/callback', 'twitter\TwitterController@callback')->middleware('auth');
Route::post('/RT', 'twitter\TwitterController@darRT')->name('rt')->middleware('auth'); 
Route::post('/generar', 'twitter\TwitterController@generar')->name('generar')->middleware('auth'); 
Route::post('/generar1', 'twitter\TwitterController@generar1')->name('generar1')->middleware('auth');  
Route::post('/generar3', 'twitter\TwitterController@generar3')->name('generar3')->middleware('auth');  

Route::get('/reautorizar', 'twitter\TwitterController@reautorizar')->name('reautorizar')->middleware('auth');  


Route::post('panel.deck.eliminar-user/', 'DeckController@eliminarUser')->name('eliminar-user')->middleware('auth');  


Route::get('/config-cache', 'DeckController@cache');

Route::get('/agregar', 'PermisosController@crear')->middleware('auth');

Auth::routes();

Route::get('/home', 'DeckController@noticias')->name('home')->middleware('auth');
Route::get('/hora', 'twitter\TwitterController@hora');
