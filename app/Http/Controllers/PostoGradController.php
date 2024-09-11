<?php

namespace App\Http\Controllers;

use App\Models\PostoGrad;

class PostoGradController extends Controller
{

    //lista os usuários
    public function index()
    {
        return PostoGrad::orderBy('antiguidade', 'ASC')->get();
    }

}
