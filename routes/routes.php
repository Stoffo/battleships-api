<?php


use Laravel\Lumen\Routing\Router;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/** @var Router $router */
$router->get('/', function () use ($router) {
    return view('game');
});

$router->group(['prefix' => 'api', 'middleware' => 'validate_json'], function () use ($router) {
    $router->post('fire', [
        'as'         => 'fire',
        'uses'       => 'BattleshipController@fireShot',
    ]);

    $router->post('reset', [
        'as'         => 'reset',
        'uses'       => 'BattleshipController@resetGame',
    ]);

    $router->get('grids', [
        'as'         => 'grids',
        'uses'       => 'BattleshipController@getGrids',
    ]);
});
