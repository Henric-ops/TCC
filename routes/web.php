<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Admin\TurmasController;
use App\Http\Controllers\Admin\AlunoController;
use App\Http\Controllers\RegistroDiarioController;
use App\Http\Controllers\FrequenciaController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\TurmaController;



Route::get('/', function () {
    return redirect()->route('login');
});



Route::middleware('guest')->group(function () {//rotas para autenticação de usuários não logados

    Route::get('/login', [LoginController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.submit');

    Route::get('/register', [RegisterController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [RegisterController::class, 'register'])
        ->name('register.store');
});




Route::middleware('auth')->group(function () {//rotas para usuários autenticados

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->name('admin.dashboard');

    Route::get('/professor/dashboard', [DashboardController::class, 'professor'])
        ->name('professor.dashboard');

    Route::get('/responsavel/dashboard', [DashboardController::class, 'responsavel'])
        ->name('responsavel.dashboard');
});




Route::middleware(['auth', 'perfil:admin'])//rotas para administração do sistema
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {


        Route::resource('usuarios', UsuarioController::class)
            ->except(['show']);

        Route::resource('turmas', TurmasController::class)
            ->except(['show']);

        Route::resource('alunos', AlunoController::class)
            ->except(['show']);

        Route::get('/usuarios/{usuario}/verificar', [
            UsuarioController::class,
            'verificar'
        ])->name('usuarios.verificar');

        Route::post('/usuarios/{usuario}/aprovar', [
            UsuarioController::class,
            'aprovar'
        ])->name('usuarios.aprovar');

        Route::post('/usuarios/{usuario}/recusar', [
            UsuarioController::class,
            'recusar'
        ])->name('usuarios.recusar');
    });



Route::middleware(['auth', 'perfil:professor'])->group(function () {//rotas para professores
    Route::get('minhas-turmas', [TurmaController::class, 'index'])->name('turmas.minhas');
    Route::get('minhas-turmas/{turma}', [TurmaController::class, 'show'])->name('turmas.minha');
});


Route::middleware(['auth', 'perfil:admin,professor'])//rota para marcação de frequência
    ->group(function () {

        Route::get('/frequencia', [
            FrequenciaController::class,
            'selecionarTurma'
        ])->name('frequencia.selecionar');

        Route::get('/frequencia/marcar', [
            FrequenciaController::class,
            'form'
        ])->name('frequencia.form');

        Route::post('/frequencia/marcar', [
            FrequenciaController::class,
            'salvar'
        ])->name('frequencia.salvar');

        Route::get('/frequencia/historico', [
            FrequenciaController::class,
            'index'
        ])->name('frequencia.index');
    });



Route::middleware(['auth', 'perfil:responsavel'])// rota para visualização da frequência do aluno
    ->group(function () {

        Route::get('/minha-frequencia', [
            FrequenciaController::class,
            'meusRegistros'
        ])->name('frequencia.meus');
    });




Route::middleware(['auth', 'perfil:admin,professor'])//rota para registros diários
    ->group(function () {

        Route::resource('registros-diarios', RegistroDiarioController::class)
            ->except(['show'])
            ->parameters([
                'registros-diarios' => 'registro'
            ])
            ->names('registros');

        Route::get(
            '/registros-diarios/selecionar-aluno',
            [RegistroDiarioController::class, 'selecionarAluno']
        )->name('registros.selecionar-aluno');
    });




Route::middleware(['auth', 'perfil:responsavel'])//rota para visualização dos registros diários
    ->group(function () {

        Route::get('/meus-registros', [
            RegistroDiarioController::class,
            'meusRegistros'
        ])->name('registros.meus');
    });



Route::middleware(['auth', 'perfil:admin,professor'])//rota para envio de e-mail
    ->group(function () {


        Route::get('/emails/create', [
            EmailController::class,
            'create'
        ])->name('emails.create');

        Route::post('/emails/enviar', [
            EmailController::class,
            'enviar'
        ])->name('emails.enviar');
    });