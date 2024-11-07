<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Unidade;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UnidadeController extends Controller
{
    public function index()
    {
       return Unidade::all()->load('cursos.semestresLetivos.turnos');
    }

    // retorna uma lista simples de unidades para montagem de menus
    public function listaSimples()
    {
        return Unidade::all();
    }

    public function vigente()
    {
        // Obter o semestre vigente usando a função emVigencia()
        $semestreVigente = $this->emVigencia();

        // Inicializar o array de unidades
        $unidades = [];

        // Buscar todos os cursos com seus respectivos semestres (se existirem)
        $cursos = Curso::with(['unidade', 'semestresLetivos'])->get();

        // Iterar pelos cursos e organizar por unidades
        foreach ($cursos as $curso) {
            $unidade = $curso->unidade;

            // Verificar se a unidade já foi adicionada ao array
            if (!isset($unidades[$unidade->id])) {
                // Adicionar a unidade ao array se ainda não existir
                $unidades[$unidade->id] = [
                    'id' => $unidade->id,
                    'nome' => $unidade->nome,
                    'prefixo' => $unidade->prefixo,
                    'cursos' => []
                ];
            }

            // Filtrar os semestres do curso
            $semestreAtual = null;
            $semestresAnteriores = [];

            foreach ($curso->semestresLetivos as $semestre) {
                if ($semestre->codigo === $semestreVigente) {
                    $semestreAtual = $semestre;
                } elseif ($semestre->codigo < $semestreVigente) {
                    $semestresAnteriores[] = $semestre;
                }
            }

            // Se o curso tem semestre vigente, adicioná-lo uma vez à lista de cursos da unidade
            if ($semestreAtual) {
                $unidades[$unidade->id]['cursos'][] = [
                    'id' => $curso->id,
                    'nome' => $curso->nome,
                    'codigo' => $curso->codigo,
                    'qtd_periodos_possiveis' => $curso->qtd_periodos_possiveis,
                    'semestre_codigo' => $semestreAtual->codigo
                ];
            } elseif (!empty($semestresAnteriores)) {
                // Se não houver semestre vigente, mas houver semestres anteriores, adicionar o curso com o semestre mais recente
                $semestreMaisRecente = collect($semestresAnteriores)->sortByDesc('codigo')->first();
                $unidades[$unidade->id]['cursos'][] = [
                    'id' => $curso->id,
                    'nome' => $curso->nome,
                    'codigo' => $curso->codigo,
                    'qtd_periodos_possiveis' => $curso->qtd_periodos_possiveis,
                    'semestre_codigo' => $semestreMaisRecente->codigo
                ];
            } else {
                // Se não houver nenhum semestre associado ao curso, adicioná-lo sem semestre
                $unidades[$unidade->id]['cursos'][] = [
                    'id' => $curso->id,
                    'nome' => $curso->nome,
                    'codigo' => $curso->codigo,
                    'qtd_periodos_possiveis' => $curso->qtd_periodos_possiveis,
                    'semestre_codigo' => null // Sem semestre associado
                ];
            }
        }

        // Adicionar as unidades que não possuem cursos relacionados
        $unidadesSemCursos = Unidade::whereDoesntHave('cursos')->get();
        foreach ($unidadesSemCursos as $unidade) {
            if (!isset($unidades[$unidade->id])) {
                $unidades[$unidade->id] = [
                    'id' => $unidade->id,
                    'nome' => $unidade->nome,
                    'prefixo' => $unidade->prefixo,
                    'cursos' => [] // Nenhum curso associado
                ];
            }
        }

        // Retornar a resposta como JSON
        return response()->json(array_values($unidades));
    }

    public function emVigencia()
    {

        // Obtém o ano corrente
        $currentYear = Carbon::now()->year;

        // Obtém o mês corrente
        $currentMonth = Carbon::now()->month;

        // Define o semestre com base no mês
        $currentSemester = $currentMonth <= 6 ? 1 : 2;

        // Retorna o ano com os dois últimos dígitos e o semestre
        return substr($currentYear, 2) . '.' . $currentSemester;


    }

    public function store(Request $request)
    {
        $unidade = Unidade::create([
            'nome' => request('nome'),
            'prefixo' => request('prefixo'),
        ]);

        foreach (request('cursos') as $curso) {
            Curso::create([
                'nome' => $curso['nome'],
                'codigo' => $curso['codigo'],
                'qtd_periodos_possiveis' => $curso['qtd_periodos_possiveis'],
                'unidade_id' => $unidade->id
            ]);
        }
        return 'Ok';
    }

    public function destroy($id)
    {

        $unidade = Unidade::destroy($id);

        if ($unidade === 0) {

            return response()->json([
                'erro' => 'Recurso não encontrado'
            ], 404);

        } else {
            return response()->json('', 204);
        }

    }

    public function update(int $id, Request $request)
    {
        $unidade = Unidade::find($id);

        if (is_null($unidade)) {
            return response()->json([
                'erro' => 'Recurso não encontrado'
            ], 404);
        }

        // Atualiza os dados da unidade
        $unidade->nome = $request['nome'];
        $unidade->prefixo = $request['prefixo'];
        $unidade->save();

        // Itera sobre os cursos fornecidos
        foreach ($request->input('cursos') as $cursoData) {
            if (isset($cursoData['id'])) {
                // Se o curso tem ID, tenta encontrar e atualizar
                $curso = Curso::find($cursoData['id']);
                if ($curso) {
                    // Atualiza os dados do curso existente
                    $curso->nome = $cursoData['nome'];
                    $curso->codigo = $cursoData['codigo'];
                    $curso->qtd_periodos_possiveis = $cursoData['qtd_periodos_possiveis'];
                    $curso->save();
                }
            } else {
                // Se o curso não tem ID, cria um novo curso
                $novoCurso = new Curso();
                $novoCurso->unidade_id = $unidade->id;
                $novoCurso->nome = $cursoData['nome'];
                $novoCurso->codigo = $cursoData['codigo'];
                $novoCurso->qtd_periodos_possiveis = $cursoData['qtd_periodos_possiveis'];
                $novoCurso->save();
            }
        }

        // Retorna a unidade com os cursos atualizados
        return response()->json($unidade->load('cursos'), 200);
    }


    public function checaCodCursos(Request $request)
    {
        $cursos = $request->input('cursos');

        $arrayCursosCod = [];
        $itensNovos = [];

        foreach ($cursos as $curso) {
            if (isset($curso['id'])) {
                $arrayCursosCod[] = $curso['codigo'];
            } else {
                $itensNovos[] = $curso['codigo'];
            }
        }

        // Check for duplicates
        $duplicates = array_intersect($arrayCursosCod, $itensNovos);

        if (!empty($duplicates)) {
            return 'duplicado';
        } else {
            return response()->json([
                'message' => 'Ok'
            ], 200);
        }
    }

}
