<?php

/** @var Router $router */

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

use Laravel\Lumen\Routing\Router;

$router->get('/', function () use ($router) {
    return '<h1>API SIRA</h1> <b>Framework da Api:</b> ' . $router->app->version() . '<br> <b>Versão da api:</b> 1.4<br><b>Desenvolvedor: </b> Tiago Brilhante <br>Todos os Direitos dessa API pertencem a UniNorte. <br> Todo o poder emana do código.';
});

$router->post('/api/login', 'TokenController@gerarToken');


// autenticado ...
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {

    // USUARIOS
    $router->group(['prefix' => 'users'], function () use ($router) {
        $router->get('', 'UserController@index');
        $router->post('/password/reset', 'UserController@alteraSenhaResetada');
        $router->post('/password/change', 'UserController@alteraSenhaNormal');
        $router->post('', 'UserController@createUser');
        $router->post('/reset', 'UserController@resetaSenha');
        $router->put('{id}', 'UserController@update');
        $router->delete('{id}', 'UserController@destroy');
        $router->post('checasenha/', 'UserController@checarSenha');
    });
});
