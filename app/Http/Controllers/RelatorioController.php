<?php

namespace App\Http\Controllers;


use App\Models\Curso;
use App\Models\UserAtendimento;
use Illuminate\Http\Request;



class RelatorioController extends Controller
{
    public function retornaVigencia()
    {
        $solicitacoes = UserAtendimento::where('status', '!=', 'Aberto')->get();

        $arrayVigencia = [];

        foreach ($solicitacoes as $solicitacao) {
            $arrayVigencia[] = $solicitacao->periodo_letivo;
        }

        $arrayVigencia = array_values(array_unique($arrayVigencia));
        rsort($arrayVigencia);

        return $arrayVigencia;
    }

    public function retornaCursosComVigencia($vigencia)
    {
        $atendimentos = UserAtendimento::where('status', '!=', 'Aberto')->where('periodo_letivo', $this->convertString($vigencia))->get();

        $arrayCursos = [];

        foreach ($atendimentos as $atendimento) {
            $arrayCursos[] = $atendimento->curso_id;
        }

        $arrayCursos = array_values(array_unique($arrayCursos));

        return Curso::whereIn('id', $arrayCursos)->get()->load('unidade');

    }

    public function geraRelatorioVigencia(Request $request)
    {

        $vigencia = $request['vigencia'];
        $curso_id = $request['curso_id'];

        if ($curso_id === 'Todos') {
            return UserAtendimento::where('status', '!=', 'Aberto')->where('periodo_letivo', $vigencia)->get()->load('aluno', 'curso', 'userAtendimentoResolucao.responsavel', 'userAtendimentoResolucao.designado');
        } else {
            return UserAtendimento::where('status', '!=', 'Aberto')->where('periodo_letivo', $vigencia)->where('curso_id', $curso_id)->get()->load('aluno', 'curso', 'userAtendimentoResolucao.responsavel', 'userAtendimentoResolucao.designado');
        }



    }

    private function convertString($input) {
        return str_replace('_', '.', $input);
    }

}
