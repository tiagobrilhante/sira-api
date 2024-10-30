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
    return '<h1>API SIRA</h1> <b>Framework da Api:</b> ' . $router->app->version() . '<br> <b>Versão da api:</b> 1.0.0 a<br><b>Desenvolvedor: </b> Tiago Brilhante <br>Todos os Direitos dessa API pertencem a UniNorte. <br> Todo o poder emana do código.';
});

$router->post('/api/login', 'TokenController@gerarToken');
$router->post('/api/pesquisamatricula', 'UserController@pesquisaMatricula');
$router->get('/api/retornacursos', 'CursoController@pesquisaCursos');
$router->post('/api/autocadastro', 'UserController@autoCadastro');


// autenticado ...
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {

    // USUARIOS
    $router->group(['prefix' => 'users'], function () use ($router) {
        $router->get('', 'UserController@index');
        $router->get('/adm/{tipo}', 'UserController@indexAdm');
        $router->post('/password/reset', 'UserController@alteraSenhaResetada');
        $router->post('/password/change', 'UserController@alteraSenhaNormal');
        $router->post('', 'UserController@createUser');
        $router->post('/reset', 'UserController@resetaSenha');
        $router->put('{id}', 'UserController@update');
        $router->delete('{id}', 'UserController@destroy');
        $router->post('checasenha/', 'UserController@checarSenha');
        $router->post('updatecursousuario/', 'UserController@updateCursoUsuario');
    });


    // Unidades
    $router->group(['prefix' => 'unidades'], function () use ($router) {
        $router->get('', 'UnidadeController@index');
        $router->post('', 'UnidadeController@store');
        $router->put('{id}', 'UnidadeController@update');
        $router->get('listasimples', 'UnidadeController@listaSimples');
        $router->get('vigente', 'UnidadeController@vigente');
        $router->delete('{id}', 'UnidadeController@destroy');
    });

    // curso
    $router->group(['prefix' => 'curso'], function () use ($router) {
        $router->get('', 'CursoController@index');
        $router->delete('{id}', 'CursoController@destroy');
    });

    // turno parâmetro
    $router->group(['prefix' => 'turnoparametro'], function () use ($router) {
        $router->get('', 'TurnoParametroController@index');
    });

    // Semestre Letivo
    $router->group(['prefix' => 'semestreletivo'], function () use ($router) {
        $router->get('', 'SemestreLetivoController@index');
        $router->get('vigente', 'SemestreLetivoController@vigente');
        $router->post('vigentecurso', 'SemestreLetivoController@vigenteCurso');
        $router->get('cursolista/{id}', 'SemestreLetivoController@cursosUnidadeVigente');
        $router->get('turnos/{id}', 'SemestreLetivoController@retornaSemestreTurnos');
        $router->post('habilitaturma', 'SemestreLetivoController@habilitaTurma');
        $router->put('editaturma/{id}', 'SemestreLetivoController@editaTurma');
        $router->post('deletaturma', 'SemestreLetivoController@deletaTurma');
    });

    // curso
    $router->group(['prefix' => 'periodoturma'], function () use ($router) {
        $router->put('{id}', 'PeriodoTurmaController@update');
        $router->delete('{id}', 'PeriodoTurmaController@destroy');
    });

    // turma
    $router->group(['prefix' => 'turma'], function () use ($router) {
        $router->get('{codigo}', 'TurmaController@retornaObjetoCursoPeriodoTurnoTurma');
    });

    // Atendimento
    $router->group(['prefix' => 'atendimento'], function () use ($router) {
        $router->get('', 'UserAtendimentoController@index');
        $router->post('/pesquisa', 'UserAtendimentoController@pesquisa');
        $router->post('/', 'UserAtendimentoController@store');
        $router->post('/resolve', 'UserAtendimentoController@resolve');
        $router->get('/meusatendimentos/{tipo}', 'UserAtendimentoController@buscaAtendimentosAluno');
    });

    // relatorios
    $router->group(['prefix' => 'relatorios'], function () use ($router) {
        $router->get('/vigencias', 'RelatorioController@retornaVigencia');
        $router->get('/cursosvigentes/{vigencia}', 'RelatorioController@retornaCursosComVigencia');
        $router->post('/pegarelatoriovigencia', 'RelatorioController@geraRelatorioVigencia');
    });
});
