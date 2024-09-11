<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{

    //lista os usuários
    public function index()
    {
        return User::all()->load('secao', 'posto_grad');
    }

    // retorna se o usuário pode ou não usar o cpf ao se cadastrar / editar
    // retorna se o usuário pode ou não usar o cpf ao se cadastrar / editar
    public function cpfExist(Request $request)
    {
        $id_usuario = $request['id'];
        if ($id_usuario === null) {
            $teste = User::where('cpf', $request['cpf'])->count();
        } else {
            $teste = User::where('cpf', $request['cpf'])->where('id', '!=', $id_usuario)->count();
        }
        return $teste;
    }

    //limpa os . e - de um cpf para resetar senha
    function limpaCPF_CNPJ($valor)
    {
        $valor = trim($valor);
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", "", $valor);
        $valor = str_replace("-", "", $valor);
        $valor = str_replace("/", "", $valor);
        return $valor;
    }

    // altera a senha de um usuário resetado
    public function alteraSenhaResetada(Request $request)
    {
        $user = User::find($request['id']);
        $user->password = Hash::make($request['password']);
        $user->reset = 0;
        $user->save();
    }

    // reseta a senha de um usuário
    public function resetaSenha(Request $request)
    {
        $user = User::find($request['id']);
        $user->password = Hash::make($this->limpaCPF_CNPJ($user->cpf));
        $user->reset = 1;
        $user->save();

        return $user->load('secao', 'posto_grad');
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
            'cpf' => $request['cpf'],
            'nome' => $request['nome'],
            'nome_guerra' => $request['nome_guerra'],
            'tipo' => $request['tipo'],
            'secao_id' => $request['secao_id'],
            'posto_grad_id' => $request['posto_grad_id'],
            'password' => Hash::make($this->limpaCPF_CNPJ($request['cpf'])),
            'reset' => 1
        ]);

        return $user->load('secao', 'posto_grad');
    }

    // altera um usuário
    public function update(int $id, Request $request)
    {

        $usuario = User::find($id);

        if (is_null($usuario)) {
            return response()->json([
                'erro' => 'Recurso não encontrado'
            ], 404);

        }
        $usuario->fill($request->all());
        $usuario->save();
        return $usuario->load('secao', 'posto_grad');

    }

    // remove um usuário
    public function destroy($id)
    {

        $usuario = User::destroy($id);

        if ($usuario === 0) {

            return response()->json([
                'erro' => 'Recurso não encontrado'
            ], 404);

        } else {
            return response()->json('', 204);
        }

    }

    public function checaCPFExist(Request $request, $id)
    {

        $mensagemRetorno = '';
        if ($id === 'undefined') {
            // Busca por outro usuário com o mesmo CPF, excluindo o próprio usuário
            $usuarioComMesmoCPF = User::where('cpf',$request['cpf'])->first();
        } else {
            $meuUser = User::find($id);
            // Busca por outro usuário com o mesmo CPF, excluindo o próprio usuário
            $usuarioComMesmoCPF = User::where('cpf',$request['cpf'])
                ->where('id', '<>', $id)
                ->first();
        }

        if ($usuarioComMesmoCPF) {
            $mensagemRetorno = 'CPF já registrado por outro usuário.';
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
