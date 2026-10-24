<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Acompanhamento Individual</title>

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
            margin-bottom: 26px;
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
            margin-bottom: 16px;
        }

        .quadro {
            margin-bottom: 26px;
            page-break-inside: avoid;
        }

        .quadro-titulo {
            font-size: 9px;
            font-weight: bold;
            color: #111;
            margin-bottom: 8px;
        }

        .indicadores {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border-top: 1px solid #111;
            border-bottom: 1px solid #111;
        }

        .indicadores td {
            padding: 14px 12px 12px 12px;
            vertical-align: top;
            border-left: 1px solid #e3e7ea;
        }

        .indicadores td.primeiro {
            padding-left: 0;
            border-left: none;
        }

        .ind-numero {
            font-size: 22px;
            font-weight: bold;
            line-height: 1.1;
            color: #111;
        }

        .ind-label {
            margin-top: 5px;
            font-size: 7px;
            font-weight: bold;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            color: #6b7785;
        }

        .barra-bloco {
            margin-top: 12px;
        }

        .barra-legenda {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
            font-size: 8px;
            color: #6b7785;
        }

        .barra-legenda td {
            padding: 0;
        }

        .barra-legenda .direita {
            text-align: right;
            font-weight: bold;
            color: #111;
        }

        .barra-fundo {
            width: 100%;
            height: 7px;
            background: #e9edef;
        }

        .barra-preenchida {
            height: 7px;
            background: #426e83;
        }

        .tabela {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            border-top: 1px solid #111;
            border-bottom: 1px solid #111;
        }

        .tabela th {
            padding: 9px 8px;
            font-size: 7px;
            font-weight: bold;
            letter-spacing: 0.4px;
            text-transform: uppercase;
            text-align: left;
            color: #444;
            border-bottom: 1px solid #111;
        }

        .tabela td {
            padding: 11px 8px;
            font-size: 9px;
            vertical-align: middle;
            border-bottom: 1px solid #e3e7ea;
        }

        .tabela tbody tr:last-child td {
            border-bottom: none;
        }

        .tabela .primeiro {
            padding-left: 0;
        }

        .tabela .num {
            text-align: right;
            font-weight: bold;
            color: #111;
        }

        .tabela th.num {
            text-align: right;
        }

        .col-cat {
            width: 26%;
        }

        .col-qtd {
            width: 14%;
        }

        .col-pct {
            width: 14%;
        }

        .col-barra {
            width: 46%;
        }

        .tabela .barra-fundo {
            height: 6px;
        }

        .tabela .barra-preenchida {
            height: 6px;
        }

        .cor-tudo {
            background: #588e85;
        }

        .cor-parte {
            background: #d9a441;
        }

        .cor-rejeitou {
            background: #b5533c;
        }

        .duplo {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .duplo>tbody>tr>td {
            padding: 0;
            vertical-align: top;
        }

        .duplo .espaco {
            width: 6%;
        }

        .duplo .coluna {
            width: 47%;
        }

        .nota {
            margin-top: 8px;
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

    @php
        $pctFrequencia = max(0, min(100, (float) $dados['percentualPresenca']));

        $alim = $dados['alimentacaoContagem'];
        $totalAlim = (int) $alim['tudo'] + (int) $alim['parte'] + (int) $alim['rejeitou'];
        $pct = function ($valor) use ($totalAlim) {
            return $totalAlim > 0 ? round(($valor / $totalAlim) * 100) : 0;
        };
    @endphp

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
                {{ $aluno->escola->nome ?? 'Instituição de Ensino' }}
            </td>
        </tr>
    </table>

    <div class="titulo">Relatório de Acompanhamento Individual</div>

    <div class="periodo">
        Relatório referente ao período de
        {{ \Carbon\Carbon::parse($inicio)->format('d/m/Y') }}
        a
        {{ \Carbon\Carbon::parse($fim)->format('d/m/Y') }}.
    </div>

    <table class="identificacao">
        <tr>
            <td class="rotulo">Instituição:</td>
            <td class="valor">{{ $aluno->escola->nome ?? 'Instituição de Ensino' }}</td>
        </tr>
        <tr>
            <td class="rotulo">Aluno:</td>
            <td class="valor">{{ $aluno->nome }}</td>
        </tr>
    </table>

    <div class="secao-titulo">Síntese dos registros</div>

    <div class="quadro">
        <div class="quadro-titulo">Quadro 1 — Frequência escolar</div>

        <table class="indicadores">
            <tr>
                <td class="primeiro">
                    <div class="ind-numero">{{ $dados['totalDias'] }}</div>
                    <div class="ind-label">Dias registrados</div>
                </td>
                <td>
                    <div class="ind-numero">{{ $dados['presencas'] }}</div>
                    <div class="ind-label">Presenças</div>
                </td>
                <td>
                    <div class="ind-numero">{{ $dados['faltas'] }}</div>
                    <div class="ind-label">Faltas</div>
                </td>
                <td>
                    <div class="ind-numero">{{ $dados['percentualPresenca'] }}%</div>
                    <div class="ind-label">Frequência</div>
                </td>
            </tr>
        </table>

        <div class="barra-bloco">
            <table class="barra-legenda">
                <tr>
                    <td>Frequência no período</td>
                    <td class="direita">{{ $dados['percentualPresenca'] }}%</td>
                </tr>
            </table>
            <div class="barra-fundo">
                <div class="barra-preenchida" style="width: {{ $pctFrequencia }}%;"></div>
            </div>
        </div>
    </div>

    <div class="quadro">
        <div class="quadro-titulo">Quadro 2 — Alimentação</div>

        <table class="tabela">
            <thead>
                <tr>
                    <th class="primeiro col-cat">Aceitação</th>
                    <th class="num col-qtd">Registros</th>
                    <th class="num col-pct">%</th>
                    <th class="col-barra">Proporção</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="primeiro">Comeu tudo</td>
                    <td class="num">{{ $alim['tudo'] }}</td>
                    <td class="num">{{ $pct($alim['tudo']) }}%</td>
                    <td>
                        <div class="barra-fundo">
                            <div class="barra-preenchida cor-tudo" style="width: {{ $pct($alim['tudo']) }}%;"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="primeiro">Comeu parte</td>
                    <td class="num">{{ $alim['parte'] }}</td>
                    <td class="num">{{ $pct($alim['parte']) }}%</td>
                    <td>
                        <div class="barra-fundo">
                            <div class="barra-preenchida cor-parte" style="width: {{ $pct($alim['parte']) }}%;"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="primeiro">Rejeitou</td>
                    <td class="num">{{ $alim['rejeitou'] }}</td>
                    <td class="num">{{ $pct($alim['rejeitou']) }}%</td>
                    <td>
                        <div class="barra-fundo">
                            <div class="barra-preenchida cor-rejeitou" style="width: {{ $pct($alim['rejeitou']) }}%;">
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="nota">
            Total de {{ $totalAlim }} registros de alimentação no período selecionado.
        </div>
    </div>

    <table class="duplo">
        <tr>
            <td class="coluna">
                <div class="quadro-titulo">Quadro 3 — Sono</div>
                <table class="indicadores">
                    <tr>
                        <td class="primeiro">
                            <div class="ind-numero">{{ $dados['diasComSono'] }}</div>
                            <div class="ind-label">Dias que dormiu</div>
                        </td>
                        <td>
                            <div class="ind-numero">{{ $dados['diasSemSono'] }}</div>
                            <div class="ind-label">Dias sem dormir</div>
                        </td>
                    </tr>
                </table>
            </td>

            <td class="espaco"></td>

            <td class="coluna">
                <div class="quadro-titulo">Quadro 4 — Trocas de fraldas</div>
                <table class="indicadores">
                    <tr>
                        <td class="primeiro">
                            <div class="ind-numero">{{ $dados['totalXixi'] }}</div>
                            <div class="ind-label">Total de xixi</div>
                        </td>
                        <td>
                            <div class="ind-numero">{{ $dados['totalCoco'] }}</div>
                            <div class="ind-label">Total de cocô</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="nota" style="margin-top: 22px;">
        <span class="nota-destaque">Nota metodológica:</span>
        os indicadores são calculados com base nos registros diários
        disponíveis no período selecionado.
    </div>

</body>

</html>