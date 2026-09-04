<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailResponsavel extends Mailable
{
    use Queueable, SerializesModels;

    public string $assunto;
    public string $conteudo;
    public string $nomeAluno;
    public string $nomeRemetente;
    public string $nomeEscola;

    public function __construct(
        string $assunto,
        string $conteudo,
        string $nomeAluno,
        string $nomeRemetente,
        string $nomeEscola
    ) {
        $this->assunto = $assunto;
        $this->conteudo = $conteudo;
        $this->nomeAluno = $nomeAluno;
        $this->nomeRemetente = $nomeRemetente;
        $this->nomeEscola = $nomeEscola;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->assunto,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.responsavel',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}