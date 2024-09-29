<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserCurso;
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
}
