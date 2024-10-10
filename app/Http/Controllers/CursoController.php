<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\SemestreLetivo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index()
    {

        return Curso::all()->load('unidade');

    }

    public function destroy($id)
    {

        $curso = Curso::destroy($id);

        if ($curso === 0) {

            return response()->json([
                'erro' => 'Recurso não encontrado'
            ], 404);

        } else {
            return response()->json('', 204);
        }

    }
/*
    public function pesquisaCursos()
    {

        return Curso::all()->load('unidade','semestresLetivos');

    }
*/
    public function pesquisaCursos()
    {
        $semestreSelecionado = $this->emVigencia();

        // Buscar os cursos que pertencem à unidade e possuem semestres letivos com o código igual ao semestre vigente
        $cursos = Curso::whereHas('semestresLetivos', function ($query) use ($semestreSelecionado) {
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
                'turnos' => [],
                'unidade' =>$curso->unidade
            ];

            // Adicionar os semestres letivos, turnos e turmas ao curso
            foreach ($semestresDoCurso as $semestre) {
                // Adicionar os turnos e turmas para o semestre letivo
                foreach ($semestre->turnos as $turno) {
                    $turnoData = [
                        'id' => $turno->id,
                        'horario' => $turno->turnoParametro->horario,
                        'identificador_horario' => $turno->turnoParametro->identificador_horario,
                        'periodo_turmas' => $turno->periodoTurma
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

    public function emVigencia()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentSemester = $currentMonth <= 6 ? 1 : 2;
        // Retorna o ano com os dois últimos dígitos e o semestre
        return substr($currentYear, 2) . '.' . $currentSemester;
    }

}
