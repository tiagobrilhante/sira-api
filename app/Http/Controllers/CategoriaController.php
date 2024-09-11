<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoriaController extends Controller
{

    //lista as categorias
    public function index()
    {
        return Categoria::all()->load('secao');
    }


    public function show($id)
    {

        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json(['error' => 'Categoria não encontrada'], 404);
        }

        return $categoria->load('secao');
    }

    public function pegaCategoriaPorSecao($id)
    {

        $categoria = Categoria::where('secao_id', $id)->get();

        if (!$categoria) {
            return response()->json(['error' => 'Categoria não encontrada'], 404);
        }

        return $categoria->load('secao');
    }

    public function store(Request $request)
    {
        $categoria = Categoria::create([
            'nome' => $request['nome'],
            'secao_id' => $request['secao_id'],
            'natureza' => $request['natureza'],
            'periodicidade' => $request['periodicidade'],
            'mapeamento_total_anual' => $request['mapeamento_total_anual'],
            'mapeamento_total_mensal' => $request['mapeamento_total_mensal'],
            'observacoes' => $request['observacoes'],
            'ativo' => $request['ativo']
        ]);

        return $categoria->load('secao');

    }

    public function update(Request $request, $id)
    {

        $categoria = Categoria::find($id);
        if (!$categoria) {
            return response()->json(['error' => 'Categoria não encontrada'], 404);
        }

        $categoria->nome = $request->input('nome', $categoria->nome);
        $categoria->secao_id = $request->input('secao_id', $categoria->secao_id);
        $categoria->natureza = $request->input('natureza', $categoria->natureza);
        $categoria->periodicidade = $request->input('periodicidade', $categoria->periodicidade);
        $categoria->mapeamento_total_anual = $request->input('mapeamento_total_anual', $categoria->mapeamento_total_anual);
        $categoria->mapeamento_total_mensal = $request->input('mapeamento_total_mensal', $categoria->mapeamento_total_mensal);
        $categoria->ativo = $request->input('ativo', $categoria->ativo);
        $categoria->observacoes = $request->input('observacoes', $categoria->observacoes);

        $categoria->save();

        return response()->json($categoria->load('secao'), 200);

    }


    public function destroy($id)
    {
        $categoria = Categoria::find($id);
        if ($categoria) {
            $categoria->delete();
            return response()->json(['message' => 'Categoria excluída com sucesso!']);
        } else {
            return response()->json(['error' => 'Categoria não encontrada'], 404);
        }
    }

    public function buscaDeCategorias(Request $request)
    {

        $palavra = $request['busca'];
        $ano = $request['ano'];

        if (Auth::user()->tipo === 'Administrador' || Auth::user()->tipo === 'Auditor') {
            $categorias = Categoria::where('nome', 'like', '%' . $palavra . '%')->where('ativo', true)->whereHas('indicadores')->get();
        } else {
            $categorias = Categoria::where('nome', 'like', '%' . $palavra . '%')->where('ativo', true)->where('secao_id', Auth::user()->secao_id)->whereHas('indicadores')->get();
        }


        $categorias->load(['indicadores.indicadorValor' => function ($query) use ($ano) {
            $query->where('ano', $ano);
        }]);

        return $categorias->load('secao');

    }

    public function checaAlteracaoNatureza(Request $request)
    {
        $categoria_editada = Categoria::find($request['id']);
        return [$categoria_editada->natureza !== $request['nova_natureza'], $categoria_editada->natureza, $request['nova_natureza']];

    }

}
