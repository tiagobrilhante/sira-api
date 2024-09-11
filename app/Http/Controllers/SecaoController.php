<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Dashboard;
use App\Models\Indicador;
use App\Models\IndicadorValor;
use App\Models\PostoGrad;
use App\Models\Secao;
use App\Models\SecaoImpacto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SecaoController extends Controller
{

    //lista os usuários
    public function index()
    {
        return Secao::all()->load('categoria.indicadores.indicadorValor');
    }

    public function pegaDadosMesAno(Request $request)
    {

        $id = $request->input('secao_id');
        $mes = $request->input('mes');
        $ano = $request->input('ano');

        $secao = Secao::find($id)->load(['categoria.indicadores' => function ($query) use ($mes, $ano) {
            $query->with(['indicadorValor' => function ($subQuery) use ($mes, $ano) {
                $subQuery->where('mes', $mes)->where('ano', $ano)->first();
            }]);
        }]);

        return $secao;
    }

    public function secaoCrua()
    {
        return Secao::all()->load('categoria.indicadores');
    }

    public function simples()
    {
        //  return Secao::all();

        return Secao::whereHas('categoria')->get();
    }

    public function unitaria($id)
    {
        return Secao::find($id);
    }


    // base

    public function salva(Request $request)
    {

        $nome = $request['nome'];
        $sigla = $request['sigla'];
        $souPai = $request['soupai'];
        $pai = $request['pai'];

        if (!$souPai) {
            $secao = Secao::create([
                'nome' => $nome,
                'sigla' => $sigla,
                'secao_pai' => $pai,
            ]);
        } else {

            $secao = Secao::create([
                'nome' => $nome,
                'sigla' => $sigla,
            ]);

            $newSec = Secao::find($secao->id);
            $newSec->secao_pai = $secao->id;
            $newSec->save();

        }

        return $secao;


    }

    public function altera(int $id, Request $request)
    {


        $nome = $request['nome'];
        $sigla = $request['sigla'];
        $souPai = $request->input('soupai', false);

        $secao = Secao::find($id);
        $secao->nome = $nome;
        $secao->sigla = $sigla;


        if (!$souPai) {
            $secao->secao_pai = $request['secao_pai'];
        } else {
            $secao->secao_pai = $secao->id;
        }

        $secao->save();

        return $secao;

    }

    public function destroy($id)
    {

        // Busca a seção a ser removida
        $secao = Secao::with('filhos', 'users', 'categoria.indicadores.indicadorValor')->find($id);

        if (!$secao) {
            return response()->json([
                'erro' => 'Recurso não encontrado'
            ], 404);
        }

        // Remove os usuários vinculados à seção principal
        $secao->users()->delete();

        // Para cada seção filha, remover os usuários e seus dados associados
        foreach ($secao->filhos as $filho) {
            $filho->users()->delete();

            // Remove as categorias vinculadas à seção filha e seus dados associados
            foreach ($filho->categoria as $categoria) {
                foreach ($categoria->indicadores as $indicador) {
                    $indicador->indicadorValor()->delete(); // Remover valores do indicador
                }
                $categoria->indicadores()->delete(); // Remover indicadores da categoria
            }

            $filho->categoria()->delete(); // Remover categorias da seção filha
            $filho->delete(); // Remover a própria seção filha
        }

        // Remove as categorias, indicadores e valores vinculados à seção principal
        foreach ($secao->categoria as $categoria) {
            foreach ($categoria->indicadores as $indicador) {
                $indicador->indicadorValor()->delete(); // Remover valores do indicador
            }
            $categoria->indicadores()->delete(); // Remover indicadores da categoria
        }
        $secao->categoria()->delete(); // Remover categorias da seção principal

        // Por fim, remover a seção principal
        $secao->delete();

        return response()->json('', 204);
    }


    public function getChart($id)
    {
        $secao = Secao::find($id);


        $meus_filhos = Secao::where('secao_pai', $secao->id)->where('id', '!=', $secao->id)->get();

        $pessoas = User::where('secao_id', $secao->id)->get();

        $tempPessoas = [];
        foreach ($pessoas as $pes) {
            $tempPessoas[] = $pes->guerra;
        }

        $objectReturn = (object)[
            'id' => $secao->id,
            'title' => $secao->nome,
            'name' => $secao->sigla,
            'pessoas' => $tempPessoas,
            'children' => $this->retornaFilhos($meus_filhos)
        ];

        return response()
            ->json($objectReturn, 201);


    }

    private function retornaFilhos($filhos)
    {

        $arrayDeFilhos = [];

        foreach ($filhos as $filho) {

            $lefilhos = $filho->where('secao_pai', $filho->id)->get();

            $pessoas = User::where('secao_id', $filho->id)->get();

            $tempPessoas = [];
            foreach ($pessoas as $pes) {
                $tempPessoas[] = $pes->guerra;
            }

            $arrayDeFilhos[] = (object)[
                'id' => $filho->id,
                'title' => $filho->nome,
                'name' => $filho->sigla,
                'pessoas' => $tempPessoas,
                'children' => $this->retornaFilhos($lefilhos)
            ];
        }

        return $arrayDeFilhos;

    }

    public function getPais()
    {
        $lesecs = Secao::all();

        $array_pais = [];

        foreach ($lesecs as $sec) {
            if ($sec->id === $sec->secao_pai) {
                array_push($array_pais, $sec);
            }
        }

        return $array_pais;

    }

    public function secaoBasica()
    {
        return Secao::all();
    }


    public function checaImpacto($id)
    {
        $secao = Secao::find($id);

        // quantos filhos tem a seção?

        $secaoFilhos = Secao::where('secao_pai', $secao->id)->get();

        // quantos usuários estão vinculados a essa seção?

        $users = User::where('secao_id', $secao->id)->get()->load('posto_grad');

        // quantas categorias pertencem a essa seção?

        $categorias = Categoria::where('secao_id', $secao->id)->get();


        $totalCategorias = $categorias->count();


        // quantos indicadores estão vinculados a essa categoria?

        $indicadores = Indicador::whereIn('categoria_id', $categorias->pluck('id'))->get();


        $totalIndicadores = $indicadores->count();


        // quantos valores serão impactados

        $indicadorValor = IndicadorValor::whereIn('indicador_id', $indicadores->pluck('id'))->get();
        $totalIndicadorValor = $indicadorValor->count();


        // Criando o objeto de impacto
        $impacto = new SecaoImpacto(
            $secao,
            $secaoFilhos,
            $users,
            $totalCategorias,
            $totalIndicadores,
            $totalIndicadorValor
        );


        return response()->json((array) $impacto);
    }

}
