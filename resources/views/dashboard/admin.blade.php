@extends('layout.app')

@section('title', 'Dashboard - LumiKids')

@section('content')
    <h1>Dashboard Admin</h1>
    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit" class="btn btn-danger">
            Sair
        </button>
    </form>
@endsection