<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <title>Boletim Escolar</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 20px;
        }

        .dados {
            margin-bottom: 20px;
        }

        .campo {
            margin-bottom: 8px;
        }

        .titulo {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .observacao {
            text-align: justify;
            line-height: 1.5;
        }

        .rodape {
            margin-top: 40px;
            font-size: 10px;
        }
    </style>

</head>

<body>

    <div class="header">

        <h1>Boletim Escolar</h1>

        <p>LumiKids - Gestão Escolar</p>

    </div>


    <div class="dados">

        <div class="campo">
            <strong>Aluno:</strong>
            {{ $boletim->aluno->nome }}
        </div>


        <div class="campo">
            <strong>Período:</strong>
            {{ $boletim->periodo }}
        </div>


        <div class="campo">
            <strong>Ano:</strong>
            {{ $boletim->ano }}
        </div>


    </div>


    @if($boletim->observacao)

        <div class="titulo">
            Avaliação
        </div>


        <div class="observacao">

            {!! nl2br(e($boletim->observacao)) !!}

        </div>

    @endif


    <div class="rodape">

        Emitido em:
        {{ now()->format('d/m/Y H:i') }}

    </div>


</body>

</html>