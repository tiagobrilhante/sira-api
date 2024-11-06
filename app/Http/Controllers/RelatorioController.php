<?php

namespace App\Http\Controllers;


use App\Models\Curso;
use App\Models\UserAtendimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class RelatorioController extends Controller
{
    public function retornaVigencia()
    {
        if (Auth::user()->tipo === 'Administrador Geral') {
            $solicitacoes = UserAtendimento::where('status', '!=', 'Aberto')->get();
        } else {


            $cursos = Auth::user()->cursos;
            $arrayIdCurso = [];
            foreach ($cursos as $curso) {
                array_push($arrayIdCurso, $curso->curso_id);
            }

            $solicitacoes = UserAtendimento::where('status', '!=', 'Aberto')
                ->whereIn('curso_id', $arrayIdCurso)->get();

        }
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


        if (Auth::user()->tipo === 'Administrador Geral') {
            $atendimentos = UserAtendimento::where('status', '!=', 'Aberto')->where('periodo_letivo', $this->convertString($vigencia))->get();
        } else {


            $cursos = Auth::user()->cursos;
            $arrayIdCurso = [];
            foreach ($cursos as $curso) {
                array_push($arrayIdCurso, $curso->curso_id);
            }

            $atendimentos = UserAtendimento::where('status', '!=', 'Aberto')->whereIn('curso_id', $arrayIdCurso)->where('periodo_letivo', $this->convertString($vigencia))->get();

        }


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

        if (Auth::user()->tipo === 'Administrador Geral') {
            if ($curso_id === 'Todos') {
                return UserAtendimento::where('status', '!=', 'Aberto')->where('periodo_letivo', $vigencia)->get()->load('aluno', 'curso', 'userAtendimentoResolucao.responsavel', 'userAtendimentoResolucao.designado');
            } else {
                return UserAtendimento::where('status', '!=', 'Aberto')->where('periodo_letivo', $vigencia)->where('curso_id', $curso_id)->get()->load('aluno', 'curso', 'userAtendimentoResolucao.responsavel', 'userAtendimentoResolucao.designado');
            }
        }
        if (Auth::user()->tipo === 'Administrador') {

            if ($curso_id === 'Todos') {

                $cursos = Auth::user()->cursos;
                $arrayIdCurso = [];
                foreach ($cursos as $curso) {
                    array_push($arrayIdCurso, $curso->curso_id);
                }

                return UserAtendimento::where('status', '!=', 'Aberto')->where('periodo_letivo', $vigencia)->whereIn('curso_id', $arrayIdCurso)->get()->load('aluno', 'curso', 'userAtendimentoResolucao.responsavel', 'userAtendimentoResolucao.designado');
            } else {
                return UserAtendimento::where('status', '!=', 'Aberto')->where('periodo_letivo', $vigencia)->where('curso_id', $curso_id)->get()->load('aluno', 'curso', 'userAtendimentoResolucao.responsavel', 'userAtendimentoResolucao.designado');
            }
        }


    }

    private function convertString($input)
    {
        return str_replace('_', '.', $input);
    }

}
