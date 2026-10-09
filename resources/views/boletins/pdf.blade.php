<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Relatório de Avaliação Individual</title>

</head>
<style>
    @page {
        margin: 35px 45px 45px 45px;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 11px;
        color: #333;
        line-height: 1.6;
    }


    .header {
        text-align: center;
        padding-bottom: 18px;
        border-bottom: 2px solid #426e83;
        margin-bottom: 28px;
    }

    .escola {
        font-size: 15px;
        font-weight: bold;
        color: #263c48;
        margin-bottom: 6px;
    }

    .sistema {
        font-size: 9px;
        color: #888;
        margin-bottom: 18px;
    }

    .titulo-principal {
        font-size: 17px;
        font-weight: bold;
        color: #263c48;
        margin: 0;
    }

    .subtitulo {
        font-size: 10px;
        color: #777;
        margin-top: 5px;
    }


    .secao-titulo {
        font-size: 12px;
        font-weight: bold;
        color: #263c48;
        margin-bottom: 12px;
        padding-bottom: 5px;
        border-bottom: 1px solid #ddd;
    }

    .dados {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
    }

    .dados td {
        padding: 8px 12px 8px 0;
        vertical-align: top;
        width: 50%;
    }

    .label {
        display: block;
        font-size: 9px;
        color: #777;
        text-transform: uppercase;
        margin-bottom: 3px;
    }

    .valor {
        font-size: 11px;
        font-weight: bold;
        color: #333;
    }


    .avaliacao {
        margin-top: 10px;
    }

    .observacao {
        font-size: 11px;
        font-weight: normal;
        line-height: 1.9;
        text-align: justify;
        overflow-wrap: break-word;
        color: #444;
    }


    .assinatura {
        margin-top: 75px;
        text-align: center;
        page-break-inside: avoid;
    }

    .linha-assinatura {
        width: 280px;
        border-top: 1px solid #777;
        margin: 0 auto;
        padding-top: 7px;
    }

    .nome-professor {
        font-size: 11px;
        font-weight: bold;
    }

    .cargo {
        font-size: 9px;
        color: #777;
    }


    .rodape {
        position: fixed;
        bottom: -20px;
        left: 0;
        right: 0;
        border-top: 1px solid #ddd;
        padding-top: 10px;
        font-size: 9px;
        color: #888;
    }

    .rodape-tabela {
        width: 100%;
        border-collapse: collapse;
    }

    .rodape-direita {
        text-align: right;
    }
</style>

<body>

    <div class="header">

        <div class="escola">
            {{ $boletim->aluno->escola->nome ?? 'Instituição de Ensino' }}
        </div>

        <div class="sistema">
            LumiKids · Gestão Escolar
        </div>

        <h1 class="titulo-principal">
            Relatório de Avaliação Individual
        </h1>
    </div>



    <div class="secao-titulo">
        Identificação do aluno
    </div>

    <table class="dados">
        <tr>
            <td>
                <span class="label">Aluno</span>
                <span class="valor">
                    {{ $boletim->aluno->nome }}
                </span>
            </td>

            <td>
                <span class="label">Ano letivo</span>
                <span class="valor">
                    {{ $boletim->ano }}
                </span>
            </td>
        </tr>

        <tr>
            <td>
                <span class="label">Período de avaliação</span>
                <span class="valor">
                    {{ $boletim->periodo }}
                </span>
            </td>

            <td>
                <span class="label">Responsável pela avaliação</span>
                <span class="valor">
                    {{ $boletim->usuario->nome ?? 'Não informado' }}
                </span>
            </td>
        </tr>
    </table>


    @if($boletim->observacao)

        <div class="avaliacao">

            <div class="secao-titulo">
                Avaliação descritiva
            </div>

            <div class="observacao">
                {!! nl2br(e($boletim->observacao)) !!}
            </div>

        </div>

    @endif



    <div class="rodape">

        <table class="rodape-tabela">
            <tr>
                <td class="rodape-direita">
                    Emitido em: {{ now()->format('d/m/Y') }}
                </td>
            </tr>
        </table>

    </div>

</body>

</html>