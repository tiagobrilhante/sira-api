<?php

namespace App\Http\Controllers;


use App\Models\TurnoParametro;

class TurnoParametroController extends Controller
{
    public function index()
    {

        return TurnoParametro::all();

    }

}
