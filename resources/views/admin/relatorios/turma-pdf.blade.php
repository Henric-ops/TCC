<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Acompanhamento da Turma</title>

    <style>
        @page {
            margin: 70px 50px 70px 50px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            line-height: 1.5;
            color: #1f2933;
        }

        .topo-fixo {
            position: fixed;
            top: -40px;
            left: 0;
            right: 0;
            padding-bottom: 6px;
            border-bottom: 1px solid #cfd4d9;
            font-size: 7px;
            font-weight: bold;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #6b7785;
        }

        .faixa {
            width: 100%;
            border-collapse: collapse;
            margin-top: 22px;
            border-bottom: 2px solid #111;
        }

        .faixa td {
            padding: 0 0 8px 0;
            vertical-align: bottom;
        }

        .faixa-escola {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            color: #111;
        }

        .faixa-sistema {
            text-align: right;
            font-size: 9px;
            text-transform: uppercase;
            color: #426e83;
        }

        .titulo {
            width: 55%;
            margin: 28px 0 10px 0;
            font-size: 20px;
            line-height: 1.2;
            font-weight: bold;
            color: #111;
        }

        .periodo {
            padding-bottom: 14px;
            margin-bottom: 18px;
            border-bottom: 1px solid #cfd4d9;
            font-size: 9px;
            color: #6b7785;
        }

        .identificacao {
            border-collapse: collapse;
            margin-bottom: 24px;
        }

        .identificacao td {
            padding: 3px 0;
            font-size: 9px;
        }

        .identificacao .rotulo {
            width: 85px;
            color: #6b7785;
        }

        .identificacao .valor {
            font-weight: bold;
            color: #111;
        }

        .secao-titulo {
            font-size: 12px;
            font-weight: bold;
            color: #111;
            margin-bottom: 12px;
        }

        .sintese {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 26px;
        }

        .sintese td {
            width: 50%;
            padding: 0;
            font-size: 9px;
            color: #333;
        }

        .quadro-titulo {
            font-size: 9px;
            font-weight: bold;
            color: #111;
            margin-bottom: 8px;
        }

        .tabela-alunos {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border-top: 1px solid #111;
            border-bottom: 1px solid #111;
        }

        .tabela-alunos thead {
            display: table-header-group;
        }

        .tabela-alunos tr {
            page-break-inside: avoid;
        }

        .tabela-alunos th {
            padding: 10px 8px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
            text-align: left;
            color: #444;
            border-bottom: 1px solid #111;
        }

        .tabela-alunos td {
            padding: 10px 8px;
            font-size: 9px;
            color: #1f2933;
            border-bottom: 1px solid #e3e7ea;
        }

        .tabela-alunos tbody tr:last-child td {
            border-bottom: none;
        }

        .col-aluno {
            width: 40%;
        }

        .col-presenca {
            width: 18%;
        }

        .col-faltas {
            width: 16%;
        }

        .col-registros {
            width: 26%;
        }

        .direita {
            text-align: right !important;
        }

        .vazio {
            padding: 20px;
            text-align: center;
            color: #888;
        }

        .nota {
            margin-top: 18px;
            font-size: 8px;
            color: #6b7785;
        }

        .nota .nota-destaque {
            color: #426e83;
        }

        .rodape {
            position: fixed;
            bottom: -40px;
            left: 0;
            right: 0;
            border-top: 1px solid #cfd4d9;
            padding-top: 8px;
            font-size: 7px;
            color: #6b7785;
        }

        .rodape table {
            width: 100%;
            border-collapse: collapse;
        }

        .rodape td {
            padding: 0;
        }

        .rodape-direita {
            text-align: right;
        }

        .pagina:before {
            content: counter(page);
        }

        .total-paginas:before {
            content: counter(pages);
        }
    </style>
</head>

<body>
    <div class="rodape">
        <table>
            <tr>
                <td class="rodape-direita">
                    Emitido em {{ now()->format('d/m/Y') }}
                    &nbsp;&nbsp;
                </td>
            </tr>
        </table>
    </div>

    <table class="faixa">
        <tr>
            <td class="faixa-escola">
                {{ $turma->escola->nome ?? 'Instituição de Ensino' }}
            </td>
        </tr>
    </table>

    <div class="titulo">
        Relatório de Acompanhamento da Turma
    </div>

    <div class="periodo">
        Relatório referente ao período de
        {{ \Carbon\Carbon::parse($inicio)->format('d/m/Y') }}
        a
        {{ \Carbon\Carbon::parse($fim)->format('d/m/Y') }}.
    </div>

    <table class="identificacao">
        <tr>
            <td class="rotulo">Instituição:</td>
            <td class="valor">{{ $turma->escola->nome ?? 'Instituição de Ensino' }}</td>
        </tr>
        <tr>
            <td class="rotulo">Turma:</td>
            <td class="valor">{{ $turma->nome }}</td>
        </tr>
    </table>

    <div class="secao-titulo">Síntese dos registros</div>

    <table class="sintese">
        <tr>
            <td>{{ str_pad($linhas->count(), 2, '0', STR_PAD_LEFT) }} alunos vinculados</td>
            <td>{{ $mediaPresencaTurma }}% de presença média</td>
        </tr>
    </table>

    <div class="quadro-titulo">Quadro 1 — Acompanhamento individual</div>

    <table class="tabela-alunos">
        <thead>
            <tr>
                <th class="col-aluno">Aluno</th>
                <th class="col-presenca">Presença</th>
                <th class="col-faltas direita">Faltas</th>
                <th class="col-registros direita">Registros diários</th>
            </tr>
        </thead>

        <tbody>
            @forelse($linhas as $linha)
                <tr>
                    <td>{{ $linha['aluno']->nome }}</td>
                    <td>{{ $linha['percentualPresenca'] }}%</td>
                    <td class="direita">{{ $linha['faltas'] }}</td>
                    <td class="direita">{{ $linha['totalRegistros'] }}</td>
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
        <span class="nota-destaque">Nota metodológica:</span>
        os percentuais de presença são calculados com base nos registros de
        frequência disponíveis no período selecionado.
    </div>

</body>

</html>