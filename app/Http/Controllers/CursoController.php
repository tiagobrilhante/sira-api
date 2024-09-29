<?php

namespace App\Http\Controllers;

use App\Models\Curso;

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

}
