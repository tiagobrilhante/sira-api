<?php

namespace App\Http\Controllers;


use App\Models\Curso;
use App\Models\TurnoParametro;
use App\Models\Unidade;
use App\Models\UserAtendimento;
use App\Models\UserAtendimentoResolucao;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAtendimentoController extends Controller
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

        $arrayLetras = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'X', 'W', 'Y', 'Z'];

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

    public function store(Request $request)
    {
        $descricao = $request['descricao'];
        $password = $request['password'];
        $data = $request['data'];
        $turmaCursoGroup = $request['turmaCursoGroup'];
        $usuarioLogado = Auth::user();
        $periodo_letivo = $request['periodo_letivo'];


        if (Hash::check($password, $usuarioLogado->password)) {

            UserAtendimento::create([
                'descricao' => $descricao,
                'periodo_letivo' => $periodo_letivo,
                'codigo_geral' => $turmaCursoGroup['geral'],
                'status' => 'Aberto',
                'data_solicitacao' => $this->convertToDateTime($data),
                'data_solucao' => null,
                'curso_id' => $turmaCursoGroup['curso']['id'],
                'user_id' => $usuarioLogado->id
            ]);

            return 'ok';

        } else {
            return 'Senha inválida';
        }
    }

    private function convertToDateTime($dateString)
    {
        // Define the format of the input date string
        $format = 'd/m/Y H:i:s';

        // Create a DateTime object from the input string
        $dateTime = DateTime::createFromFormat($format, $dateString);

        // Check if the conversion was successful
        if ($dateTime === false) {
            // Handle the error if the date string is not in the expected format
            throw new Exception('Invalid date format');
        }

        return $dateTime;
    }

    private function getCurrentDateTime()
    {
        // Create a new DateTime object with the current date and time
        $dateTime = new DateTime();

        // Format the date and time as 'Y-m-d H:i:s'
        return $dateTime->format('Y-m-d H:i:s');
    }

    public function index()
    {
        return UserAtendimento::all()->load('aluno', 'curso');
    }

    public function pesquisa(Request $request)
    {

        $unidade = $request['unidade'];
        $curso = $request['curso'];
        $estado = $request['estado'];

        if ($unidade === 'todos') {
            return UserAtendimento::where('status', $estado)->get()->load('aluno', 'curso', 'userAtendimentoResolucao.responsavel');
        } else {
            if ($curso === 'todos') {
                return UserAtendimento::where('status', $estado)
                    ->whereHas('curso', function ($query) use ($unidade) {
                        $query->where('unidade_id', $unidade);
                    })
                    ->get()
                    ->load('aluno', 'curso', 'userAtendimentoResolucao.responsavel');

            } else {
                return UserAtendimento::where('status', $estado)
                    ->whereHas('curso', function ($query) use ($unidade, $curso) {
                        $query->where('unidade_id', $unidade)
                            ->where('id', $curso);
                    })
                    ->get()
                    ->load('aluno', 'curso', 'userAtendimentoResolucao.responsavel');
            }
        }

    }

    public function resolve(Request $request)
    {
        $atendimento = UserAtendimento::find($request['solicitacao']['id']);

        $atendimento->status = $request['intervencaoEstado'];
        $atendimento->data_solucao = $this->getCurrentDateTime();
        $atendimento->save();

        UserAtendimentoResolucao::create([
            'intervencao_coordenacao' => $request['intervencaoCoordenacao'],
            'intervencao_outros' => null,
            'user_atendimento_id' => $atendimento->id,
            'user_id' => Auth::user()->id,
            'designado_id' => null
        ]);


        return $atendimento->load('aluno', 'curso','userAtendimentoResolucao.responsavel');
    }

    public function buscaAtendimentosAluno($tipo)
    {
        $user = Auth::user();
        return UserAtendimento::where('user_id', $user->id)->where('status', $tipo)->get()->load('aluno', 'curso','userAtendimentoResolucao.responsavel');

    }

}
