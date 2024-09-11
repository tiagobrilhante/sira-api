<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Indicador;
use App\Models\Secao;
use Illuminate\Http\Request;

class IndicadorController extends Controller
{

    //lista as categorias
    public function index()
    {
        return Indicador::all()->load('categoria.secao');
    }


    public function show($id)
    {

        $indicador = Indicador::find($id);

        if (!$indicador) {
            return response()->json(['error' => 'Indicador não encontrado'], 404);
        }

        return $indicador->load('categoria.secao');
    }

    public function pegaPorSec($id)
    {
        // aqui eu recebo a id da seção
        $secao = Secao::find($id)->load('categoria.indicadores');
        $arrayIndicadores = [];
        foreach ($secao->categoria as $categoria) {
            foreach ($categoria->indicadores as $indicador) {
                $arrayIndicadores[] = $indicador->id;
            }

        }
        return Indicador::whereIn('id', $arrayIndicadores)->get()->load('categoria.secao');

    }

    public function store(Request $request)
    {
        $indicador = Indicador::create([
            'nome' => $request['nome'],
            'categoria_id' => $request['categoria_id'],
            'tendencia' => $request['tendencia'],
            'meta' => $request['meta'],
            'objetivo' => $request['objetivo'],
            'green' => $request['green'],
            'yellow_1' => $request['yellow_1'],
            'yellow_2' => $request['yellow_2'],
            'red' => $request['red'],
            'observacoes' => $request['observacoes']
        ]);

        return $indicador->load('categoria.secao');

    }

    public function update(Request $request, $id)
    {

        $indicador = Indicador::find($id);
        if (!$indicador) {
            return response()->json(['error' => 'Indicador não encontrada'], 404);
        }

        $indicador->nome = $request->input('nome', $indicador->nome); // Use existing title if not provided
        $indicador->categoria_id = $request->input('categoria_id', $indicador->categoria_id);
        $indicador->tendencia = $request->input('tendencia', $indicador->tendencia);
        $indicador->objetivo = $request->input('objetivo', $indicador->objetivo);
        $indicador->green = $request->input('green', $indicador->green);
        $indicador->meta = $request->input('meta', $indicador->meta);
        $indicador->yellow_1 = $request->input('yellow_1', $indicador->yellow_1);
        $indicador->yellow_2 = $request->input('yellow_2', $indicador->yellow_2);
        $indicador->red = $request->input('red', $indicador->red);
        $indicador->observacoes = $request->input('observacoes', $indicador->observacoes);
        $indicador->save();

        return response()->json($indicador->load('categoria.secao'), 200);

    }

    public function destroy($id)
    {
        $indicador = Indicador::find($id);
        if ($indicador) {
            // Delete associated indicadorValor records
            $indicador->indicadorValor()->delete();
            // Delete the indicador
            $indicador->delete();
            return response()->json(['message' => 'Indicador e seus valores associados foram excluídos com sucesso!']);
        } else {
            return response()->json(['error' => 'Indicador não encontrado'], 404);
        }
    }

    public function porSecao(Request $request)
    {
        $id = (int)$request->input('secao_id');
        $mes = (int)$this->retornaMes($request->input('mes'));
        $ano = (int)$request->input('ano');

        // Carrega a seção com as categorias e indicadores filtrados por mês e ano
        $secao = Secao::find($id)->load(['categoria.indicadores' => function ($query) use ($mes, $ano) {
            $query->with(['indicadorValor' => function ($subQuery) use ($mes, $ano) {
                $subQuery->where('mes', $mes)->where('ano', $ano);
            }]);
        }]);

        return $secao;

        //return $secao->load('categoria.indicadores.indicadorValor');
    }

    public function porSecaoRefinado(Request $request)
    {
        $id = (int)$request->input('secao_id');
        $mes = (int)$this->retornaMes($request->input('mes'));
        $ano = (int)$request->input('ano');

        $secao = Secao::find($id)->load(['categoria' => function ($query) use ($mes, $ano)  {
            $query->where('ativo', true)->with(['indicadores' => function ($query) use ($mes, $ano) {
                $query->with(['indicadorValor' => function ($subQuery) use ($mes, $ano) {
                    $subQuery->where('mes', $mes)->where('ano', $ano);
                }]);
            }]);
        }]);

        return $secao;

        //return $secao->load('categoria.indicadores.indicadorValor');
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

    public function destroyTodosInvalidos(Request $request)
    {
        $indicadores = $request->input('indicadores'); // Assuming 'indicadores' is an array of IDs

        foreach ($indicadores as $indicador) {
            $leindicador = Indicador::find($indicador['id']);

            if ($leindicador) {
                // Delete associated indicadorValor records
                $leindicador->indicadorValor()->delete();
                // Delete the indicador
                $leindicador->delete();
            }
        }

        return response()->json(['message' => 'Indicadores e seus valores associados foram excluídos com sucesso!']);
    }

}
