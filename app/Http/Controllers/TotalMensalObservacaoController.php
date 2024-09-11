<?php

namespace App\Http\Controllers;

use App\Models\IndicadorValor;
use App\Models\IndicadorValorObservacaos;
use App\Models\TotalMensalObservacaos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TotalMensalObservacaoController extends Controller
{

    public function store(Request $request)
    {

        $totalMensalObservacao = TotalMensalObservacaos::create([
            'observacao' => $request['observacao'],
            'resp' => Auth::user()->posto_grad->pg . ' ' . Auth::user()->nome_guerra,
            'categoria_id' => $request['categoria_id'],
            'mes'=> $request['mes'],
            'ano'=> $request['ano'],
            'user_id' => Auth::user()->id
        ]);

        return $totalMensalObservacao;

    }

    public function pegaTodosMensalCategoria(Request $request)
    {

        $ano = $request['ano'];
        $categoria_id = $request['categoria_id'];


        $totalArray = [];

        for ($i = 0; $i < 12; $i++) {
            $totalArray[] = TotalMensalObservacaos::where('mes', $i + 1)
                ->where('ano', $ano)
                ->where('categoria_id', $categoria_id)
                ->get();
        }

        return $totalArray;


    }

    public function destroy($id)
    {

        $obstm = TotalMensalObservacaos::destroy($id);

        if ($obstm === 0) {

            return response()->json([
                'erro' => 'Recurso não encontrado'
            ], 404);

        } else {
            return response()->json('', 204);
        }

    }

}
