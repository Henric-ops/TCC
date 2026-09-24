@extends('layout.app')

@section('title', 'Editar escola')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/usuario-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
@endpush

@section('content')
    <div class="usuario-form-page">
        <div class="usuario-form-header">
            <div class="usuario-form-title">
                <span class="usuario-form-title-icon" aria-hidden="true">
                    <i class="bi bi-pencil-square"></i>
                </span>
                <div>
                    <h1 class="h4 mb-1">Editar escola</h1>
                    <p class="usuario-form-subtitle">Atualize os dados cadastrais da escola.</p>
                </div>
            </div>

            <a href="{{ route('admin.escolas.index') }}" class="usuario-button usuario-button-muted">
                <i class="bi bi-arrow-left" aria-hidden="true"></i>
                Voltar para escolas
            </a>
        </div>

        <div class="card usuario-form-card">
            <div class="card-header">
                <i class="bi bi-building" aria-hidden="true"></i>
                <strong>Dados da escola</strong>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
                        <i class="bi bi-exclamation-triangle-fill mt-1" aria-hidden="true"></i>
                        <div>
                            <strong>Não foi possível atualizar a escola.</strong>
                            <ul class="mb-0 mt-1 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.escolas.update', $escola) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" id="nome" name="nome"
                                class="form-control @error('nome') is-invalid @enderror"
                                value="{{ old('nome', $escola->nome) }}">
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">

                            <label for="cnpj" class="form-label">CNPJ</label>

                            <input type="text" id="cnpj" name="cnpj"
                                class="form-control @error('cnpj') is-invalid @enderror" value="{{ old('cnpj') }}"
                                maxlength="18" placeholder="00.000.000/0000-00">

                            @error('cnpj')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>

                        <div class="col-12 mb-3 usuario-form-field">
                            <label for="endereco" class="form-label">Endereço</label>
                            <input type="text" id="endereco" name="endereco"
                                class="form-control @error('endereco') is-invalid @enderror"
                                value="{{ old('endereco', $escola->endereco) }}">
                            @error('endereco')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" id="telefone" name="telefone"
                                class="form-control @error('telefone') is-invalid @enderror"
                                value="{{ old('telefone', $escola->telefone) }}">
                            @error('telefone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3 usuario-form-field">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $escola->email) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="usuario-form-actions">
                        <a href="{{ route('admin.escolas.index') }}" class="usuario-button usuario-button-muted">
                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="usuario-button usuario-button-primary">
                            <i class="bi bi-check2-circle" aria-hidden="true"></i>
                            Salvar alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js"></script>

    <script>
        $('#cnpj').inputmask('99.999.999/9999-99');
    </script>
@endsection