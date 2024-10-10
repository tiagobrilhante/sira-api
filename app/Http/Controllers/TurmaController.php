<?php

namespace App\Http\Controllers;


use App\Models\Curso;
use App\Models\TurnoParametro;
use App\Models\Unidade;

class TurmaController extends Controller
{
    public function retornaObjetoCursoPeriodoTurnoTurma($codigo)
    {

        // Spl0790102NMA

        /*
         * (SPL) identificador da unidade -  3 caracteres
         * (079) código de curso - 3 caracteres
         * (01) Numero constante -  Nunca Muda
         * (02) identificador de períodos - valores menores que 10, possuem 2 digitos
         * (NM) código identificadores de turnos, possuem 2 ou 3 caracteres
         * (A) Código da turma - possui um caracter
         */


        // Verifica se o código tem o comprimento esperado
        if (strlen($codigo) < 12) {
            return response()->json(['erro' => 'Código inválido'], 400);
        }

        // Extrai as partes do código conforme as regras
        $identificadorUnidade = substr($codigo, 0, 3);
        $codigoCurso = substr($codigo, 3, 3);
        $numeroConstante = substr($codigo, 6, 2);
        $identificadorPeriodo = substr($codigo, 8, 2);
        $codigoTurno = substr($codigo, 10, -1); // Pega todos os caracteres até o penúltimo
        $codigoTurma = substr($codigo, -1); // Pega o último caractere

        // Melhorando as informacões extraídas

        $unidade = Unidade::where('prefixo', $identificadorUnidade)->first();
        $curso = Curso::where('codigo', $codigoCurso)->first();
        $turno = TurnoParametro::where('identificador_horario', $codigoTurno)->first();

        // Monta o array com as informações extraídas
        $informacoes = [
            'unidade' => $unidade,
            'curso' => $curso,
            'numero_constante' => $numeroConstante,
            'identificador_periodo' => $identificadorPeriodo,
            'turno' => $turno,
            'codigo_turma' => $codigoTurma,
            'geral' => $codigo
        ];

        return $informacoes;

    }

    private function montaCodigo($curso)
    {

        $codigoUnidade = $curso['unidade']['prefixo'];
        $codigoCurso = $curso['codigo'];
        $periodo = str_pad($curso['periodo'], 2, '0', STR_PAD_LEFT);
        $turno = $curso['turno']['identificador_horario'];

        $arrayLetras = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','X','W','Y','Z'];

        $turmaIndex = ($curso['turma'] ?? 1) - 1; // Default to 1 if 'turma' is not set
        $letra = $arrayLetras[$turmaIndex] ?? 'A'; // Use 'A' if index is out of bounds


        // base de exemplo
        /*
         * SPL (codigo da unidade) 079 (curso) 01 (turma unica - só pra constar) 07 (periodo)  NN (turno) A (turma)
         */

        // Spl 079 01 02 NM A


        // Spl 079 01 06 NTA C
        return $codigoUnidade . $codigoCurso . '01' . $periodo . $turno . $letra;

    }

}
