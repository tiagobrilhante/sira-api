<?php

namespace App\Http\Controllers;

use App\Models\IndicadorValor;
use App\Models\IndicadorValorObservacaos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndicadorValorObservacaoController extends Controller
{

    public function store(Request $request)
    {

        $indicadorValorObs = IndicadorValorObservacaos::create([
            'observacao' => $request['observacao'],
            'resp' => Auth::user()->posto_grad->pg . ' ' . Auth::user()->nome_guerra,
            'indicador_valor_id' => $request['valor_indicador']['id'],
            'user_id' => Auth::user()->id
        ]);

        $indicadorValor = IndicadorValor::find($request['valor_indicador']['id'])->load('indicadorValorObservacoes');

        return $indicadorValor;

    }

    public function destroy($id)
    {

        $obsvi = IndicadorValorObservacaos::destroy($id);

        if ($obsvi === 0) {

            return response()->json([
                'erro' => 'Recurso não encontrado'
            ], 404);

        } else {
            return response()->json('', 204);
        }

    }
}
