<?php

namespace App\Http\Controllers;


use App\Models\Curso;
use App\Models\SemestreLetivo;
use App\Models\Unidade;
use App\Models\User;
use App\Models\UserAtendimento;
use App\Models\UserAtendimentoResolucao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EstatisticaController extends Controller
{
    public function index()
    {

        if (Auth::user()->tipo === 'Administrador Geral') {
            $quantidadeAtendimentosCriados = UserAtendimento::all()->count();
            $quantidadeAtendimentosDeferidos = UserAtendimento::where('status', 'Deferido')->count();
            $quantidadeAtendimentosIndeferidos = UserAtendimento::where('status', 'Indeferido')->count();
            $quantidadeAtendimentosEmAberto = UserAtendimento::where('status', 'Aberto')->count();
            $quantidadeAtendimentosResolvidos = $quantidadeAtendimentosIndeferidos + $quantidadeAtendimentosDeferidos;
            $semestresLetivos = SemestreLetivo::all();

            $arrayDeCodigos = [];
            foreach ($semestresLetivos as $semestreLetivo) {
                $arrayDeCodigos[] = $semestreLetivo->codigo;
            }
            $arrayDeCodigos = array_unique($arrayDeCodigos);
            $quantidadeSemestresLetivos = count($arrayDeCodigos);

            $unidades = Unidade::all();
            $cursos = Curso::all()->load('unidade');
            $coordenadores = User::whereIn('tipo', ['Administrador', 'Administrador Geral'])->get();
            $qtdAlunos = User::where('tipo', 'Aluno')->count();


            $retorno = new \stdClass();
            $retorno->qtdatendimentoscriados = $quantidadeAtendimentosCriados;
            $retorno->qtdatendimentosresolvidos = $quantidadeAtendimentosResolvidos;
            $retorno->qtdatendimentosdeferidos = $quantidadeAtendimentosDeferidos;
            $retorno->qtdatendimentosindeferidos = $quantidadeAtendimentosIndeferidos;
            $retorno->qtdatendimentosemaberto = $quantidadeAtendimentosEmAberto;
            $retorno->unidades = $unidades;
            $retorno->cursos = $cursos;
            $retorno->coordenadores = $coordenadores;
            $retorno->qtdalunos = $qtdAlunos;
            $retorno->qtdperiodos = $quantidadeSemestresLetivos;


            return response()->json($retorno);
        }

        if (Auth::user()->tipo === 'Administrador') {

            $cursos = Auth::user()->cursos;
            $arrayIdCurso = [];
            foreach ($cursos as $curso) {
                array_push($arrayIdCurso, $curso->curso_id);
            }

            $quantidadeAtendimentosCriados = UserAtendimento::whereIn('curso_id',$arrayIdCurso)->count();
            $quantidadeAtendimentosDeferidos = UserAtendimento::where('status', 'Deferido')->whereIn('curso_id',$arrayIdCurso)->count();
            $quantidadeAtendimentosIndeferidos = UserAtendimento::where('status', 'Indeferido')->whereIn('curso_id',$arrayIdCurso)->count();
            $quantidadeAtendimentosEmAberto = UserAtendimento::where('status', 'Aberto')->whereIn('curso_id',$arrayIdCurso)->count();
            $quantidadeAtendimentosResolvidos = $quantidadeAtendimentosIndeferidos + $quantidadeAtendimentosDeferidos;

            $cursos = Curso::whereIn('id', $arrayIdCurso)->get()->load('unidade');
            $coordenadores = [Auth::user()];


            $retorno = new \stdClass();
            $retorno->qtdatendimentoscriados = $quantidadeAtendimentosCriados;
            $retorno->qtdatendimentosresolvidos = $quantidadeAtendimentosResolvidos;
            $retorno->qtdatendimentosdeferidos = $quantidadeAtendimentosDeferidos;
            $retorno->qtdatendimentosindeferidos = $quantidadeAtendimentosIndeferidos;
            $retorno->qtdatendimentosemaberto = $quantidadeAtendimentosEmAberto;
            $retorno->cursos = $cursos;
            $retorno->coordenadores = $coordenadores;

            return response()->json($retorno);
        }


    }

    public function coordenadorGeral($id)
    {

        $coordenador = User::find($id)->load('cursos.curso.unidade');


        $quantidadeAtendimentosDeferidos = UserAtendimentoResolucao::whereHas('userAtendimento', function ($query) {
            $query->where('status', 'Deferido');
        })->where('user_id', $id)->count();

        $quantidadeAtendimentosIndeferidos = UserAtendimentoResolucao::whereHas('userAtendimento', function ($query) {
            $query->where('status', 'Indeferido');
        })->where('user_id', $id)->count();


        // periodos disponiveis

        $periodos = UserAtendimentoResolucao::whereHas('userAtendimento')->where('user_id', $id)->get();
        $arrayDeCodigos = [];
        foreach ($periodos as $periodo) {
            $arrayDeCodigos[] = $periodo->userAtendimento->periodo_letivo;
        }

        $arrayDeCodigos = array_values(array_unique($arrayDeCodigos));
        rsort($arrayDeCodigos);


        $retorno = new \stdClass();
        $retorno->coordenador = $coordenador;
        $retorno->qtdatendimentossolucionados = $quantidadeAtendimentosIndeferidos + $quantidadeAtendimentosDeferidos;
        $retorno->qtdatendimentosdeferidos = $quantidadeAtendimentosDeferidos;
        $retorno->qtdatendimentosindeferidos = $quantidadeAtendimentosIndeferidos;
        $retorno->qtdperiodos = $arrayDeCodigos;

        return response()->json($retorno);

    }

    public function coordenadorPorPeriodo(Request $request)
    {
        $id = $request['coordenadorId'];
        $periodo_letivo = $request['periodo'];

        if ($periodo_letivo !== 'Todos') {
            $retorno = UserAtendimentoResolucao::whereHas('userAtendimento', function ($query) use ($periodo_letivo) {
                $query->where('periodo_letivo', $periodo_letivo);
            })->where('user_id', $request['coordenadorId'])->get();

            $quantidadeAtendimentosDeferidos = $retorno->filter(function ($item) {
                return $item->userAtendimento->status === 'Deferido';
            })->count();

            $quantidadeAtendimentosIndeferidos = $retorno->filter(function ($item) {
                return $item->userAtendimento->status === 'Indeferido';
            })->count();

            return response()->json([
                'quantidadeAtendimentosDeferidos' => $quantidadeAtendimentosDeferidos,
                'quantidadeAtendimentosIndeferidos' => $quantidadeAtendimentosIndeferidos,
            ]);
        } else {
            $retorno = UserAtendimentoResolucao::whereHas('userAtendimento')->where('user_id', $request['coordenadorId'])->get();

            $result = $retorno->groupBy(function ($item) {
                return $item->userAtendimento->periodo_letivo;
            })->map(function ($group) {
                $deferidos = $group->filter(function ($item) {
                    return $item->userAtendimento->status === 'Deferido';
                })->count();

                $indeferidos = $group->filter(function ($item) {
                    return $item->userAtendimento->status === 'Indeferido';
                })->count();

                return [
                    'periodo_letivo' => $group->first()->userAtendimento->periodo_letivo,
                    'quantidadeAtendimentosDeferidos' => $deferidos,
                    'quantidadeAtendimentosIndeferidos' => $indeferidos,
                    'quantidadeTotal' => $indeferidos + $deferidos,
                ];
            })->values();

            return response()->json($result);
        }
    }


    public function unidadeGeral($id)
    {

        $unidade = Unidade::find($id)->load('cursos');


        $quantidadeAtendimentosDeferidos = UserAtendimentoResolucao::whereHas('userAtendimento', function ($query) use ($unidade) {
            $query->where('status', 'Deferido')
                ->whereHas('curso', function ($query) use ($unidade) {
                    $query->where('unidade_id', $unidade->id);
                });
        })->count();


        $quantidadeAtendimentosIndeferidos = UserAtendimentoResolucao::whereHas('userAtendimento', function ($query) use ($unidade) {
            $query->where('status', 'Indeferido')
                ->whereHas('curso', function ($query) use ($unidade) {
                    $query->where('unidade_id', $unidade->id);
                });
        })->count();


        // periodos disponiveis
        $periodos = UserAtendimentoResolucao::whereHas('userAtendimento', function ($query) use ($unidade) {
            $query->whereHas('curso', function ($query) use ($unidade) {
                $query->where('unidade_id', $unidade->id);
            });
        })->get();

        $arrayDeCodigos = $periodos->map(function ($item) {
            return $item->userAtendimento->periodo_letivo;
        })->unique()->sortDesc()->values()->all();


        $retorno = new \stdClass();
        $retorno->unidade = $unidade;
        $retorno->qtdatendimentossolucionados = $quantidadeAtendimentosIndeferidos + $quantidadeAtendimentosDeferidos;
        $retorno->qtdatendimentosdeferidos = $quantidadeAtendimentosDeferidos;
        $retorno->qtdatendimentosindeferidos = $quantidadeAtendimentosIndeferidos;
        $retorno->qtdperiodos = $arrayDeCodigos;

        return response()->json($retorno);

    }

    public function unidadePorPeriodo(Request $request)
    {


        $id = $request['unidadeId'];
        $periodo_letivo = $request['periodo'];

        if ($periodo_letivo !== 'Todos') {

            $retorno = UserAtendimentoResolucao::whereHas('userAtendimento', function ($query) use ($periodo_letivo, $id) {
                $query->whereHas('curso', function ($query) use ($id, $periodo_letivo) {
                    $query->where('unidade_id', $id);
                })->where('periodo_letivo', $periodo_letivo);
            })->get();

            $quantidadeAtendimentosDeferidos = $retorno->filter(function ($item) {
                return $item->userAtendimento->status === 'Deferido';
            })->count();

            $quantidadeAtendimentosIndeferidos = $retorno->filter(function ($item) {
                return $item->userAtendimento->status === 'Indeferido';
            })->count();

            return response()->json([
                'quantidadeAtendimentosDeferidos' => $quantidadeAtendimentosDeferidos,
                'quantidadeAtendimentosIndeferidos' => $quantidadeAtendimentosIndeferidos,
            ]);
        } else {
            $retorno = UserAtendimentoResolucao::whereHas('userAtendimento', function ($query) use ($id) {
                $query->whereHas('curso', function ($query) use ($id) {
                    $query->where('unidade_id', $id);
                });
            })->get();

            $result = $retorno->groupBy(function ($item) {
                return $item->userAtendimento->periodo_letivo;
            })->map(function ($group) {
                $deferidos = $group->filter(function ($item) {
                    return $item->userAtendimento->status === 'Deferido';
                })->count();

                $indeferidos = $group->filter(function ($item) {
                    return $item->userAtendimento->status === 'Indeferido';
                })->count();

                return [
                    'periodo_letivo' => $group->first()->userAtendimento->periodo_letivo,
                    'quantidadeAtendimentosDeferidos' => $deferidos,
                    'quantidadeAtendimentosIndeferidos' => $indeferidos,
                    'quantidadeTotal' => $indeferidos + $deferidos,
                ];
            })->values();

            return response()->json($result);


        }
    }






    public function cursoGeral($id)
    {

        $curso = Curso::find($id)->load('unidade');


        $quantidadeAtendimentosDeferidos = UserAtendimentoResolucao::whereHas('userAtendimento', function ($query) use ($curso) {
            $query->where('status', 'Deferido')->where('curso_id',$curso->id);
        })->count();


        $quantidadeAtendimentosIndeferidos = UserAtendimentoResolucao::whereHas('userAtendimento', function ($query) use ($curso) {
            $query->where('status', 'Indeferido')->where('curso_id',$curso->id);
        })->count();


        // periodos disponiveis
        $periodos = UserAtendimentoResolucao::whereHas('userAtendimento', function ($query) use ($curso) {
            $query->where('curso_id', $curso->id);
        })->get();

        $arrayDeCodigos = $periodos->map(function ($item) {
            return $item->userAtendimento->periodo_letivo;
        })->unique()->sortDesc()->values()->all();


        $retorno = new \stdClass();
        $retorno->curso = $curso;
        $retorno->qtdatendimentossolucionados = $quantidadeAtendimentosIndeferidos + $quantidadeAtendimentosDeferidos;
        $retorno->qtdatendimentosdeferidos = $quantidadeAtendimentosDeferidos;
        $retorno->qtdatendimentosindeferidos = $quantidadeAtendimentosIndeferidos;
        $retorno->qtdperiodos = $arrayDeCodigos;

        return response()->json($retorno);

    }

    public function cursoPorPeriodo(Request $request)
    {


        $id = $request['cursoId'];
        $periodo_letivo = $request['periodo'];

        if ($periodo_letivo !== 'Todos') {

            $retorno = UserAtendimentoResolucao::whereHas('userAtendimento', function ($query) use ($periodo_letivo, $id) {
                $query->where('curso_id', $id )->where('periodo_letivo', $periodo_letivo);
            })->get();

            $quantidadeAtendimentosDeferidos = $retorno->filter(function ($item) {
                return $item->userAtendimento->status === 'Deferido';
            })->count();

            $quantidadeAtendimentosIndeferidos = $retorno->filter(function ($item) {
                return $item->userAtendimento->status === 'Indeferido';
            })->count();

            return response()->json([
                'quantidadeAtendimentosDeferidos' => $quantidadeAtendimentosDeferidos,
                'quantidadeAtendimentosIndeferidos' => $quantidadeAtendimentosIndeferidos,
            ]);
        } else {
            $retorno = UserAtendimentoResolucao::whereHas('userAtendimento', function ($query) use ($id) {
                $query->where('curso_id', $id );
            })->get();

            $result = $retorno->groupBy(function ($item) {
                return $item->userAtendimento->periodo_letivo;
            })->map(function ($group) {
                $deferidos = $group->filter(function ($item) {
                    return $item->userAtendimento->status === 'Deferido';
                })->count();

                $indeferidos = $group->filter(function ($item) {
                    return $item->userAtendimento->status === 'Indeferido';
                })->count();

                return [
                    'periodo_letivo' => $group->first()->userAtendimento->periodo_letivo,
                    'quantidadeAtendimentosDeferidos' => $deferidos,
                    'quantidadeAtendimentosIndeferidos' => $indeferidos,
                    'quantidadeTotal' => $indeferidos + $deferidos,
                ];
            })->values();

            return response()->json($result);


        }
    }


}
