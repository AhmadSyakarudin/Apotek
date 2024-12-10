@extends('layouts.layout')

@section('content')
    <div class="container">
        @if (Session::get('failed'))
            <div class="alert alert-danger">{{ Session::get('failed') }}</div>
        @endif
        @if (Session::get('gagal'))
            <div class="alert alert-danger">{{ Session::get('gagal') }}</div>
        @endif
        <h1>Selamat datang {{ Auth::user()->name }}</h1>
    @endsection
