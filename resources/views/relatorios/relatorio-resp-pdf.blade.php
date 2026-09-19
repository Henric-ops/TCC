<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #1B2141;
            font-size: 12px;
        }

        h1 {
            font-size: 18px;
            margin-bottom: 2px;
        }

        .sub {
            color: #666;
            font-size: 11px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px 10px;
            text-align: left;
            font-size: 11px;
        }

        th {
            background: #f4f5fa;
            width: 50%;
        }
    </style>
</head>

<body>
    <h1>Relatório — {{ $aluno->nome }}</h1>
    <p class="sub">Período: {{ \Carbon\Carbon::parse($inicio)->format('d/m/Y') }} a
        {{ \Carbon\Carbon::parse($fim)->format('d/m/Y') }}
    </p>

    <table>
        <tr>
            <th>Dias letivos</th>
            <td>{{ $dados['totalDias'] }}</td>
        </tr>
        <tr>
            <th>Presenças</th>
            <td>{{ $dados['presencas'] }}</td>
        </tr>
        <tr>
            <th>Faltas</th>
            <td>{{ $dados['faltas'] }}</td>
        </tr>
        <tr>
            <th>% de presença</th>
            <td>{{ $dados['percentualPresenca'] }}%</td>
        </tr>
    </table>

    <table>
        <tr>
            <th>Comeu tudo</th>
            <td>{{ $dados['alimentacaoContagem']['tudo'] }}</td>
        </tr>
        <tr>
            <th>Comeu parte</th>
            <td>{{ $dados['alimentacaoContagem']['parte'] }}</td>
        </tr>
        <tr>
            <th>Rejeitou</th>
            <td>{{ $dados['alimentacaoContagem']['rejeitou'] }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <th>Dias que dormiu</th>
            <td>{{ $dados['diasComSono'] }}</td>
        </tr>
        <tr>
            <th>Dias sem dormir</th>
            <td>{{ $dados['diasSemSono'] }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <th>Total de xixi</th>
            <td>{{ $dados['totalXixi'] }}</td>
        </tr>
        <tr>
            <th>Total de cocô</th>
            <td>{{ $dados['totalCoco'] }}</td>
        </tr>
    </table>
</body>

</html>