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
Route::get('articles/cat/{category?}', ['uses' => 'ArticlesController@index', 'as'=> 'articles_category' ]);

Route::resource('comment', 'CommentController', [
        'only' => ['store']
    ]
);

