<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <title>{{ $assunto }}</title>
</head>

<body style="margin: 0; padding: 0; background: #f5f7fb; font-family: Arial, sans-serif;">

    <div style="max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 12px; overflow: hidden;">

        <div style="padding: 25px; text-align: center; background: #ffffff;">
            <h1 style="margin: 0; font-size: 26px;">
                {{ $nomeEscola }}
            </h1>

            <p style="margin: 5px 0 0; color: #777;">
                Comunicado escolar
            </p>
        </div>

        <div style="padding: 30px;">

            <p>
                Olá!
            </p>

            <p>
                Este e-mail foi enviado pela <strong>{{ $nomeEscola }}</strong>
                referente ao aluno:
            </p>

            <p>
                <strong>{{ $nomeAluno }}</strong>
            </p>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 25px 0;">

            <div style="font-size: 15px; line-height: 1.7;">
                {!! nl2br(e($conteudo)) !!}
            </div>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 25px 0;">

            <p style="margin-bottom: 0;">
                Atenciosamente,
            </p>

            <strong>
                {{ $nomeRemetente }}
            </strong>

            <p style="color: #888; font-size: 13px;">
                {{ $nomeEscola }}
                <br>
                <span style="font-size: 11px; color: #aaa;">Enviado via sistema LumiKids</span>
            </p>

        </div>

    </div>

</body>

</html>