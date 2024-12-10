@extends('layouts.layout')

@section('content')
    <div class="container">
        <form action="{{ route('login.proses') }}" class="card p-5 mt-5" method="POST">
            @csrf
            @if (Session::get('failed'))
                <div class="alert alert-danger">{{ Session::get('failed') }}</div>
            @endif
            <div class="mb-3">
                <label for="email" class="form-label">Input Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Input Password</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-success">Login</button>
        </form>
    </div>
@endsection
