<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAlunoVinculo;
use App\Models\UserCurso;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    //lista os usuários
    public function index()
    {
        return User::all();
    }

    //lista os usuários para administracão
    public function indexAdm()
    {
        return User::where('tipo', '!=', 'Aluno')->get()->load('cursos.curso');
    }

    // retorna se o usuário pode ou não usar a matricula ao se cadastrar / editar
    public function matriculaExist(Request $request)
    {
        $id_usuario = $request['id'];
        if ($id_usuario === null) {
            $teste = User::where('matricula', $request['matricula'])->count();
        } else {
            $teste = User::where('matricula', $request['matricula'])->where('id', '!=', $id_usuario)->count();
        }
        return $teste;
    }

    // altera a senha de um usuário resetado
    public function alteraSenhaResetada(Request $request)
    {
        $user = User::find($request['id']);
        $user->password = Hash::make($request['password']);
        $user->reset = 0;
        $user->save();
    }

    // reseta a senha de um usuário TEM QUE ARRUMAR
    public function resetaSenha(Request $request)
    {
        $user = User::find($request['id']);
        $user->password = Hash::make($user->matricula);
        $user->reset = 1;
        $user->save();

        return $user->load('cursos');
    }

    // altera a senha de um usuário normal
    public function alteraSenhaNormal(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->password = Hash::make($request['password']);
        $user->save();
    }

    // cria um novo usuário
    public function createUser(Request $request)
    {
        $user = User::create([
            'nome' => $request['nome'],
            'matricula' => $request['matricula'],
            'telefone' => $request['telefone'],
            'email' => $request['email'],
            'tipo' => $request['tipo'],
            'reset' => 1,
            'password' => Hash::make($request['matricula'])
        ]);

        if ($request['tipo'] === 'Administrador' && is_array($request['cursos'])) {
            // Recebe um array de IDs de cursos
            foreach ($request['cursos'] as $cursoId) {
                UserCurso::create([
                    'curso_id' => $cursoId,
                    'user_id' => $user->id
                ]);
            }
        }

        return $user->load('cursos.cursos');
    }

    // altera um usuário
    public function update(int $id, Request $request)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return response()->json([
                'erro' => 'Recurso não encontrado'
            ], 404);
        }

        $user->fill($request->only([
            'nome', 'matricula', 'telefone', 'email', 'tipo', 'reset'
        ]));

        $user->save();

        if ($request['tipo'] === 'Administrador' && is_array($request['cursos'])) {
            // Sync the cursos relationship
            $user->cursos()->delete();

            foreach ($request['cursos'] as $cursoId) {
                UserCurso::create([
                    'curso_id' => $cursoId,
                    'user_id' => $user->id
                ]);
            }
        }

        return $user->load('cursos.curso');
    }

    // remove um usuário
    public function destroy($id)
    {

        $usuario = User::find($id);
        $usuario->cursos()->delete();
        $usuario->delete();

        if ($usuario === 0) {

            return response()->json([
                'erro' => 'Recurso não encontrado'
            ], 404);

        } else {
            return response()->json('', 204);
        }

    }

    public function checaMatriculaExist(Request $request, $id)
    {

        $mensagemRetorno = '';
        if ($id === 'undefined') {
            // Busca por outro usuário com a mesma Matricula, excluindo o próprio usuário
            $usuarioComMesmaMatricula = User::where('matricula', $request['matricula'])->first();
        } else {
            $meuUser = User::find($id);
            // Busca por outro usuário com a mesma Matricula, excluindo o próprio usuário
            $usuarioComMesmaMatricula = User::where('matricula', $request['matricula'])
                ->where('id', '<>', $id)
                ->first();
        }

        if ($usuarioComMesmaMatricula) {
            $mensagemRetorno = 'Matrícula já registrada por outro usuário.';
        }

        return $mensagemRetorno;
    }


    public function checarSenha(Request $request)
    {
        $senha = $request['password'];

        $user = User::find(Auth::user()->id);

        if (Hash::check($senha, $user->password)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function pesquisaMatricula(Request $request)
    {
        $user = User::where('matricula', $request['matricula'])->where('tipo', 'Aluno')->first();

        // primeiro eu tenho que verificar se existe a matricula, e em seguida verificar se existe uma  vigência nos cursos que eu possuo

        if ($user) {

            $possuiVinculo = UserAlunoVinculo::where('user_id', $user->id)->where('semestre_letivo', $this->emVigencia())->count();

            return $user->load('alunoVinculos');
        }

        return 'Matrícula não encontrada.';

    }

    public function emVigencia()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        $currentSemester = $currentMonth <= 6 ? 1 : 2;
        // Retorna o ano com os dois últimos dígitos e o semestre
        return substr($currentYear, 2) . '.' . $currentSemester;
    }


    // cria um novo usuário (ALUNO)
    public function autoCadastro(Request $request)
    {

        $user = User::create([
            'nome' => $request['nome'],
            'matricula' => $request['matricula'],
            'telefone' => $request['telefone'],
            'email' => $request['email'],
            'tipo' => 'Aluno',
            'reset' => 0,
            'password' => Hash::make($request['password'])
        ]);

        //estrutura Recebiada
        /*
         *
         * cursos é array ->
         * tem unidade em cursos
         * tem turno em cursos que é objeto e tem periodo_turmas (que é array) que possui qtd_turmas_por_periodo
         * tem periodo em cursos
         *
         */

        // Recebe um array de IDs de cursos e faz o código vinculo
        foreach ($request['cursos'] as $cursoId) {
            UserAlunoVinculo::create([
                'semestre_letivo' => $this->emVigencia(),
                'codigo_vinculo' => $this->montaCodigo($cursoId),
                'user_id' => $user->id
            ]);
        }

        return $user;
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
