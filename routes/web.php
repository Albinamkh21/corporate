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




Route::resource('/', 'IndexController',
                                    [
                                        'only' => 'index' ,
                                        'names' => ['index' => 'home']
                                    ]
);
Route::resource('portfolio', 'PortfolioController', [
                                                        'parameters' => ['portfolio' => 'alias']
                                                    ]
);
Route::resource('articles', 'ArticlesController', [
        'parameters' => ['articles' => 'alias']
    ]
);
Route::get('articles/cat/{category?}', ['uses' => 'ArticlesController@index', 'as'=> 'articles_category' ])->where('category','[\w-]+' );

Route::resource('comment', 'CommentController', [
        'only' => ['store']
    ]
);
Route::match(['get', 'post'], '/contacts', ['uses' => 'ContactController@index', 'as' => 'contacts']);


Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', ['as' => 'login', 'uses' => 'Auth\LoginController@login']);
Route::get('logout', 'Auth\LoginController@logout');

//admin panel

Route::group(['prefix' => 'admin', 'middleware' => 'auth', 'as' => 'admin.'], function(){

    Route::get('/', ['uses' => 'Admin\IndexController@index', 'as' => 'adminIndex' ]);
    Route::resource('/articles',  'Admin\ArticlesController');
    Route::resource('/permissions',  'Admin\PermissionController');
    Route::resource('/menu',  'Admin\MenuController');
    Route::resource('/users',  'Admin\UsersController');

});