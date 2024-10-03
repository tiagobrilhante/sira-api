<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\PeriodoTurma;
use App\Models\SemestreLetivo;
use App\Models\Turno;
use App\Models\TurnoParametro;
use App\Models\Unidade;
use Carbon\Carbon;
use Illuminate\Http\Request;


class SemestreLetivoController extends Controller
{
    public function index()
    {
        return SemestreLetivo::all()->load('curso.unidade');
    }

    public function vigente()
    {
        // Obter o semestre vigente usando a função emVigencia()
        $semestreVigente = $this->emVigencia();

        // Inicializar o array de unidades
        $unidades = [];

        // Buscar todos os cursos com seus respectivos semestres (se existirem)
        $cursos = Curso::with(['unidade', 'semestresLetivos'])->get();

        // Iterar pelos cursos e organizar por unidades
        foreach ($cursos as $curso) {
            $unidade = $curso->unidade;

            // Verificar se a unidade já foi adicionada ao array
            if (!isset($unidades[$unidade->id])) {
                // Adicionar a unidade ao array se ainda não existir
                $unidades[$unidade->id] = [
                    'id' => $unidade->id,
                    'nome' => $unidade->nome,
                    'prefixo' => $unidade->prefixo,
                    'cursos' => []
                ];
            }

            // Filtrar os semestres do curso
            $semestreAtual = null;
            $semestresAnteriores = [];

            foreach ($curso->semestresLetivos as $semestre) {
                if ($semestre->codigo === $semestreVigente) {
                    $semestreAtual = $semestre;
                } elseif ($semestre->codigo < $semestreVigente) {
                    $semestresAnteriores[] = $semestre;
                }
            }

            // Se o curso tem semestre vigente, adicioná-lo uma vez à lista de cursos da unidade
            if ($semestreAtual) {
                $unidades[$unidade->id]['cursos'][] = [
                    'id' => $curso->id,
                    'nome' => $curso->nome,
                    'codigo' => $curso->codigo,
                    'qtd_periodos_possiveis' => $curso->qtd_periodos_possiveis,
                    'semestre_codigo' => $semestreAtual->codigo
                ];
            }
        }


        // Retornar a resposta como JSON
        return response()->json(array_values($unidades));
    }

    public function emVigencia()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentSemester = $currentMonth <= 6 ? 1 : 2;
        // Retorna o ano com os dois últimos dígitos e o semestre
        return substr($currentYear, 2) . '.' . $currentSemester;
    }

    public function vigenteCurso(Request $request)
    {
        $semestreSelecionado = $request['semestreSelecionado'];
        $unidade_id = $request['unidade_id'];

        // Buscar os cursos que pertencem à unidade e possuem semestres letivos com o código igual ao semestre vigente
        $cursos = Curso::where('unidade_id', $unidade_id)  // Filtrar pela unidade
        ->whereHas('semestresLetivos', function ($query) use ($semestreSelecionado) {
            $query->where('codigo', $semestreSelecionado);
        })
            ->get();

        $arrayIdCursos = [];
        foreach ($cursos as $curso) {
            $arrayIdCursos[] = $curso->id;
        }

        // Buscar os semestres letivos correspondentes aos cursos filtrados
        $semestresLetivos = SemestreLetivo::whereIn('curso_id', $arrayIdCursos)
            ->where('codigo', $semestreSelecionado)
            ->with('turnos.turnoParametro')  // Carregar os turnos e turmas
            ->get();

        // Montar o array customizado com os cursos, semestres letivos, turnos e turmas
        $resultado = [];
        foreach ($cursos as $curso) {
            // Filtrar os semestres letivos do curso atual
            $semestresDoCurso = $semestresLetivos->where('curso_id', $curso->id);

            // Estrutura para o curso com os semestres, turnos e turmas
            $cursoData = [
                'id' => $curso->id,
                'nome' => $curso->nome,
                'codigo' => $curso->codigo,
                'qtd_periodos_possiveis' => $curso->qtd_periodos_possiveis,
                'turnos' => []
            ];

            // Adicionar os semestres letivos, turnos e turmas ao curso
            foreach ($semestresDoCurso as $semestre) {
                // Adicionar os turnos e turmas para o semestre letivo
                foreach ($semestre->turnos as $turno) {
                    $turnoData = [
                        'horario' => $turno->turnoParametro->horario,
                        'identificador_horario' => $turno->turnoParametro->identificador_horario,
                        'periodo_turma' => $turno->periodoTurma
                    ];

                    // Adicionar o turno ao semestre
                    $cursoData['turnos'][] = $turnoData;
                }
            }

            // Adicionar o curso ao resultado final
            $resultado[] = $cursoData;
        }

        // Retornar o array customizado
        return response()->json($resultado);
    }


    public function cursosUnidadeVigente(int $id)
    {
        $cursos = Curso::where('unidade_id', $id)->get()->load('semestresLetivos');

        return $cursos;

    }

    public function retornaSemestreTurnos($id)
    {
        $semestreLetivo = SemestreLetivo::find($id);

        if ($semestreLetivo) {
            $semestreLetivo->load('turnos.turnoParametro', 'turnos.periodoTurma');
        }

        return $semestreLetivo;
    }

    //habilita turmas em um semestre letivo...
    // leva em consideração turnos, curso e unidade além do período de vigência
    public function habilitaTurma(Request $request)
    {

        // return $request->all();

        // primeiro eu crio o semestre letivo do ano se não existir

        // depois eu crio o turno que foi passado e qtd_turmas

        $curso = $request['curso'];
        $turno = $request['turno'];
        $vigencia = $request['vigencia'];
        $turmas_por_periodo = $request['turmas_por_periodo'];

        $selectedCurso = Curso::find($curso['id']);


        // só pode existir um codigo por curso

        $existeSemestreLetivo = SemestreLetivo::where('codigo', $vigencia)->where('curso_id', $curso['id'])->count();

        if ($existeSemestreLetivo === 0) {
            $semestreLetivo = SemestreLetivo::create([
                'codigo' => $vigencia,
                'curso_id'=> $curso['id']
            ]);
        } else {
            $semestreLetivo = SemestreLetivo::where('codigo', $vigencia)->where('curso_id', $curso['id'])->first();
        }

        $verificaTurno = Turno::where('turno_parametro_id', $turno['id'])->where('semestre_letivo_id', $semestreLetivo->id)->count();

        if ($verificaTurno === 0) {
            $turno = Turno::create([
                'turno_parametro_id' => $turno['id'],
                //  'qtd_turmas' => $request['qtd_turmas'],
                'semestre_letivo_id'=> $semestreLetivo->id
            ]);
        } else {
            $turno = Turno::where('turno_parametro_id', $turno['id'])->where('semestre_letivo_id', $semestreLetivo->id)->first();
        }


        foreach ($turmas_por_periodo as $turma) {
            $periodoTurma = PeriodoTurma::create([
                'periodo' => $turma['periodo'],
                'qtd_turmas_por_periodo' => $turma['qtd_turmas'],
                'turno_id' => $turno->id
            ]);
        }

        return 'ok';

    }

    public function editaTurma(int $id, Request $request)
    {

        $turno = Turno::find($id);
        $turno->qtd_turmas = $request['qtd_turmas'];
        $turno->save();

        return 'ok';

    }
    public function deletaTurma(Request $request)
    {
        // return $request->all();

        // primeiro eu crio o semestre letivo do ano se não existir

        // depois eu crio o turno que foi passado e qtd_turmas

        $curso = $request['curso'];
        $vigencia = $request['vigencia'];

        $selectedCurso = Curso::find($curso['id']);

        $semestreLetivo = SemestreLetivo::where('codigo', $vigencia)->where('curso_id', $selectedCurso->id)->first();

        $turno = Turno::where('turno_parametro_id', $request['turno']['id'])->where('semestre_letivo_id', $semestreLetivo->id)->first();

        if ($turno) {
            $turno->delete();
        }

        // Verificar se existem mais vínculos com semestreLetivo, não havendo, excluir também o SemestreLetivo.
        $turnos = Turno::where('semestre_letivo_id', $semestreLetivo->id)->count();

        if ($turnos === 0) {
            $semestreLetivo->delete();
        }

        return response()->json(['message' => 'Turma deletada com sucesso'], 200);
    }


}
