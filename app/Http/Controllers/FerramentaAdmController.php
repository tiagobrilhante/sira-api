<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Indicador;
use App\Models\IndicadorValor;
use App\Models\Secao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class FerramentaAdmController extends Controller
{

    //lista os usuários
    public function analisaIntegridade(Request $request)
    {
        $ano_inicial = (int)$request->input('ano_inicio');
        $ano_final = (int)$request->input('ano_fim');

        // Obtém todos os registros dentro do intervalo de anos especificado
        $indicadorValores = IndicadorValor::whereBetween('ano', [$ano_inicial, $ano_final])->get()->load('indicador.categoria.secao');

        // Agrupa os registros por indicador_id, mes e ano
        $grupos = $indicadorValores->groupBy(function ($item) {
            return $item->indicador_id . '-' . $item->mes . '-' . $item->ano;
        });

        $arrayResultado = [];
        foreach ($grupos as $grupo) {
            // Se o grupo tem mais de um registro, significa que há duplicatas
            if ($grupo->count() > 1) {
                // Ordena os registros do grupo por created_at de forma descendente
                // Assim, o primeiro registro será o mais recente
                $ordenadosPorData = $grupo->sortByDesc('created_at');

                // Remove o primeiro registro (o mais recente) da coleção para exclusão
                $ordenadosPorData->shift();

                // Exclui todos os registros restantes no grupo (duplicatas mais antigas)
                foreach ($ordenadosPorData as $item) {
                    $arrayResultado[] = $item;
                }
            }
        }

        return $arrayResultado;
    }

    public function corretorIndicadores(Request $request)
    {
        $ano_inicial = (int)$request->input('ano_inicio');
        $ano_final = (int)$request->input('ano_fim');

        // Obtém todos os registros do ano de 2024
        $indicadorValores = IndicadorValor::whereBetween('ano', [$ano_inicial, $ano_final])->get();

        // Agrupa os registros por indicador_id, mes e ano
        $grupos = $indicadorValores->groupBy(function ($item) {
            return $item->indicador_id . '-' . $item->mes . '-' . $item->ano;
        });

        foreach ($grupos as $grupo) {
            // Se o grupo tem mais de um registro, significa que há duplicatas
            if ($grupo->count() > 1) {
                // Ordena os registros do grupo por created_at de forma descendente
                // Assim, o primeiro registro será o mais recente
                $ordenadosPorData = $grupo->sortByDesc('created_at');

                // Remove o primeiro registro (o mais recente) da coleção para exclusão
                $ordenadosPorData->shift();

                // Exclui todos os registros restantes no grupo (duplicatas mais antigas)
                foreach ($ordenadosPorData as $item) {
                    $item->delete();
                }
            }
        }
    }

    public function removeRegistroDuplicado($id)
    {
        $indicadorValor = IndicadorValor::find($id);
        if ($indicadorValor) {
            $indicadorValor->delete();
            return response()->json(['message' => 'Registro excluído com sucesso!']);
        } else {
            return response()->json(['error' => 'Registro não encontrado'], 404);
        }
    }

    public function relatorioPendencias(Request $request)
    {


        $ano = (int)$request->input('ano');
        $mes_limite = (int)$this->retornaMes($request->input('mes_limite'));
        $secao = $request->input('secao');
        $tipo = $request->input('tipo');

       // return [$mes_limite, $ano, $secao, $tipo, Auth::user()->tipo, Auth::user()->secao_id];

        if (Auth::user()->tipo === 'Administrador' || Auth::user()->tipo === 'Auditor') {
            if ($secao === 'todos') {
                if ($tipo === 'pesquisa') {
                    $secoes = Secao::whereHas('categoria', function ($query) {
                        $query->where('ativo', true);
                    })
                        ->with(['categoria' => function ($query) {
                            $query->where('ativo', true);
                        }, 'categoria.indicadores' => function ($query) use ($ano, $mes_limite) {
                            $query->with(['indicadorValor' => function ($subQuery) use ($ano, $mes_limite) {
                                $subQuery->where('ano', $ano)->whereBetween('mes', [1, $mes_limite]);
                            }]);
                        }])
                        ->get();
                }
                else {
                    $mes_limite = (int)date('n') -1;
                    $secoes = Secao::whereHas('categoria', function ($query) {
                        $query->where('ativo', true);
                    })
                        ->with(['categoria' => function ($query) {
                            $query->where('ativo', true);
                        }, 'categoria.indicadores' => function ($query) use ($ano, $mes_limite) {
                            $query->with(['indicadorValor' => function ($subQuery) use ($ano, $mes_limite) {
                                $subQuery->where('ano', $ano)->whereBetween('mes', [1, $mes_limite]);
                            }]);
                        }])
                        ->get();
                }
            }
            else {
                $mes_limite = (int)date('n')-1;
                $secoes = Secao::whereHas('categoria', function ($query) {
                    $query->where('ativo', true);
                })
                    ->with(['categoria' => function ($query) {
                        $query->where('ativo', true);
                    }, 'categoria.indicadores' => function ($query) use ($ano, $mes_limite) {
                        $query->with(['indicadorValor' => function ($subQuery) use ($ano, $mes_limite) {
                            $subQuery->where('ano', $ano)->whereBetween('mes', [1, $mes_limite]);
                        }]);
                    }])->where('id', $secao)
                    ->get();
            }
        } else {
            $mes_limite = (int)date('n')-1;
            $secoes = Secao::whereHas('categoria', function ($query) {
                $query->where('ativo', true);
            })
                ->with(['categoria' => function ($query) {
                    $query->where('ativo', true);
                }, 'categoria.indicadores' => function ($query) use ($ano, $mes_limite) {
                    $query->with(['indicadorValor' => function ($subQuery) use ($ano, $mes_limite) {
                        $subQuery->where('ano', $ano)->whereBetween('mes', [1, $mes_limite]);
                    }]);
                }])->where('id', Auth::user()->secao_id)
                ->get();
        }

        $secoesPendentes = $secoes->map(function ($secao) use ($ano, $mes_limite) {
            $secaoPendencias = [];
            foreach ($secao->categoria as $categoria) {
                $categoriaPendencias = [];
                foreach ($categoria->indicadores as $indicador) {
                    $mesesComValores = $indicador->indicadorValor->where('ano', $ano)->pluck('mes')->toArray();
                    $mesesPendentes = [];
                    for ($mes = 1; $mes <= $mes_limite; $mes++) {
                        if (!in_array($mes, $mesesComValores)) {
                            $mesesPendentes[] = $mes;
                        }
                    }
                    if (!empty($mesesPendentes)) {
                        $categoriaPendencias[] = [
                            'indicador' => $indicador->nome, // Ajuste conforme o atributo do indicador
                            'meses_pendentes' => $mesesPendentes,
                        ];
                    }
                }
                if (!empty($categoriaPendencias)) {
                    $secaoPendencias[] = [
                        'categoria' => $categoria->nome, // Ajuste conforme o atributo da categoria
                        'indicadores_pendentes' => $categoriaPendencias,
                    ];
                }
            }
            if (!empty($secaoPendencias)) {
                return [
                    'secao' => $secao->sigla . ' ( ' . $secao->nome . ' )', // Ajuste conforme o atributo da seção
                    'categorias_pendentes' => $secaoPendencias,
                ];
            }
            return null;
        })->filter()->values();

        return response()->json($secoesPendentes);
    }


    private function retornaMes($mes)
    {

        // Array associativo que mapeia o nome dos meses para seus respectivos números
        $meses = [
            'Janeiro' => 1,
            'Fevereiro' => 2,
            'Março' => 3,
            'Abril' => 4,
            'Maio' => 5,
            'Junho' => 6,
            'Julho' => 7,
            'Agosto' => 8,
            'Setembro' => 9,
            'Outubro' => 10,
            'Novembro' => 11,
            'Dezembro' => 12
        ];

        // Retorna o número correspondente ao mês
        // Se o mês não for encontrado no array, retorna null
        return $meses[$mes] ?? null;
    }

    public function pegaIndSemCat()
    {
        $categorias = Categoria::all();

        $array_id_categoria = [];
        foreach ($categorias as $categoria) {
            $array_id_categoria[] = $categoria->id;
        }

        $deletedCategories = Categoria::onlyTrashed()->get();

        $indicadores = Indicador::whereNotIn('categoria_id', $array_id_categoria)->get()->load('indicadorValor');

        return [$indicadores, $deletedCategories];

    }
}
