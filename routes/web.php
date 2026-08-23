<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Admin\TurmasController;
use App\Http\Controllers\Admin\AlunoController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/professor/dashboard', [DashboardController::class, 'professor'])->name('professor.dashboard');
    Route::get('/responsavel/dashboard', [DashboardController::class, 'responsavel'])->name('responsavel.dashboard');
});

Route::middleware(['auth', 'perfil:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('usuarios', UsuarioController::class)->except(['show']); // gerenciar usuários
    Route::resource('turmas', TurmasController::class)->except(['show']); // gerenciar turmas
    Route::resource('alunos', AlunoController::class)->except(['show']);// gerenciar alunos


    Route::get('/usuarios/{usuario}/verificar', [UsuarioController::class, 'verificar'])->name('usuarios.verificar');
    Route::post('/usuarios/{usuario}/aprovar', [UsuarioController::class, 'aprovar'])->name('usuarios.aprovar');
    Route::post('/usuarios/{usuario}/recusar', [UsuarioController::class, 'recusar'])->name('usuarios.recusar');


});