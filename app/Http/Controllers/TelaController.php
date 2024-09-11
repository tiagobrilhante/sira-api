<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Indicador;
use App\Models\IndicadorValor;
use App\Models\Secao;
use App\Models\Tela;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TelaController extends Controller
{

    public function pegaCatInd()
    {

        if (Auth::user()->tipo === 'Administrador' || Auth::user()->tipo === 'Auditor') {
            $categorias = Categoria::where('ativo', true)->whereHas('indicadores')->get()->load('indicadores', 'secao');
        } else {
            $categorias = Categoria::where('ativo', true)->where('secao_id', Auth::user()->secao_id)->whereHas('indicadores')->get()->load('indicadores', 'secao');
        }

        $arraySec = [];
        foreach ($categorias as $cat) {
            $arraySec[] = $cat->secao_id;
        }

        $arraySec = array_values(array_unique($arraySec));

        $secoes = Secao::whereIn('id', $arraySec)->get();

        return [$categorias, $secoes];

    }

    public function montaTela(Request $request)
    {

        /*
         * tipos de requisição (Formas de montagem da tela):
         * Mês de um ano específico
         * Intervalo de meses de um ano específico
         * Ano específico
         * Intervalo de anos
         * Intervalo de meses de um intervalo de anos
         */

        $forma = $request['forma'];
        $indicadoresArray = $request['array_indicadores'];

        if ($forma === 'Mês de um ano específico') {
            $mes = $request['mes'];
            $ano = $request['ano_verifica_inicio'];

            $indicadorValor = IndicadorValor::whereIn('indicador_id', $indicadoresArray)->where('mes', $this->retornaMes($mes))->where('ano', $ano)->get()->load('indicador.categoria.secao');

            return $indicadorValor;
        } elseif ($forma === 'Intervalo de meses de um ano específico') {
            $mesesRaw = $request['mes'];
            $meses = [];
            $ano = $request['ano_verifica_inicio'];

            for ($i = 0, $iMax = count($mesesRaw); $i < $iMax; $i++) {
                $meses[] = $this->retornaMes($mesesRaw[$i]);
            }

            $indicadorValor = IndicadorValor::whereIn('indicador_id', $indicadoresArray)->whereIn('mes', $meses)->where('ano', $ano)->get()->load('indicador.categoria.secao');

            return $indicadorValor;

        } elseif ($forma === 'Ano específico') {
            $ano = $request['ano_verifica_inicio'];

            $indicadorValor = IndicadorValor::whereIn('indicador_id', $indicadoresArray)->where('ano', $ano)->get()->load('indicador.categoria.secao');

            return $indicadorValor;

        } elseif ($forma === 'Intervalo de anos') {
            $ano_inicio = $request['ano_verifica_inicio'];
            $ano_fim = $request['ano_verifica_fim'];

            $arrayAnos = [];

            for ($i = $ano_inicio; $i <= $ano_fim; $i++) {
                $arrayAnos[] = (int)$i;
            }


            $indicadorValor = IndicadorValor::whereIn('indicador_id', $indicadoresArray)->whereIn('ano', $arrayAnos)->get()->load('indicador.categoria.secao');

            return $indicadorValor;

        } elseif ($forma === 'Intervalo de meses de um intervalo de anos') {
            $mesesRaw = $request['mes'];
            $meses = [];
            $ano_inicio = $request['ano_verifica_inicio'];
            $ano_fim = $request['ano_verifica_fim'];

            $arrayAnos = [];

            for ($i = $ano_inicio; $i <= $ano_fim; $i++) {
                $arrayAnos[] = (int)$i;
            }

            for ($i = 0, $iMax = count($mesesRaw); $i < $iMax; $i++) {
                $meses[] = $this->retornaMes($mesesRaw[$i]);
            }

            $indicadorValor = IndicadorValor::whereIn('indicador_id', $indicadoresArray)->whereIn('mes', $meses)->whereIn('ano', $arrayAnos)->get()->load('indicador.categoria.secao');

            return $indicadorValor;

        }
    }

    private function retornaMes($mes)
    {

        // Array associativo que mapeia o nome dos meses para seus respectivos números
        $meses = [
            'Janeiro' => 1,
            'Fevereiro' => 2,
            'Março' => 3,
            'Abril' => 4,
            'Maio' => 5,
            'Junho' => 6,
            'Julho' => 7,
            'Agosto' => 8,
            'Setembro' => 9,
            'Outubro' => 10,
            'Novembro' => 11,
            'Dezembro' => 12
        ];

        // Retorna o número correspondente ao mês
        // Se o mês não for encontrado no array, retorna null
        return $meses[$mes] ?? null;
    }

    public function saveTela(Request $request)
    {
        // Retrieve the request data
        $data = $request->all();

        // Create a new Tela instance
        $tela = new Tela();

        // Assign the nome and objetoPesquisa from the request to the Tela instance
        $tela->nome = $data['nome'];
        $tela->hash = $this->generateHash();
        $tela->objetoPesquisa = json_encode($data['pesquisaFeita']); // Encode pesquisaFeita as JSON

        // Optionally, assign the user_id if needed
        $tela->user_id = auth()->id();

        // Save the Tela instance to the database
        $tela->save();

        // Return a success response
        return response()->json(['message' => 'Tela salva com sucesso!', 'tela' => $tela], 201);
    }

    private function generateHash()
    {
        // Get the current Unix timestamp
        $timestamp = time();

        // Generate a random string of 5 characters
        $randomString = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5);

        // Concatenate the timestamp with the random string
        $hash = $timestamp . $randomString;

        // Return the hash
        return $hash;
    }

    public function pegaTelas()
    {
        // Retrieve all Telas from the database
        $telas = Tela::where('user_id', auth()->id())->get();

        // Return the Telas
        return $telas;

    }

    public function pegaTelaEspecifica(Request $request)
    {
        $hash = $request['hash'];

        $tela = Tela::where('hash', $hash)->first();


        if ($tela) {
            $tela->objetoPesquisa = json_decode($tela->objetoPesquisa);
        }

        /*
        * tipos de requisição (Formas de montagem da tela):
        * Mês de um ano específico
        * Intervalo de meses de um ano específico
        * Ano específico
        * Intervalo de anos
        * Intervalo de meses de um intervalo de anos
        */


        if ($tela->objetoPesquisa->forma === "Mês de um ano específico") {
            $tela->tipoGraph = 'pie';
        } else {
            $tela->tipoGraph = 'bar';
        }

        return $tela;

    }

    public function verificaExisteHash(Request $request)
    {

        $hash = $request[0];

        return Tela::where('hash', $hash)->count();

    }

    public function destroy($id)
    {

        $tela = Tela::destroy($id);

        if ($tela === 0) {

            return response()->json([
                'erro' => 'Recurso não encontrado'
            ], 404);

        } else {
            return response()->json('', 204);
        }

    }
}
