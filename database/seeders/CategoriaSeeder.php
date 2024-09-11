<?php

namespace Database\Seeders;

use App\Models\Om;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categorias')->insert([
            [
                'id' => 1,
                'nome' => 'Inserções espontâneas na mídia',
                'secao_id' => 8
            ],
            [
                'id' => 2,
                'nome' => 'Inserções espontâneas na mídia por operação',
                'secao_id' => 8
            ],
            [
                'id' => 3,
                'nome' => 'Seguidores das redes sociais do CMA',
                'secao_id' => 8
            ],
            [
                'id' => 4,
                'nome' => 'Publicações nas mídias do CMA',
                'secao_id' => 8
            ],
            [
                'id' => 5,
                'nome' => 'Média de visualizações das publicações do CMA',
                'secao_id' => 8
            ],
            [
                'id' => 6,
                'nome' => 'Média de curtidas/likes das publicações do CMA',
                'secao_id' => 8
            ],
            [
                'id' => 7,
                'nome' => 'Visitantes ao Zoo CIGS',
                'secao_id' => 8
            ],
            [
                'id' => 8,
                'nome' => 'Número de instituições visitantes ao Zoo CIGS',
                'secao_id' => 8
            ],
            [
                'id' => 9,
                'nome' => 'Efetivo - CMA - por PG',
                'secao_id' => 2
            ],
            [
                'id' => 10,
                'nome' => 'Efetivo - Guarnição',
                'secao_id' => 2
            ],
            [
                'id' => 11,
                'nome' => 'Efetivo - Brigada',
                'secao_id' => 2
            ],
            [
                'id' => 12,
                'nome' => 'Efetivo - Faixa de fronteira',
                'secao_id' => 2
            ],
            [
                'id' => 13,
                'nome' => 'Efetivo - PEF, DEF e Ba Op PEF',
                'secao_id' => 2
            ],
            [
                'id' => 14,
                'nome' => 'Efetivo - Segmento feminino - por PG',
                'secao_id' => 2
            ],
            [
                'id' => 15,
                'nome' => 'Efetivo - Indígenas Incorporados - por OM',
                'secao_id' => 2
            ],
            [
                'id' => 16,
                'nome' => 'Efetivo - Indígenas EP - por OM',
                'secao_id' => 2
            ],
            [
                'id' => 17,
                'nome' => 'Efetivo - PTTC - por PG',
                'secao_id' => 2
            ],
            [
                'id' => 18,
                'nome' => 'Efetivo - PTTC - por OM',
                'secao_id' => 2
            ],
            [
                'id' => 19,
                'nome' => 'Efetivo - Veteranos e Pensionistas - por OM',
                'secao_id' => 2
            ],
            [
                'id' => 20,
                'nome' => 'Efetivo - Veteranos e Pensionistas - por Situação',
                'secao_id' => 2
            ],
            [
                'id' => 21,
                'nome' => 'Efetivo - Reintegrados / Problemas ',
                'secao_id' => 2
            ],
            [
                'id' => 22,
                'nome' => 'Efetivo - Benefíciários do FuSEx - por GU',
                'secao_id' => 2
            ],
            [
                'id' => 23,
                'nome' => 'Efetivo - Benefíciários do FuSEx - por Tipo (Ativa - Veterano - Pensionista)',
                'secao_id' => 2
            ],
            [
                'id' => 24,
                'nome' => 'Efetivo - Desertores',
                'secao_id' => 2
            ],
            [
                'id' => 25,
                'nome' => 'Atendimentos - SVP',
                'secao_id' => 2
            ],
            [
                'id' => 26,
                'nome' => 'Atendimentos - PIR',
                'secao_id' => 2
            ],
            [
                'id' => 27,
                'nome' => 'CAC - Vinculados',
                'secao_id' => 3
            ],
            [
                'id' => 28,
                'nome' => 'SFPC - Nr Processos',
                'secao_id' => 3
            ],
            [
                'id' => 29,
                'nome' => 'SFPC - Nr Operações Desencadeadas',
                'secao_id' => 3
            ],
            [
                'id' => 31,
                'nome' => 'SFPC - Resultados de Operações',
                'secao_id' => 3
            ],
            [
                'id' => 32,
                'nome' => 'Inteligência - Nr de Conhecimentos Produzidos',
                'secao_id' => 3
            ],
            [
                'id' => 33,
                'nome' => 'Acidentes automobilísticos - Por G Cmdo / GU / Gpt E',
                'secao_id' => 3
            ],
            [
                'id' => 34,
                'nome' => 'COS - Efetivo Inscrito - Por Curso',
                'secao_id' => 4
            ],
            [
                'id' => 35,
                'nome' => 'COS - Efetivo Concludente - Por Curso',
                'secao_id' => 4
            ],
            [
                'id' => 36,
                'nome' => 'Nav Flu - Efetivo Inscrito - ONA / OSP',
                'secao_id' => 4
            ],
            [
                'id' => 37,
                'nome' => 'Nav Flu - Efetivo Concludente - ONA / OSP',
                'secao_id' => 4
            ],
            [
                'id' => 38,
                'nome' => 'Estágio de Área - Realizados',
                'secao_id' => 4
            ],
            [
                'id' => 39,
                'nome' => 'Estágio de Área - Inscritos / Concludentes',
                'secao_id' => 4
            ],
            [
                'id' => 40,
                'nome' => 'Estágio para Civis - Realizados',
                'secao_id' => 4
            ],
            [
                'id' => 41,
                'nome' => 'Estágio para Civis - Inscritos / Concludentes',
                'secao_id' => 4
            ],
            [
                'id' => 42,
                'nome' => 'CFC - Concludentes - Por QM',
                'secao_id' => 4
            ],
            [
                'id' => 43,
                'nome' => 'CFC - Concludentes - Por Gu',
                'secao_id' => 4
            ],
            [
                'id' => 44,
                'nome' => 'CFST - Concludentes - Por QM',
                'secao_id' => 4
            ],
            [
                'id' => 45,
                'nome' => 'CFST - Concludentes - Por Gu',
                'secao_id' => 4
            ],
            [
                'id' => 46,
                'nome' => 'Militares Incorporados no CMA - Por Tipo e Gu (OTT / STT / MFDV / CET)',
                'secao_id' => 4
            ],
            [
                'id' => 47,
                'nome' => 'Efetivo - Militares Estrangeiros adestrados pelas Eq móveis de Adestramento do CIGS',
                'secao_id' => 4
            ],
            [
                'id' => 48,
                'nome' => 'Acidentes no Sv / Instrução - Por G Cmdo / GU / Gpt E',
                'secao_id' => 4
            ],
            [
                'id' => 49,
                'nome' => 'Acidentes no Sv / Instrução - Por causa (DESABILITAR)',
                'secao_id' => 4
            ],
            [
                'id' => 50,
                'nome' => 'TAT - Por Menção - G Cmdo / GU / Gpt E',
                'secao_id' => 4
            ],
            [
                'id' => 51,
                'nome' => 'TAF - Por Menção - G Cmdo / GU / Gpt E',
                'secao_id' => 4
            ],
            [
                'id' => 52,
                'nome' => 'Distância percorrida em atividades logísticas - por Modal',
                'secao_id' => 5
            ],
            [
                'id' => 53,
                'nome' => 'Distância percorrida em atividades logísticas - por Om',
                'secao_id' => 5
            ],
            [
                'id' => 54,
                'nome' => 'Total de dias em operações logísticas - por Modal',
                'secao_id' => 5
            ],
            [
                'id' => 55,
                'nome' => 'Total de dias em operações logísticas - por Om',
                'secao_id' => 5
            ],
            [
                'id' => 56,
                'nome' => 'Total de Carga Transportada - por Modal',
                'secao_id' => 5
            ],
            [
                'id' => 57,
                'nome' => 'Total de Carga Transportada - por Om',
                'secao_id' => 5
            ],
            [
                'id' => 58,
                'nome' => 'Saúde - Total de Atendimento de saúde nas OM do CMA - por G Cmdo / GU e tipo (Indígenas / Civis / Militares / Dependentes / Estrangeiros)',
                'secao_id' => 5
            ],
            [
                'id' => 59,
                'nome' => 'ACISO - Total realizadas - Por G Cmdo / GU / Gpr E',
                'secao_id' => 5
            ],
            [
                'id' => 60,
                'nome' => 'ACISO - Atendimentos de saúde realizados - Por G Cmdo / GU / Gpr E',
                'secao_id' => 5
            ],
            [
                'id' => 61,
                'nome' => 'ACISO - Pessoas Beneficiadas - Por G Cmdo / GU / Gpr E',
                'secao_id' => 5
            ],
            [
                'id' => 62,
                'nome' => 'Saúde - Produção das Organizações militares de Saúde - Por OMS',
                'secao_id' => 5
            ],
            [
                'id' => 63,
                'nome' => 'Saúde - Número de Encaminhamentos para OCS / PSA - Por Guarnição',
                'secao_id' => 5
            ],
            [
                'id' => 64,
                'nome' => 'PNR - Número de PNR - Por Guarnição / Disponibilidade',
                'secao_id' => 5
            ],
            [
                'id' => 65,
                'nome' => 'PNR - Número de PNR - Por Guarnição / PG',
                'secao_id' => 5
            ],
            [
                'id' => 66,
                'nome' => 'PNR - Número de PNR - Por Guarnição / Tipo',
                'secao_id' => 5
            ],
            [
                'id' => 67,
                'nome' => 'Viaturas - Número de Viaturas - Por Tipo / Gu',
                'secao_id' => 5
            ],
            [
                'id' => 68,
                'nome' => 'Viaturas - Número de Viaturas - Por Disponibilidade / Gu',
                'secao_id' => 5
            ],
            [
                'id' => 69,
                'nome' => 'Embarcações - Número de Embarcações - Por Tipo / Gu',
                'secao_id' => 5
            ],
            [
                'id' => 70,
                'nome' => 'Embarcações - Número de Embarcações - Por Disponibilidade / Gu',
                'secao_id' => 5
            ],
            [
                'id' => 71,
                'nome' => 'SISCOFIS - Ranking - Por C Mil A',
                'secao_id' => 5
            ],
            [
                'id' => 72,
                'nome' => 'Soldado Cidadão - Cursos Oferecidos - Por Gu',
                'secao_id' => 6
            ],
            [
                'id' => 73,
                'nome' => 'Soldado Cidadão - Vagas Oferecidas / Concludentes - Por Gu',
                'secao_id' => 6
            ],
            [
                'id' => 74,
                'nome' => 'PROFESP - Beneficiados - Por OM',
                'secao_id' => 6
            ],
            [
                'id' => 75,
                'nome' => 'Obras de cooperação - Quantidade',
                'secao_id' => 6
            ],
            [
                'id' => 76,
                'nome' => 'Obras de cooperação - Recursos Empregados',
                'secao_id' => 6
            ],
            [
                'id' => 77,
                'nome' => 'Obras militares - Quantidade - Por Execução (Direta / Indireta)',
                'secao_id' => 6
            ],
            [
                'id' => 78,
                'nome' => 'Obras militares - Recursos Empregados - Por Execução (Direta / Indireta)',
                'secao_id' => 6
            ],
            [
                'id' => 79,
                'nome' => 'Espaço Cultural - Quantidades de Visitas',
                'secao_id' => 9
            ],
            [
                'id' => 80,
                'nome' => 'Operações - Média por dia',
                'secao_id' => 10
            ],
            [
                'id' => 81,
                'nome' => 'Operações - Média militares por dia',
                'secao_id' => 10
            ],
            [
                'id' => 82,
                'nome' => 'Operações - Número por tipo (Singulares / Conjuntas)',
                'secao_id' => 10
            ],
            [
                'id' => 83,
                'nome' => 'Operações - Reconhecimento de Fronteira',
                'secao_id' => 10
            ],
            [
                'id' => 84,
                'nome' => 'Operações - Total de Militares Empregados',
                'secao_id' => 10
            ],
            [
                'id' => 85,
                'nome' => 'Operações - Quantidade de Apreensões - Por Tipo',
                'secao_id' => 10
            ],
            [
                'id' => 86,
                'nome' => 'Operações - Destruições - Por Tipo',
                'secao_id' => 10
            ],
            [
                'id' => 87,
                'nome' => 'Inspeções de Viaturas - Total por Tipo (Terrestre / Fluvial)',
                'secao_id' => 10
            ],
            [
                'id' => 88,
                'nome' => 'Patrulhas - Total por Tipo (Terrestre / Fluvial)',
                'secao_id' => 10
            ],
            [
                'id' => 89,
                'nome' => 'Distâncias percorridas em operações - Total por Tipo (Terrestre / Fluvial / Aéreo)',
                'secao_id' => 10
            ],
            [
                'id' => 90,
                'nome' => 'Hora Voo em operações - Quantidade',
                'secao_id' => 10
            ],
            [
                'id' => 91,
                'nome' => 'Detenções em operações - Quantidade',
                'secao_id' => 10
            ],
            [
                'id' => 92,
                'nome' => 'Recursos em Operações - Por Operação / Bda',
                'secao_id' => 10
            ],
        ]);
    }
}
