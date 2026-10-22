@extends('layout.app')

@section('title', 'Enviar comunicado')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/email.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
@endpush

@section('content')
    <div class="email-page">

        <div class="email-header">

            <div>

                <h1>
                    Enviar comunicado
                </h1>
                <p>
                    Selecione o aluno e escreva um comunicado para o responsável.
                </p>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success email-alert">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
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

            <form action="{{ route('emails.enviar') }}" method="POST" id="emailForm">

                @csrf
                <div class="email-section">

                    <div class="section-title">
                        <i class="bi bi-people"></i>
                        <span>Destinatário</span>
                    </div>


                    <div class="form-group">

                        <label for="buscar_aluno">
                            Aluno
                            <span>*</span>
                        </label>

                        <div class="input-wrapper autocomplete-wrapper">
                            <i class="bi bi-search"></i>
                            <input type="search" id="buscar_aluno" class="@error('aluno_id') is-invalid @enderror"
                                placeholder="Digite o nome do aluno" autocomplete="off" required role="combobox"
                                aria-autocomplete="list" aria-expanded="false" aria-controls="alunoSugestoes"
                                data-student-search>
                            <input type="hidden" name="aluno_id" id="aluno_id" value="{{ old('aluno_id') }}">
                            <div id="alunoSugestoes" class="student-suggestions" role="listbox" hidden></div>
                        </div>

                        <div id="resultadoBuscaAluno" class="search-status" aria-live="polite">
                            Digite para buscar e selecione um aluno nas sugestões.
                        </div>

                        @error('aluno_id')
                            <div class="field-error">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


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


                <div class="email-section">

                    <div class="section-title">
                        <i class="bi bi-chat-left-text"></i>
                        <span>Mensagem</span>
                    </div>

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

                <div class="email-actions">

                    <a href="{{ url()->previous() }}" class="usuario-button usuario-button-muted">
                        Cancelar
                    </a>

                    <button type="submit" class="usuario-button usuario-button-primary" id="btnEnviar">
                        <i class="bi bi-send-fill"></i>
                        <span>Enviar e-mail</span>
                    </button>

                </div>

            </form>

        </div>

    </div>
    <script>
        window.alunosEmail = @json($alunosEmail);
        window.oldAlunoEmail = @json(old('aluno_id'));
        window.oldResponsavelEmail = @json(old('destinatario_id'));
    </script>

    <script src="{{ asset('js/email.js') }}"></script>

@endsection