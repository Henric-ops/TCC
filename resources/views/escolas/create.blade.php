@extends('layout.app')

@section('title', 'Nova escola')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/usuario-form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/usuario-buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/escola-create.css') }}">
@endpush

@section('content')
    <main class="usuario-form-page escola-create-page">
        <header class="escola-create-header">
            <div>
                <h1 class="escola-create-title">Nova escola</h1>
                <p class="escola-create-subtitle">Cadastre os dados e contatos da instituição.</p>
            </div>
            <a href="{{ route('admin.escolas.index') }}" class="usuario-button usuario-button-muted">
                Voltar
            </a>
        </header>

        <section class="card usuario-form-card escola-create-card">
            <div class="card-body">
                <div class="escola-create-section-heading">
                    <h2>Dados da escola</h2>
                </div>
                <div class="escola-create-divider" aria-hidden="true"></div>

                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-start gap-2" role="alert">
                        <i class="bi bi-exclamation-triangle-fill mt-1" aria-hidden="true"></i>
                        <div>
                            <strong>Não foi possível cadastrar a escola.</strong>
                            <ul class="mb-0 mt-1 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.escolas.store') }}" method="POST">
                    @csrf

                    <div class="row g-3 escola-create-fields">
                        <div class="col-md-6 usuario-form-field">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" id="nome" name="nome"
                                class="form-control @error('nome') is-invalid @enderror" value="{{ old('nome') }}">
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 usuario-form-field">
                            <label for="cnpj" class="form-label">CNPJ</label>
                            <input type="text" id="cnpj" name="cnpj"
                                class="form-control @error('cnpj') is-invalid @enderror" value="{{ old('cnpj') }}"
                                maxlength="18" placeholder="00.000.000/0000-00">
                            @error('cnpj')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 usuario-form-field">
                            <label for="endereco" class="form-label">Endereço</label>
                            <input type="text" id="endereco" name="endereco"
                                class="form-control @error('endereco') is-invalid @enderror" value="{{ old('endereco') }}">
                            @error('endereco')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 usuario-form-field">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" id="telefone" name="telefone"
                                class="form-control @error('telefone') is-invalid @enderror" value="{{ old('telefone') }}">
                            @error('telefone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 usuario-form-field">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="usuario-form-actions">
                        <a href="{{ route('admin.escolas.index') }}" class="usuario-button usuario-button-danger">
                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                            Cancelar
                        </a>
                        <button type="submit" class="usuario-button usuario-button-primary">
                            <i class="bi bi-building-add" aria-hidden="true"></i>
                            Cadastrar escola
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.9/jquery.inputmask.min.js"></script>

    <script>
        $('#cnpj').inputmask('99.999.999/9999-99');
    </script>
@endsection