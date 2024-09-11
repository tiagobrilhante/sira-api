<?php

namespace App\Http\Controllers;

use App\Models\Dashboard;
use App\Models\DashboardColuna;
use App\Models\DashboardLinha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    // retorna todos os dashboards
    public function index()
    {
        return Dashboard::where('user_id', Auth::user()->id)->get();
    }

    // salva o dash
    public function save(Request $request)
    {
        $arrayDados = $request->all();
        $firstElement = array_shift($arrayDados);
        $remainingElements = $arrayDados;

        $nome = $firstElement['nome'];
        $ativo = $firstElement['ativo'];
        $destaque = $firstElement['destaque'];

        $user = Auth::user();

        /////////////////
        //  dashboard  //
        /////////////////
        // - nome
        // - hash
        // - user_id

        // para a ordem eu tenho que
        /*
         * 1 - contar quantos itens tem persistidos no banco
         * 2 - verificar qual é a maior ordem existente
         * 3 - adicionar +1 a maior ordem existente
         * 4 - reordenar para que não existam lacunas
         */

        if ($destaque) {
            $todosDash = Dashboard::where('user_id', Auth::user()->id)->get();
            foreach ($todosDash as $dash) {
                $dash->destaque = false;
                $dash->save();
            }
        }

        $dash = Dashboard::create([
            'nome' => $nome,
            'ativo' => $ativo,
            'destaque' => $destaque,
            'hash' => $this->generateHash(),
            'user_id' => $user->id
        ]);
        /////////////////
        //    linha    //
        /////////////////
        // - table_dashboard_id
        // - ordem
        /////////////////
        //    coluna   //
        /////////////////
        // - tela_id
        // - table_dashboard_linha_id
        // - ordem
        for ($i = 0, $iMax = count($remainingElements); $i < $iMax; $i++) {
            // return $remainingElements[$i];
            $linha = DashboardLinha::create([
                'dashboard_id' => $dash->id,
                'ordem' => $i + 1
            ]);

            foreach ($remainingElements[$i]['telas'] as $j => $jValue) {
                $coluna = DashboardColuna::create([
                    'tela_id' => $jValue['tela']['id'],
                    'dashboard_linha_id' => $linha->id,
                    'ordem' => $j + 1
                ]);
            }
        }
    }

    private function generateHash()
    {
        // Get the current Unix timestamp
        $timestamp = time();

        // Generate a random string of 5 characters
        $randomString = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 7);

        // Concatenate the timestamp with the random string
        $hash = $timestamp . $randomString;

        // Return the hash
        return $hash;
    }

    public function show($id)
    {
        return Dashboard::find($id)->load('linhas.colunas.tela');
    }

    private function pesquisaOrdem()
    {
        // $quantidadeDash = Dashboard::where('user_id', Auth::user()->id)->count();
        $maiorOrdem = Dashboard::where('user_id', Auth::user()->id)->max('ordem');

        return $maiorOrdem + 1;

    }

    public function destroy($id)
    {

        // devo remover as colunas, depois as linhas e aí sim remover o conteúdo

        $linhas = DashboardLinha::where('dashboard_id', $id)->get();
        foreach ($linhas as $linha) {
            $colunas = DashboardColuna::where('dashboard_linha_id', $linha->id)->get();
            foreach ($colunas as $coluna) {
                $coluna->delete();
            }
            $linha->delete();
        }

        $dash = Dashboard::destroy($id);

        if ($dash === 0) {

            return response()->json([
                'erro' => 'Recurso não encontrado'
            ], 404);

        } else {
            return response()->json('', 204);
        }

    }

    public function verCompleto()
    {
        return Dashboard::where('user_id', Auth::user()->id)->where('ativo', true)
            ->orderBy('destaque', 'desc')
            ->get()
            ->load('linhas.colunas.tela');
    }

    public function verificaExisteHash(Request $request)
    {

        $hash = $request[0];

        return [Dashboard::where('hash', $hash)->count(), Dashboard::where('hash', $hash)->first()->load('linhas.colunas.tela')];

    }

    public function SaveCapturado(Request $request)
    {
        // Encontra o dashboard original pelo hash
        $dashOriginal = Dashboard::where('hash', $request['hashOriginal'])->first()->load('linhas.colunas.tela');

        // Pega os dados do request
        $nome = $request['nome'];
        $ativo = $request['ativo'];
        $destaque = $request['destaque'];

        if ($destaque) {
            $todosDash = Dashboard::where('user_id', Auth::user()->id)->get();
            foreach ($todosDash as $dash) {
                $dash->destaque = false;
                $dash->save();
            }
        }

        // Cria o novo dashboard
        $duplicatedDashboard = Dashboard::create([
            'nome' => $nome,
            'ativo' => $ativo,
            'destaque' => $destaque,
            'hash' => $this->generateHash(),
            'user_id' => Auth::user()->id
        ]);

        // Duplica as linhas e colunas associadas
        foreach ($dashOriginal->linhas as $linhaOriginal) {
            $novaLinha = DashboardLinha::create([
                'dashboard_id' => $duplicatedDashboard->id,
                'ordem' => $linhaOriginal->ordem
            ]);

            foreach ($linhaOriginal->colunas as $colunaOriginal) {
                DashboardColuna::create([
                    'tela_id' => $colunaOriginal->tela_id,
                    'dashboard_linha_id' => $novaLinha->id,
                    'ordem' => $colunaOriginal->ordem
                ]);
            }
        }

    }

}
