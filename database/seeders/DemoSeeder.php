<?php

namespace Database\Seeders;

use App\Models\Aluno;
use App\Models\Boletim;
use App\Models\Escola;
use App\Models\Frequencia;
use App\Models\Mensagem;
use App\Models\RegistroDiario;
use App\Models\Turma;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    private const SENHA = 'demo1234';

    private const DIAS_DE_HISTORICO = 25;

    private const TURMA_AO_VIVO = 'Maternal II';

    public function run(): void
    {
        DB::transaction(function () {
            $escolas = $this->criarEscolas();

            $turmas = $this->criarEstrutura($escolas);

            $this->criarPendentes($escolas[0]);
            $this->gerarHistorico($turmas);
            $this->gerarBoletins($turmas);
            $this->gerarComunicados();
        });

        $this->command?->info('Dados de demonstração criados com sucesso!');

        $this->command?->table(['Perfil', 'E-mail', 'Senha'], [
            ['Administrador', 'Cadastro já existente no servidor', '-'],
            ['Professor', 'professor@exemplo.test', self::SENHA],
            ['Responsável', 'responsavel@exemplo.test', self::SENHA],
        ]);
    }


    private function criarEscolas(): array
    {
        $dados = [
            [
                'nome' => 'Escola Infantil Pequenos Passos',
                'cnpj' => '11.111.111/0001-11',
                'endereco' => 'Rua das Acácias, 120 - Centro',
                'telefone' => '(55) 3000-1111',
                'email' => 'contato@pequenospassos.test',
            ],
            [
                'nome' => 'Espaço Infantil Arco-Íris',
                'cnpj' => '22.222.222/0001-22',
                'endereco' => 'Av. Brasil, 845 - Jardim',
                'telefone' => '(55) 3000-2222',
                'email' => 'contato@arcoiris.test',
            ],
            [
                'nome' => 'Escola Jardim Encantado',
                'cnpj' => '33.333.333/0001-33',
                'endereco' => 'Rua das Flores, 250 - Centro',
                'telefone' => '(55) 3000-3333',
                'email' => 'contato@jardimencantado.test',
            ],
            [
                'nome' => 'Escola Mundo da Criança',
                'cnpj' => '44.444.444/0001-44',
                'endereco' => 'Rua Independência, 410 - Centro',
                'telefone' => '(55) 3000-4444',
                'email' => 'contato@mundodacrianca.test',
            ],
            [
                'nome' => 'Escola Sementinha',
                'cnpj' => '55.555.555/0001-55',
                'endereco' => 'Av. das Palmeiras, 780 - Jardim',
                'telefone' => '(55) 3000-5555',
                'email' => 'contato@sementinha.test',
            ],
        ];

        return array_map(
            fn($escola) => Escola::firstOrCreate(
                ['cnpj' => $escola['cnpj']],
                $escola
            ),
            $dados
        );
    }


    private function criarEstrutura(array $escolas): array
    {
        $professoresConfig = [
            'Marina Duarte' => [
                'email' => null,
                'escolas' => [0],
            ],
            'Rafael Moreira' => [
                'email' => 'professor@exemplo.test',
                'escolas' => [0, 1],
            ],
            'Camila Fontana' => [
                'email' => null,
                'escolas' => [1],
            ],
            'Felipe Martins' => [
                'email' => null,
                'escolas' => [2],
            ],
            'Isabela Rodrigues' => [
                'email' => null,
                'escolas' => [3],
            ],
            'Thiago Oliveira' => [
                'email' => null,
                'escolas' => [4],
            ],
        ];

        $professores = [];

        foreach ($professoresConfig as $nome => $info) {
            $professor = $this->usuario(
                $nome,
                'professor',
                'aprovado',
                $escolas[$info['escolas'][0]],
                $info['email']
            );

            if (
                method_exists($professor, 'escolas')
                && Schema::hasTable('escola_professor')
            ) {
                $professor->escolas()->syncWithoutDetaching(
                    array_map(
                        fn($i) => $escolas[$i]->id,
                        $info['escolas']
                    )
                );
            }

            $professores[$nome] = $professor;
        }

        $config = [

            [
                'escola' => 0,
                'nome' => 'Maternal I',
                'periodo' => 'Manhã',
                'idade' => 2,
                'professor' => 'Marina Duarte',
                'alunos' => [
                    ['Helena Souza', 'Patrícia Souza', 'mae', 'responsavel@exemplo.test'],
                    ['Miguel Andrade', 'Carlos Andrade', 'pai'],
                    ['Alice Ferraz', 'Fernanda Ferraz', 'mae'],
                    ['Theo Barros', 'Ricardo Barros', 'pai'],
                ],
            ],
            [
                'escola' => 0,
                'nome' => 'Maternal II',
                'periodo' => 'Tarde',
                'idade' => 3,
                'professor' => 'Rafael Moreira',
                'alunos' => [
                    ['Davi Souza', 'Patrícia Souza', 'mae', 'responsavel@exemplo.test'],
                    ['Laura Campos', 'Juliana Campos', 'mae'],
                    ['Sofia Mendes', 'Aline Mendes', 'mae'],
                    ['Bernardo Lima', 'Marcos Lima', 'pai'],
                ],
            ],

            [
                'escola' => 1,
                'nome' => 'Berçário',
                'periodo' => 'Integral',
                'idade' => 1,
                'professor' => 'Camila Fontana',
                'alunos' => [
                    ['Valentina Pires', 'Renata Pires', 'mae'],
                    ['Heitor Nunes', 'Paulo Nunes', 'pai'],
                    ['Cecília Araújo', 'Luciana Araújo', 'mae'],
                    ['Gael Teixeira', 'Sérgio Teixeira', 'pai'],
                ],
            ],
            [
                'escola' => 1,
                'nome' => 'Pré I',
                'periodo' => 'Manhã',
                'idade' => 4,
                'professor' => 'Rafael Moreira',
                'alunos' => [
                    ['Manuela Costa', 'Beatriz Costa', 'mae'],
                    ['Arthur Ribeiro', 'André Ribeiro', 'pai'],
                    ['Isadora Vieira', 'Cláudia Vieira', 'mae'],
                    ['Lorenzo Cardoso', 'Eduardo Cardoso', 'pai'],
                ],
            ],

            [
                'escola' => 2,
                'nome' => 'Maternal I',
                'periodo' => 'Manhã',
                'idade' => 2,
                'professor' => 'Felipe Martins',
                'alunos' => [
                    ['Pedro Henrique', 'Amanda Henrique', 'mae'],
                    ['Lívia Martins', 'Roberto Martins', 'pai'],
                    ['Enzo Almeida', 'Carolina Almeida', 'mae'],
                    ['Manuela Freitas', 'Daniel Freitas', 'pai'],
                    ['Gabriel Rocha', 'Juliana Rocha', 'mae'],
                ],
            ],
            [
                'escola' => 2,
                'nome' => 'Pré II',
                'periodo' => 'Tarde',
                'idade' => 5,
                'professor' => 'Felipe Martins',
                'alunos' => [
                    ['Isabella Ferreira', 'Marcelo Ferreira', 'pai'],
                    ['Lucas Carvalho', 'Vanessa Carvalho', 'mae'],
                    ['Maria Eduarda', 'Paulo Eduarda', 'pai'],
                    ['Samuel Gomes', 'Tatiane Gomes', 'mae'],
                    ['Ana Clara', 'Fernando Clara', 'pai'],
                ],
            ],

            [
                'escola' => 3,
                'nome' => 'Berçário',
                'periodo' => 'Integral',
                'idade' => 1,
                'professor' => 'Isabela Rodrigues',
                'alunos' => [
                    ['Benjamin Lopes', 'Mariana Lopes', 'mae'],
                    ['Olívia Batista', 'Ricardo Batista', 'pai'],
                    ['Bento Machado', 'Camila Machado', 'mae'],
                    ['Maitê Cardoso', 'André Cardoso', 'pai'],
                    ['Ravi Monteiro', 'Patrícia Monteiro', 'mae'],
                ],
            ],
            [
                'escola' => 3,
                'nome' => 'Maternal II',
                'periodo' => 'Tarde',
                'idade' => 3,
                'professor' => 'Isabela Rodrigues',
                'alunos' => [
                    ['Júlia Mendes', 'Rodrigo Mendes', 'pai'],
                    ['Nicolas Santos', 'Fernanda Santos', 'mae'],
                    ['Cecília Lima', 'Gustavo Lima', 'pai'],
                    ['Eduardo Reis', 'Aline Reis', 'mae'],
                    ['Lara Dias', 'Marcelo Dias', 'pai'],
                ],
            ],

            [
                'escola' => 4,
                'nome' => 'Maternal I',
                'periodo' => 'Manhã',
                'idade' => 2,
                'professor' => 'Thiago Oliveira',
                'alunos' => [
                    ['Arthur Melo', 'Bianca Melo', 'mae'],
                    ['Sophia Castro', 'Carlos Castro', 'pai'],
                    ['Davi Fernandes', 'Renata Fernandes', 'mae'],
                    ['Luiza Moreira', 'Anderson Moreira', 'pai'],
                    ['Theo Martins', 'Priscila Martins', 'mae'],
                ],
            ],
            [
                'escola' => 4,
                'nome' => 'Pré I',
                'periodo' => 'Tarde',
                'idade' => 4,
                'professor' => 'Thiago Oliveira',
                'alunos' => [
                    ['Miguel Lopes', 'Camila Lopes', 'mae'],
                    ['Helena Ribeiro', 'Paulo Ribeiro', 'pai'],
                    ['Valentina Alves', 'Mariana Alves', 'mae'],
                    ['Enrico Souza', 'Ricardo Souza', 'pai'],
                    ['Antonella Costa', 'Fernanda Costa', 'mae'],
                ],
            ],
        ];

        $resultado = [];

        foreach ($config as $c) {
            $escola = $escolas[$c['escola']];

            $turma = Turma::firstOrCreate(
                [
                    'escola_id' => $escola->id,
                    'nome' => $c['nome'],
                ],
                [
                    'ano' => now()->year,
                    'periodo' => $c['periodo'],
                ]
            );

            $professor = $professores[$c['professor']];

            $turma->professores()->syncWithoutDetaching([
                $professor->id,
            ]);

            $alunos = collect();

            foreach ($c['alunos'] as $i => $linha) {
                [$nomeAluno, $nomeResponsavel, $parentesco] = $linha;

                $emailResponsavel = $linha[3] ?? null;

                $aluno = Aluno::firstOrCreate(
                    [
                        'escola_id' => $escola->id,
                        'nome' => $nomeAluno,
                    ],
                    [
                        'data_nascimento' => Carbon::today()
                            ->subYears($c['idade'])
                            ->subMonths(($i * 3) % 10 + 1)
                            ->toDateString(),
                    ]
                );

                $turma->alunos()->syncWithoutDetaching([
                    $aluno->id,
                ]);

                $responsavel = $this->usuario(
                    $nomeResponsavel,
                    'responsavel',
                    'aprovado',
                    $escola,
                    $emailResponsavel
                );

                $responsavel->alunosResponsavel()->syncWithoutDetaching([
                    $aluno->id => [
                        'parentesco' => $parentesco,
                    ],
                ]);

                $alunos->push($aluno);
            }

            $resultado[] = [
                'turma' => $turma,
                'professor' => $professor,
                'alunos' => $alunos,
                'idade' => $c['idade'],
            ];
        }

        return $resultado;
    }


    private function criarPendentes(Escola $escola): void
    {
        $pendentes = [
            ['Juliana Prates', 'professor'],
            ['Tatiane Alves', 'responsavel'],
            ['Marcelo Fagundes', 'professor'],
            ['Carolina Duarte', 'responsavel'],
            ['André Silveira', 'responsavel'],
        ];

        foreach ($pendentes as [$nome, $perfil]) {
            $this->usuario(
                $nome,
                $perfil,
                'pendente',
                $escola
            );
        }
    }

    private function gerarHistorico(array $turmas): void
    {
        for ($i = self::DIAS_DE_HISTORICO; $i >= 0; $i--) {
            $dia = Carbon::today()->subDays($i);
            $ehHoje = $i === 0;


            if ($dia->isWeekend() && !$ehHoje) {
                continue;
            }

            foreach ($turmas as $item) {
                $turma = $item['turma'];

                if (
                    $ehHoje
                    && $turma->nome === self::TURMA_AO_VIVO
                    && $turma->escola_id === 1
                ) {
                    continue;
                }

                foreach ($item['alunos'] as $indice => $aluno) {
                    mt_srand(crc32(
                        'freq|' . $aluno->id . '|' . $dia->toDateString()
                    ));

                    $presente = mt_rand(1, 100) <= 92;

                    $justificativas = [
                        'Consulta médica',
                        'Febre e resfriado',
                        'Viagem em família',
                        null,
                    ];

                    Frequencia::firstOrCreate(
                        [
                            'aluno_id' => $aluno->id,
                            'turma_id' => $turma->id,
                            'data' => $dia->toDateString(),
                        ],
                        [
                            'presente' => $presente,
                            'justificativa' => $presente
                                ? null
                                : $justificativas[mt_rand(0, 3)],
                            'registrado_por' => $item['professor']->id,
                        ]
                    );


                    if (
                        !$presente
                        || ($ehHoje && $indice % 2 === 1)
                    ) {
                        continue;
                    }

                    $this->gerarRegistro(
                        $aluno,
                        $item['professor'],
                        $turma->periodo,
                        $item['idade'],
                        $dia
                    );
                }
            }
        }
    }


    private function gerarRegistro(
        Aluno $aluno,
        User $professor,
        string $periodo,
        int $idade,
        Carbon $dia
    ): void {
        $registro = RegistroDiario::firstOrCreate(
            [
                'aluno_id' => $aluno->id,
                'data' => $dia->toDateString(),
            ],
            [
                'professor_id' => $professor->id,
                'observacao' => null,
            ]
        );

        if (!$registro->wasRecentlyCreated) {
            return;
        }

        mt_srand(crc32(
            'reg|' . $aluno->id . '|' . $dia->toDateString()
        ));

        $pesos = [
            'tudo' => 60,
            'parte' => 30,
            'rejeitou' => 10,
        ];

        $refeicoes = match ($periodo) {
            'Manhã' => ['colacao', 'almoco'],
            'Tarde' => ['lanche', 'jantar'],
            default => ['colacao', 'almoco', 'lanche', 'jantar'],
        };

        foreach ($refeicoes as $refeicao) {
            $registro->alimentacoes()->create([
                'refeicao' => $refeicao,
                'resultado' => $this->sortear($pesos),
            ]);
        }

        foreach (['leite', 'suco', 'agua'] as $tipo) {
            $registro->liquidos()->create([
                'tipo' => $tipo,
                'resultado' => $this->sortear($pesos),
            ]);
        }

        $this->gerarSono($registro, $idade, $periodo);
        $this->gerarFraldas($registro, $idade);
    }

    private function gerarSono(
        RegistroDiario $registro,
        int $idade,
        string $periodo
    ): void {
        $chanceDeDormir = [
            1 => 90,
            2 => 80,
            3 => 60,
            4 => 40,
        ][$idade] ?? 50;

        if (mt_rand(1, 100) > $chanceDeDormir) {
            $registro->sono()->create([
                'dormiu' => false,
            ]);

            return;
        }

        if ($idade <= 1) {
            $a = Carbon::createFromTime(9, mt_rand(0, 20));
            $b = Carbon::createFromTime(13, mt_rand(15, 45));

            $registro->sono()->create([
                'dormiu' => true,
                'inicio_1' => $a->format('H:i:s'),
                'fim_1' => $a->copy()
                    ->addMinutes(mt_rand(45, 80))
                    ->format('H:i:s'),
                'inicio_2' => $b->format('H:i:s'),
                'fim_2' => $b->copy()
                    ->addMinutes(mt_rand(60, 100))
                    ->format('H:i:s'),
            ]);

            return;
        }

        [$hora, $minuto] = match ($periodo) {
            'Manhã' => [9, 30],
            'Tarde' => [14, 30],
            default => [13, 0],
        };

        $inicio = Carbon::createFromTime(
            $hora,
            $minuto + mt_rand(0, 25)
        );

        $registro->sono()->create([
            'dormiu' => true,
            'inicio_1' => $inicio->format('H:i:s'),
            'fim_1' => $inicio->copy()
                ->addMinutes(mt_rand(50, 110))
                ->format('H:i:s'),
        ]);
    }

    private function gerarFraldas(
        RegistroDiario $registro,
        int $idade
    ): void {
        [$xixiMin, $xixiMax, $cocoMax] = match (true) {
            $idade <= 1 => [4, 6, 2],
            $idade === 2 => [3, 4, 1],
            $idade === 3 => [1, 2, 1],
            default => [0, 1, 0],
        };

        $xixi = mt_rand($xixiMin, $xixiMax);
        $coco = mt_rand(0, $cocoMax);

        $observacoes = [
            'Fezes com aspecto normal',
            'Leve assadura, aplicada pomada',
            'Trocas sem intercorrências',
        ];

        $observacao = mt_rand(1, 100) <= 25
            ? $observacoes[mt_rand(0, 2)]
            : null;

        $trocas = array_merge(
            array_fill(0, $xixi, 'xixi'),
            array_fill(0, $coco, 'coco')
        );

        foreach ($trocas as $i => $tipo) {
            $registro->fraldas()->create([
                'horario' => Carbon::createFromTime(8, 0)
                    ->addMinutes(mt_rand(0, 500))
                    ->format('H:i:s'),
                'xixi' => $tipo === 'xixi',
                'coco' => $tipo === 'coco',
                'observacoes' => $i === 0 ? $observacao : null,
            ]);
        }
    }



    private function gerarBoletins(array $turmas): void
    {
        $observacoes = [
            'Apresenta bom desenvolvimento nas atividades propostas e participa da rotina escolar.',
            'Demonstra evolução na interação com os colegas e nas atividades pedagógicas.',
            'Participa das atividades com entusiasmo e desenvolve gradualmente sua autonomia.',
            'Apresenta desenvolvimento satisfatório e interesse pelas experiências de aprendizagem.',
            'Demonstra curiosidade, criatividade e avanços nas habilidades trabalhadas.',
            'Apresenta boa adaptação à rotina escolar e participa das atividades propostas.',
        ];

        $quantidade = 0;
        $limite = 40;

        foreach ($turmas as $item) {
            foreach ($item['alunos'] as $aluno) {
                if ($quantidade >= $limite) {
                    break 2;
                }

                Boletim::firstOrCreate(
                    [
                        'aluno_id' => $aluno->id,
                        'ano' => now()->year,
                        'tipo_periodo' => 'bimestre',
                        'numero_periodo' => 1,
                    ],
                    [
                        'usuario_id' => $item['professor']->id,
                        'observacao' => $observacoes[
                            $aluno->id % count($observacoes)
                        ],
                        'arquivo_pdf' => null,
                    ]
                );

                $quantidade++;
            }
        }
    }



    private function gerarComunicados(): void
    {
        $comunicados = [
            [
                'Administrador',
                'Patrícia Souza',
                'Helena Souza',
                'Reunião de pais e mestres',
                "Olá!\n\nConvidamos você para a reunião de pais e mestres, na próxima quinta-feira, às 19h, na sala de reuniões.\n\nContamos com a sua presença.",
                12,
            ],
            [
                'Marina Duarte',
                'Carlos Andrade',
                'Miguel Andrade',
                'Passeio ao parque',
                "Olá!\n\nNa próxima semana faremos um passeio ao parque com a turma. Pedimos que o Miguel venha com roupas leves e calçado fechado.\n\nObrigada!",
                8,
            ],
            [
                'Rafael Moreira',
                'Patrícia Souza',
                'Davi Souza',
                'Lembrete: roupa extra',
                "Olá!\n\nPor favor, envie uma muda de roupa extra na mochila do Davi.\n\nObrigado!",
                5,
            ],
            [
                'Administrador',
                'Juliana Campos',
                'Laura Campos',
                'Atualização de contatos',
                "Olá!\n\nSolicitamos que confirme na secretaria os telefones de emergência cadastrados para a Laura.",
                4,
            ],
            [
                'Camila Fontana',
                'Renata Pires',
                'Valentina Pires',
                'Adaptação da Valentina',
                "Olá!\n\nA Valentina está se adaptando muito bem à rotina do berçário. Qualquer dúvida, estamos à disposição.",
                3,
            ],
            [
                'Rafael Moreira',
                'Beatriz Costa',
                'Manuela Costa',
                'Apresentação da turma',
                "Olá!\n\nNo dia 10 teremos a apresentação da turma Pré I. Esperamos vocês!",
                1,
            ],
        ];

        foreach (
            $comunicados as [
                $de,
                $para,
                $nomeAluno,
                $assunto,
                $conteudo,
                $diasAtras
            ]
        ) {
            $remetente = $de === 'Administrador'
                ? User::where('perfil', 'admin')->first()
                : User::where('nome', $de)->first();

            $destinatario = User::where('nome', $para)->first();

            $aluno = Aluno::where('nome', $nomeAluno)->first();

            if (!$remetente || !$destinatario || !$aluno) {
                continue;
            }

            Mensagem::firstOrCreate(
                [
                    'remetente_id' => $remetente->id,
                    'destinatario_id' => $destinatario->id,
                    'aluno_id' => $aluno->id,
                    'assunto' => $assunto,
                ],
                [
                    'conteudo' => $conteudo,
                    'status' => 'enviado',
                    'enviado_em' => now()
                        ->subDays($diasAtras)
                        ->setTime(9, 30),
                ]
            );
        }
    }


    private function usuario(
        string $nome,
        string $perfil,
        string $status,
        Escola $escola,
        ?string $email = null
    ): User {
        return User::firstOrCreate(
            [
                'email' => $email
                    ?? Str::slug($nome, '.') . '@exemplo.test',
            ],
            [
                'escola_id' => $escola->id,
                'nome' => $nome,
                'senha' => self::SENHA,
                'perfil' => $perfil,
                'status' => $status,
            ]
        );
    }


    private function sortear(array $pesos): string
    {
        $n = mt_rand(1, array_sum($pesos));

        foreach ($pesos as $chave => $peso) {
            if (($n -= $peso) <= 0) {
                return $chave;
            }
        }

        return array_key_first($pesos);
    }
}