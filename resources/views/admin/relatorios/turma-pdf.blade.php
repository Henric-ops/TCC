<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Acompanhamento da Turma</title>

    <style>
        @page {
            margin: 35px 45px 50px 45px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
        }

        .header {
            text-align: center;
            padding-bottom: 18px;
            border-bottom: 2px solid #426e83;
            margin-bottom: 25px;
        }

        .escola {
            font-size: 15px;
            font-weight: bold;
            color: #263c48;
            margin-bottom: 5px;
        }

        .sistema {
            font-size: 9px;
            color: #888;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 17px;
            color: #263c48;
            margin: 0;
        }

        .subtitulo {
            font-size: 10px;
            color: #777;
            margin-top: 5px;
        }

        .secao {
            margin-bottom: 28px;
            page-break-inside: avoid;
        }

        .secao-titulo {
            font-size: 12px;
            font-weight: bold;
            color: #263c48;
            padding-bottom: 6px;
            margin-bottom: 14px;
            border-bottom: 1px solid #ddd;
        }

        .identificacao {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .identificacao td {
            width: 50%;
            padding: 5px 12px 5px 0;
            vertical-align: top;
        }

        .label {
            display: block;
            font-size: 9px;
            color: #777;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .valor {
            font-size: 11px;
            font-weight: bold;
            color: #333;
        }

        .indicadores {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-left: -8px;
            margin-bottom: 16px;
        }

        .indicadores td {
            width: 50%;
            padding: 0;
            vertical-align: top;
        }

        .indicador {
            background: #f4f7f8;
            border: 1px solid #e5ebee;
            padding: 14px 10px;
            text-align: center;
        }

        .indicador-numero {
            font-size: 21px;
            font-weight: bold;
            color: #263c48;
            margin-bottom: 5px;
        }

        .indicador-label {
            font-size: 9px;
            color: #687780;
        }

        .barra-titulo {
            color: #777;
            font-size: 9px;
            margin-bottom: 7px;
        }

        .barra-fundo {
            width: 100%;
            height: 7px;
            background: #e9edef;
        }

        .barra-progresso {
            height: 7px;
            background: #588e85;
        }

        .tabela-alunos {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .tabela-alunos thead {
            display: table-header-group;
        }

        .tabela-alunos tr {
            page-break-inside: avoid;
        }

        .tabela-alunos th {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #63717a;
            background: #f4f7f8;
            padding: 12px 10px;
            border-bottom: 1px solid #dfe5e8;
            text-align: left;
        }

        .tabela-alunos td {
            padding: 12px 10px;
            border-bottom: 1px solid #e7ebed;
            font-size: 10px;
        }

        .coluna-aluno {
            width: 42%;
        }

        .nome-aluno {
            font-weight: bold;
            color: #333;
            overflow-wrap: break-word;
        }

        .tabela-alunos .numero {
            text-align: right;
        }

        .destaque {
            font-weight: bold;
            color: #426e83;
        }

        .vazio {
            padding: 20px;
            text-align: center;
            color: #888;
        }

        .nota {
            font-size: 9px;
            color: #888;
            margin-top: 12px;
        }

        .rodape {
            position: fixed;
            bottom: -25px;
            left: 0;
            right: 0;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 9px;
            color: #888;
        }

        .rodape table {
            width: 100%;
            border-collapse: collapse;
        }

        .rodape-direita {
            text-align: right;
        }
    </style>
</head>

<body>


    <div class="header">
        <div class="escola">
            {{ $turma->escola->nome ?? 'Instituição de Ensino' }}
        </div>

        <div class="sistema">
            LumiKids · Gestão Escolar
        </div>

        <h1>Relatório de Acompanhamento da Turma</h1>

        <div class="subtitulo">
            Educação Infantil
        </div>
    </div>



    <div class="secao-titulo">
        Identificação da turma
    </div>

    <table class="identificacao">
        <tr>
            <td>
                <span class="label">Turma</span>
                <span class="valor">
                    {{ $turma->nome }}
                </span>
            </td>

            <td>
                <span class="label">Período analisado</span>
                <span class="valor">
                    {{ \Carbon\Carbon::parse($inicio)->format('d/m/Y') }}
                    a
                    {{ \Carbon\Carbon::parse($fim)->format('d/m/Y') }}
                </span>
            </td>
        </tr>
    </table>



    <div class="secao">
        <div class="secao-titulo">
            Resumo geral da turma
        </div>

        <table class="indicadores">
            <tr>
                <td>
                    <div class="indicador">
                        <div class="indicador-numero">
                            {{ $linhas->count() }}
                        </div>
                        <div class="indicador-label">
                            Alunos vinculados
                        </div>
                    </div>
                </td>

                <td>
                    <div class="indicador">
                        <div class="indicador-numero">
                            {{ $mediaPresencaTurma }}%
                        </div>
                        <div class="indicador-label">
                            Presença média
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="barra-titulo">
            Percentual médio de presença
        </div>

        <div class="barra-fundo">
            <div class="barra-progresso" style="width: {{ max(0, min(100, $mediaPresencaTurma)) }}%;">
            </div>
        </div>
    </div>


    <div class="secao-titulo">
        Acompanhamento dos alunos
    </div>

    <table class="tabela-alunos">
        <thead>
            <tr>
                <th class="coluna-aluno">Aluno</th>
                <th class="numero">Presença</th>
                <th class="numero">Faltas</th>
                <th class="numero">Registros diários</th>
            </tr>
        </thead>

        <tbody>
            @forelse($linhas as $linha)
                <tr>
                    <td class="nome-aluno">
                        {{ $linha['aluno']->nome }}
                    </td>

                    <td class="numero destaque">
                        {{ $linha['percentualPresenca'] }}%
                    </td>

                    <td class="numero">
                        {{ $linha['faltas'] }}
                    </td>

                    <td class="numero">
                        {{ $linha['totalRegistros'] }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="vazio" colspan="4">
                        Essa turma não possui alunos vinculados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="nota">
        Os percentuais de presença são calculados com base
        nos registros de frequência disponíveis no período selecionado.
    </div>



    <div class="rodape">
        <table>
            <tr>
                <td>LumiKids · Documento escolar</td>

                <td class="rodape-direita">
                    Emitido em {{ now()->format('d/m/Y') }}
                </td>
            </tr>
        </table>
    </div>

</body>

</html>