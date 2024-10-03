<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\PeriodoTurma;
use App\Models\SemestreLetivo;
use App\Models\Turno;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PeriodoTurmaController extends Controller
{

    public function destroy($id)
    {

        $periodoTurma = PeriodoTurma::find($id);
        $turno = Turno::find($periodoTurma->turno_id);
        $semestreLetivo = SemestreLetivo::find($turno->semestre_letivo_id);


        $periodoTurmaDelete = PeriodoTurma::destroy($id);

        $contarOutrosPeriodosNoTurno = PeriodoTurma::where('turno_id', $turno->id)->count();
        $contarOutrosPeriodosDebug = PeriodoTurma::where('turno_id', $turno->id)->get();

        if ($contarOutrosPeriodosNoTurno === 0) {
            $turno = Turno::destroy($turno->id);
            SemestreLetivo::destroy($semestreLetivo->id);

        }

        // tem que ver o turno_id e tb o semestre_letivo_id
        // a regra é... caso não tenham mais periodosTurma, vinculados ao turno_id,
        // remove o turno_id turno...
        // depois verifica se existem turnos vinculados ao semestre letivo,,,, se não
        // remove o semestreLetivo

        if ($periodoTurma === 0) {

            return response()->json([
                'erro' => 'Recurso não encontrado'
            ], 404);

        } else {
            return response()->json('', 204);
        }

    }


    public function update(int $id, Request $request)
    {
       $periodoTurma = PeriodoTurma::find($id);

        if (is_null($periodoTurma)) {
            return response()->json([
                'erro' => 'Recurso não encontrado'
            ], 404);
        }

        $periodoTurma->fill($request->only([
            'qtd_turmas_por_periodo'
        ]));

        $periodoTurma->save();

        return $periodoTurma;
    }

}
