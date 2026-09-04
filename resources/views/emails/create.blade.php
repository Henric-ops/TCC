@extends('layout.app')

@section('title', 'Enviar e-mail')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/email.css') }}">
    <div class="email-page">

        {{-- Cabeçalho --}}
        <div class="email-header">

            <div>

                <h1>
                    Enviar e-mail
                </h1>
                <p>
                    Preencha os dados abaixo para enviar uma mensagem.

            </div>

            <div class="email-header-icon">
                <i class="bi bi-envelope-paper"></i>
            </div>

        </div>



        @if(session('success'))<!-- Mensagem de sucesso -->
            <div class="alert alert-success email-alert">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif



        @if($errors->any())<!-- Mensagem de erro -->
            <div class="alert alert-danger email-alert">
                <i class="bi bi-exclamation-circle-fill"></i>

                <div>
                    <strong>Não foi possível enviar o e-mail.</strong>

                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif



        <div class="email-card">

            <div class="email-card-header">

                <div class="email-card-icon">
                    <i class="bi bi-send"></i>
                </div>

                <div>
                    <h2>Novo e-mail</h2>

                    <p>
                        Preencha os dados abaixo para enviar uma mensagem.
                    </p>
                </div>

            </div>


            <form action="{{ route('emails.enviar') }}" method="POST" id="emailForm">

                @csrf


                <!-- Destinatário -->
                <div class="email-section">

                    <div class="section-title">
                        <i class="bi bi-people"></i>
                        <span>Destinatário</span>
                    </div>


                    <!-- Aluno -->
                    <div class="form-group">

                        <label for="aluno_id">
                            Aluno
                            <span>*</span>
                        </label>

                        <div class="input-wrapper">

                            <i class="bi bi-person"></i>

                            <select name="aluno_id" id="aluno_id" class="@error('aluno_id') is-invalid @enderror" required>

                                <option value="">
                                    Selecione o aluno
                                </option>

                                @foreach($alunos as $aluno)

                                    <option value="{{ $aluno->id }}" {{ old('aluno_id') == $aluno->id ? 'selected' : '' }}>
                                        {{ $aluno->nome }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        @error('aluno_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <!-- Responsável -->
                    <div class="form-group">

                        <label for="destinatario_id">
                            Responsável
                            <span>*</span>
                        </label>

                        <div class="input-wrapper">

                            <i class="bi bi-person-badge"></i>

                            <select name="destinatario_id" id="destinatario_id"
                                class="@error('destinatario_id') is-invalid @enderror" disabled required>

                                <option value="">
                                    Primeiro selecione um aluno
                                </option>

                            </select>

                        </div>

                        <div id="responsavelInfo" class="responsavel-info" style="display: none;">
                            <i class="bi bi-envelope"></i>
                            <span id="responsavelEmail"></span>
                        </div>

                        @error('destinatario_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                <!-- Conteúdo -->
                <div class="email-section">

                    <div class="section-title">
                        <i class="bi bi-chat-left-text"></i>
                        <span>Mensagem</span>
                    </div>


                    <!-- Assunto -->
                    <div class="form-group">

                        <label for="assunto">
                            Assunto
                            <span>*</span>
                        </label>

                        <input type="text" name="assunto" id="assunto" value="{{ old('assunto') }}"
                            placeholder="Ex.: Reunião de responsáveis" maxlength="255"
                            class="@error('assunto') is-invalid @enderror" required>

                        @error('assunto')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <!-- Mensagem -->
                    <div class="form-group">

                        <label for="conteudo">
                            Mensagem
                            <span>*</span>
                        </label>

                        <div class="editor-wrapper">

                            <textarea name="conteudo" id="conteudo" rows="9" maxlength="5000"
                                placeholder="Digite aqui a mensagem que será enviada ao responsável..."
                                required>{{ old('conteudo') }}</textarea>

                            <div class="character-counter">
                                <span id="characterCount">0</span>/5000
                            </div>

                        </div>

                        @error('conteudo')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                <!-- Ações -->
                <div class="email-actions">

                    <a href="{{ url()->previous() }}" class="btn-cancel">
                        Cancelar
                    </a>

                    <button type="submit" class="btn-send" id="btnEnviar">
                        <i class="bi bi-send-fill"></i>
                        <span>Enviar e-mail</span>
                    </button>

                </div>

            </form>

        </div>

    </div>
    <script>
        window.alunosEmail = @json($alunosEmail);
        window.oldResponsavelEmail = @json(old('destinatario_id'));
    </script>

    <script src="{{ asset('js/email.js') }}"></script>

@endsection