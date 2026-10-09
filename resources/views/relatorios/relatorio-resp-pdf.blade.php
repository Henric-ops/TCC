<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Acompanhamento Individual</title>

    <style>
        @page {
            margin: 35px 45px 50px 45px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
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
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .secao-titulo {
            font-size: 12px;
            font-weight: bold;
            color: #263c48;
            padding-bottom: 6px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 14px;
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
            border-spacing: 7px 0;
            margin-left: -7px;
            margin-bottom: 15px;
            table-layout: fixed;
        }

        .indicadores td {
            padding: 0;
            vertical-align: top;
        }

        .indicador {
            background: #f4f7f8;
            border: 1px solid #e5ebee;
            padding: 13px 5px;
            text-align: center;
        }

        .indicador-numero {
            font-size: 19px;
            font-weight: bold;
            color: #263c48;
            margin-bottom: 5px;
        }

        .indicador-label {
            font-size: 9px;
            color: #687780;
        }


        .barra-titulo {
            font-size: 9px;
            color: #777;
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


        .nota {
            color: #888;
            font-size: 9px;
            margin-top: 8px;
        }


        .secoes-duplas {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            page-break-inside: avoid;
        }

        .secao-coluna {
            width: 47%;
            vertical-align: top;
        }

        .espacamento {
            width: 6%;
        }

        .tabela-resumo {
            width: 100%;
            border-collapse: collapse;
        }

        .tabela-resumo td {
            padding: 10px 5px;
            font-size: 10px;
            border-bottom: 1px solid #e5e5e5;
        }

        .tabela-resumo .numero {
            width: 35px;
            font-weight: bold;
            color: #263c48;
            text-align: right;
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
            {{ $aluno->escola->nome ?? 'Instituição de Ensino' }}
        </div>

        <div class="sistema">
            LumiKids · Gestão Escolar
        </div>

        <h1>Relatório de Acompanhamento Individual</h1>

    </div>



    <div class="secao-titulo">
        Identificação do aluno
    </div>

    <table class="identificacao">
        <tr>
            <td>
                <span class="label">Aluno</span>
                <span class="valor">{{ $aluno->nome }}</span>
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
            Frequência escolar
        </div>

        <table class="indicadores">
            <tr>
                <td>
                    <div class="indicador">
                        <div class="indicador-numero">
                            {{ $dados['totalDias'] }}
                        </div>
                        <div class="indicador-label">
                            Dias registrados
                        </div>
                    </div>
                </td>

                <td>
                    <div class="indicador">
                        <div class="indicador-numero">
                            {{ $dados['presencas'] }}
                        </div>
                        <div class="indicador-label">
                            Presenças
                        </div>
                    </div>
                </td>

                <td>
                    <div class="indicador">
                        <div class="indicador-numero">
                            {{ $dados['faltas'] }}
                        </div>
                        <div class="indicador-label">
                            Faltas
                        </div>
                    </div>
                </td>

                <td>
                    <div class="indicador">
                        <div class="indicador-numero">
                            {{ $dados['percentualPresenca'] }}%
                        </div>
                        <div class="indicador-label">
                            Frequência
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>



    <div class="secao">
        <div class="secao-titulo">
            Alimentação
        </div>

        <table class="indicadores">
            <tr>
                <td>
                    <div class="indicador">
                        <div class="indicador-numero">
                            {{ $dados['alimentacaoContagem']['tudo'] }}
                        </div>
                        <div class="indicador-label">
                            Comeu tudo
                        </div>
                    </div>
                </td>

                <td>
                    <div class="indicador">
                        <div class="indicador-numero">
                            {{ $dados['alimentacaoContagem']['parte'] }}
                        </div>
                        <div class="indicador-label">
                            Comeu parte
                        </div>
                    </div>
                </td>

                <td>
                    <div class="indicador">
                        <div class="indicador-numero">
                            {{ $dados['alimentacaoContagem']['rejeitou'] }}
                        </div>
                        <div class="indicador-label">
                            Rejeitou
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <div class="nota">
            Quantidade de registros de alimentação no período selecionado.
        </div>
    </div>

    <table class="secoes-duplas">
        <tr>
            <td class="secao-coluna">
                <div class="secao-titulo">
                    Sono
                </div>

                <table class="tabela-resumo">
                    <tr>
                        <td>Dias que dormiu</td>
                        <td class="numero">
                            {{ $dados['diasComSono'] }}
                        </td>
                    </tr>

                    <tr>
                        <td>Dias sem dormir</td>
                        <td class="numero">
                            {{ $dados['diasSemSono'] }}
                        </td>
                    </tr>
                </table>
            </td>

            <td class="espacamento"></td>

            <td class="secao-coluna">
                <div class="secao-titulo">
                    Trocas de fraldas
                </div>

                <table class="tabela-resumo">
                    <tr>
                        <td>Total de xixi</td>
                        <td class="numero">
                            {{ $dados['totalXixi'] }}
                        </td>
                    </tr>

                    <tr>
                        <td>Total de cocô</td>
                        <td class="numero">
                            {{ $dados['totalCoco'] }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>


    <div class="rodape">
        <table>
            <tr>
                <td>
                    LumiKids · Relatório escolar
                </td>

                <td class="rodape-direita">
                    Emitido em: {{ now()->format('d/m/Y') }}
                </td>
            </tr>
        </table>
    </div>

</body>

</html>