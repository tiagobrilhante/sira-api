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
    return '<h1>API CmaEmNumeros</h1> <b>Framework da Api:</b> ' . $router->app->version() . '<br> <b>Versão da api:</b> 1.4<br><b>Desenvolvedor: </b> TC Brilhante <br>Todos os Direitos dessa API pertencem ao Exército Brasileiro. <br> Todo o poder emana do código.';
});

$router->post('/api/login', 'TokenController@gerarToken');


// autenticado ...
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function () use ($router) {

    // USUARIOS
    $router->group(['prefix' => 'users'], function () use ($router) {
        $router->get('', 'UserController@index');
        $router->post('cpf/', 'UserController@cpfExist');
        $router->post('/password/reset', 'UserController@alteraSenhaResetada');
        $router->post('/password/change', 'UserController@alteraSenhaNormal');
        $router->post('', 'UserController@createUser');
        $router->post('/checacpf/{id}', 'UserController@checaCPFExist');
        $router->post('/reset', 'UserController@resetaSenha');
        $router->put('{id}', 'UserController@update');
        $router->delete('{id}', 'UserController@destroy');
        $router->post('checasenha/', 'UserController@checarSenha');
    });

    // POsto Grad
    $router->group(['prefix' => 'pg'], function () use ($router) {
        $router->get('', 'PostoGradController@index');
    });

    // Seçoes
    $router->group(['prefix' => 'secao'], function () use ($router) {
        $router->get('', 'SecaoController@index');
        $router->get('/crua', 'SecaoController@secaoCrua');
        $router->get('/simples', 'SecaoController@simples');
        $router->get('/unitaria/{id}', 'SecaoController@unitaria');
        $router->post('/pima', 'SecaoController@pegaDadosMesAno');

        $router->get('/lepais', 'SecaoController@getPais');
        $router->get('/chart/{id}', 'SecaoController@getChart');
        $router->post('', 'SecaoController@salva');
        $router->put('{id}', 'SecaoController@altera');
        $router->delete('{id}', 'SecaoController@destroy');
        $router->get('/basica', 'SecaoController@secaoBasica');
        $router->get('/impacto/{id}', 'SecaoController@checaImpacto');

    });

    // Categorias de Indicadores
    $router->group(['prefix' => 'categorias'], function () use ($router) {
        $router->get('', 'CategoriaController@index');
        $router->get('{id}', 'CategoriaController@show');
        $router->get('/porsecao/{id}', 'CategoriaController@pegaCategoriaPorSecao');
        $router->post('', 'CategoriaController@store');
        $router->put('{id}', 'CategoriaController@update');
        $router->delete('{id}', 'CategoriaController@destroy');
        $router->post('/busca', 'CategoriaController@buscaDeCategorias');
        $router->post('/checanatureza', 'CategoriaController@checaAlteracaoNatureza');

    });

    // Indicadores
    $router->group(['prefix' => 'indicadores'], function () use ($router) {
        $router->get('', 'IndicadorController@index');
        $router->get('{id}', 'IndicadorController@show');
        $router->get('/pega/{id}', 'IndicadorController@pegaPorSec');
        $router->post('', 'IndicadorController@store');
        $router->post('/secao', 'IndicadorController@porSecao');
        $router->post('/secao/refinado', 'IndicadorController@porSecaoRefinado');
        $router->put('{id}', 'IndicadorController@update');
        $router->delete('{id}', 'IndicadorController@destroy');
        $router->post('/destroiinvalidos', 'IndicadorController@destroyTodosInvalidos');

    });

    // Indicadores Valor
    $router->group(['prefix' => 'valorindicador'], function () use ($router) {
        $router->post('', 'IndicadorValorController@store');
        $router->post('/resumo', 'IndicadorValorController@retornaResumo');
        $router->post('/corrente', 'IndicadorValorController@pegaIndicadorCorrente');
        $router->get('/atualizacao/{id}', 'IndicadorValorController@retornaAtualizacao');
        $router->post('/porcat', 'IndicadorValorController@retornaResumoPorCategora');
        $router->post('/valor/alteracao', 'IndicadorValorController@retornaValoresParaAlteracao');
        $router->post('/valor/doit', 'IndicadorValorController@alteraValorFinal');

    });

    $router->group(['prefix' => 'ferramenta'], function () use ($router) {
        $router->post('/corretor', 'FerramentaAdmController@corretorIndicadores');
        $router->post('/integridade', 'FerramentaAdmController@analisaIntegridade');
        $router->delete('/excluiduplicado/{id}', 'FerramentaAdmController@removeRegistroDuplicado');
        $router->post('/relatoriopendencias', 'FerramentaAdmController@relatorioPendencias');
        $router->get('/indsemcat', 'FerramentaAdmController@pegaIndSemCat');
    });

    $router->group(['prefix' => 'obsiv'], function () use ($router) {
        $router->post('/', 'IndicadorValorObservacaoController@store');
        $router->delete('{id}', 'IndicadorValorObservacaoController@destroy');
    });

    // total mensal observacao
    $router->group(['prefix' => 'obstm'], function () use ($router) {
        $router->post('/', 'TotalMensalObservacaoController@store');
        $router->post('/pegaporcategoria', 'TotalMensalObservacaoController@pegaTodosMensalCategoria');
        $router->delete('{id}', 'TotalMensalObservacaoController@destroy');
    });

    // tela
    $router->group(['prefix' => 'tela'], function () use ($router) {
        $router->get('/pegacatind', 'TelaController@pegaCatInd');
        $router->post('/montatela', 'TelaController@montaTela');
        $router->post('/savetela', 'TelaController@saveTela');
        $router->get('/pegatelas', 'TelaController@pegaTelas');
        $router->post('/pegatelasespecifica', 'TelaController@pegaTelaEspecifica');
        $router->post('/existehash', 'TelaController@verificaExisteHash');
        $router->delete('{id}', 'TelaController@destroy');
    });

    // Dashboards
    $router->group(['prefix' => 'dashboard'], function () use ($router) {
        $router->get('/completo', 'DashboardController@verCompleto');
        $router->get('', 'DashboardController@index');
        $router->get('{id}', 'DashboardController@show');
        $router->post('', 'DashboardController@save');
        $router->delete('{id}', 'DashboardController@destroy');
        $router->post('/existehash', 'DashboardController@verificaExisteHash');
        $router->post('/savedashcapturado', 'DashboardController@SaveCapturado');
    });
});
