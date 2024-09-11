<?php


namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TokenController extends Controller
{

    public function gerarToken(Request $request)
    {

        // valida os dados
        $this->validate($request,[
            'cpf'=> 'required',
            'password'=>'required'
        ]);

        $user = User::where('cpf', $request['cpf'])->first()->load('secao', 'posto_grad');

        // caso senha errada
        if (is_null($user) || !Hash::check($request->password, $user->password)) {

           return response()->json('Usuário ou senha inválidos', 401);

        }

        // payload
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'cpf' => $user->cpf, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            //'exp' => time() + 60 * 60 * 60 * 24 // Expiration time
            'exp' => time() + 43200 // Expiration time (12 horas)
            // 'exp'=> 1

        ];

       $token =  JWT::encode($payload, env('JWT_KEY'));

        return [
            'access_token'=>$token,
            'user'=> $user
        ];

    }

}
