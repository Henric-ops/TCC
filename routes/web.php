<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\RegisterController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');//rota para redirecionar para o dashboard correto com base no perfil do usuário
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');//rota para processar o logout
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/professor/dashboard', [DashboardController::class, 'professor'])->name('professor.dashboard');
    Route::get('/responsavel/dashboard', [DashboardController::class, 'responsavel'])->name('responsavel.dashboard');
});


Route::middleware(['auth', 'perfil:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('usuarios', UsuarioController::class)->except(['show']);//rota para gerenciar usuários

    Route::get('/usuarios/{usuario}/verificar', [UsuarioController::class, 'verificar'])
        ->name('usuarios.verificar');//rota para verificar o usuário

    Route::post('/usuarios/{usuario}/aprovar', [UsuarioController::class, 'aprovar'])
        ->name('usuarios.aprovar');//rota para aprovar o usuário

    Route::post('/usuarios/{usuario}/recusar', [UsuarioController::class, 'recusar'])
        ->name('usuarios.recusar'); //rota para recusar o usuário
});


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');//rota para exibir o formulário de login
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');//rota para processar o login

    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');//rota para exibir o formulário de registro
    Route::post('/register', [RegisterController::class, 'register'])->name('register.store');//rota para processar o registro
});
