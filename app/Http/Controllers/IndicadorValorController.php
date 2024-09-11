<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Indicador;
use App\Models\IndicadorValor;
use App\Models\Secao;
use App\Models\TotalMensalObservacaos;
use DateTime;
use Illuminate\Http\Request;

class IndicadorValorController extends Controller
{
    public function store(Request $request)
    {

        $arrayInt = ['Pessoas', 'Quantidade (Inteiro)'];

        $categorias = $request->input('categoria');

        foreach ($categorias as $categoria) {

            $naturezaMinha = $categoria['natureza'];

            foreach ($categoria['indicadores'] as $indicadores) {

                if (count($indicadores['indicador_valor']) > 0) {

                    // no caso se o id for null eu crio, senao eu update
                    if ($indicadores['indicador_valor'][0]['id'] === null || $indicadores['indicador_valor'][0]['id'] === 'null' || $indicadores['indicador_valor'][0]['id'] === 'undefined' || $indicadores['indicador_valor'][0]['id'] === '') {
                        // tenho que ver se a categoria é dos numeros inteiros ou não
                        if (in_array($naturezaMinha, $arrayInt, true)) {
                            $meuValorInt = (int)$indicadores['indicador_valor'][0]['valor'];
                            $meuValorFloat = null;
                        } else {
                            $meuValorInt = null;
                            $meuValorFloat = (float)$indicadores['indicador_valor'][0]['valor'];
                        }

                        IndicadorValor::create([
                            'valor' => $meuValorInt,
                            'valor_float' => $meuValorFloat,
                            'mes' => (int)$this->retornaMes($indicadores['indicador_valor'][0]['mes']),
                            'ano' => (int)$indicadores['indicador_valor'][0]['ano'],
                            'indicador_id' => (int)$indicadores['indicador_valor'][0]['indicador_id'],
                            'atualizado' => $indicadores['indicador_valor'][0]['atualizado']
                        ]);
                    } else {

                        if (in_array($naturezaMinha, $arrayInt, true)) {
                            $meuValorInt = (int)$indicadores['indicador_valor'][0]['valor'];
                            $meuValorFloat = null;
                        } else {
                            $meuValorInt = null;
                            $meuValorFloat = (float)$indicadores['indicador_valor'][0]['valor'];
                        }


                        $indicadorValor = IndicadorValor::find($indicadores['indicador_valor'][0]['id']);
                        $indicadorValor->valor = $meuValorInt;
                        $indicadorValor->valor_float = $meuValorFloat;
                        $indicadorValor->atualizado = $indicadores['indicador_valor'][0]['atualizado'];
                        $indicadorValor->save();
                    }
                }
            }
        }
        $secao = Secao::find($request->input('id'))->load(['categoria.indicadores' => function ($query) use ($request) {
            $query->with(['indicadorValor' => function ($subQuery) use ($request) {
                $ano = (int)$request->input('ano');
                $mes = (int)$this->retornaMes($request->input('mes'));
                $subQuery->where('ano', $ano)->where('mes', $mes);
            }]);
        }]);

        return $secao;

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

    public function retornaResumo(Request $request)
    {
        $categorias = Categoria::where('ativo', true)->where('secao_id', $request->input('secao_id'))->get();

        $array_retorno = [];

        foreach ($categorias as $categoria) {
            $indicadores = Indicador::where('categoria_id', $categoria->id)->get();

            $array_suporte = [];

            foreach ($indicadores as $indicador) {
                $indicador_valor = IndicadorValor::where('indicador_id', $indicador->id)->where('ano', $request['ano'])->get();

                $array_suporte[] = [
                    'indicador' => $indicador,
                    'valor' => $indicador_valor
                ];
            }

            $array_retorno[] = [
                'categoria' => $categoria,
                'indicadores' => $array_suporte
            ];
        }

        return $array_retorno;
    }


    public function retornaResumoPorCategora(Request $request)
    {


        $categorias = Categoria::where('secao_id', $request->input('secao_id'))->get();


        $array_retorno = [];

        foreach ($categorias as $categoria) {
            $indicadores = Indicador::where('categoria_id', $categoria->id)->get();

            $array_suporte = [];

            foreach ($indicadores as $indicador) {
                $indicador_valor = IndicadorValor::where('indicador_id', $indicador->id)->where('ano', $request['ano'])->get()->load('indicadorValorObservacoes');

                $array_suporte[] = [
                    'indicador' => $indicador,
                    'valor' => $indicador_valor
                ];
            }

            $totalObsArray = [];

            for ($i = 0; $i < 12; $i++) {
                $totalObsArray[] = TotalMensalObservacaos::where('mes', $i + 1)
                    ->where('ano',  $request['ano'])
                    ->where('categoria_id', $categoria->id)
                    ->get();
            }


            $array_retorno[] = [
                'categoria' => $categoria,
                'totalObs' => $totalObsArray,
                'indicadores' => $array_suporte
            ];
        }

        return $array_retorno;
    }


    public function retornaValoresParaAlteracao(Request $request)
    {
        $categoria = Categoria::find($request->input('id'));
        return $categoria->load('indicadores.indicadorValor');
    }

    public function alteraValorFinal(Request $request)
    {

        $indicador = Indicador::find($request['indicador_id']);
        $categoria = Categoria::find($indicador->categoria_id);
        $indicadorValor = IndicadorValor::find($request['id']);

        /*
                - Pessoas               ### INT
                - Quantidade (Inteiro)  ### INT
                - Quantidade (Decimal)  ### FLOAT
                - Peso (Kg)             ### FLOAT
                - Peso (Ton)            ### FLOAT
                - Distância (Km)        ### FLOAT
                - Distância (Metros)    ### FLOAT
                - Tempo (Minutos)       ### FLOAT
                - Tempo (Horas)         ### FLOAT
                - Tempo (Dias)          ### FLOAT
                - Tempo (Meses)         ### FLOAT
                - Tempo (Anos)          ### FLOAT
                - Monetário (R$)        ### FLOAT
                - Porcentagem (%)       ### FLOAT
          */

        $arrayInt = ['Pessoas', 'Quantidade (Inteiro)'];
        $arrayFloat = ['Quantidade (Decimal)', 'Peso (Kg)', 'Peso (Ton)', 'Distância (Km)', 'Distância (Metros)', 'Tempo (Minutos)', 'Tempo (Horas)', 'Tempo (Dias)', 'Tempo (Meses)', 'Tempo (Anos)', 'Monetário (R$)', 'Porcentagem (%)'];

        $intOuFloat = '';

        if (in_array($categoria->natureza, $arrayInt)) {
            $intOuFloat = 'int';
            if ($request->has('tempValor')) {
                $indicadorValor->valor = (int)$request['tempValor'];
            } else {
                $indicadorValor->valor = (int)$indicadorValor->valor_float;
            }

            $indicadorValor->valor_float = null;
            $indicadorValor->save();

        } else {
            $intOuFloat = 'float';

            if ($request->has('tempValor')) {
                $indicadorValor->valor_float = (float)$request['tempValor'];
            } else {
                $indicadorValor->valor_float = (float)$indicadorValor->valor;
            }

            $indicadorValor->valor = null;

            $indicadorValor->save();
        }

        return $indicadorValor;
    }


}
