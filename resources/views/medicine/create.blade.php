@extends('layouts.layout')
{{-- extends : untuk mengimport template @yield dari layout --}}
@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Halaman Menambahkan Obat</h1>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-4">
                    <form action="{{ route('obat.tambah.formulir') }}" method="POST">
                        @csrf

                        {{-- Mengecek Jika Success --}}
                        @if (Session::get('success'))
                            <div class="alert alert-success">
                                {{ Session::get('success') }}
                            </div>
                        @endif

                        {{-- Mengecek Jika Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    {{-- Memakai looping karena array(banyak data) --}}
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Nama Obat --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Obat:</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name') }}">
                        </div>

                        {{-- Jenis Obat --}}
                        <div class="mb-3">
                            <label for="type" class="form-label">Jenis Obat:</label>
                            <select class="form-select" name="type" id="type">
                                <option selected disabled hidden>Pilih Obat</option>
                                <option value="tablet" {{ old('type') == 'tablet' ? 'selected' : '' }}>Tablet</option>
                                <option value="sirup" {{ old('type') == 'sirup' ? 'selected' : '' }}>Sirup</option>
                                <option value="kapsul" {{ old('type') == 'kapsul' ? 'selected' : '' }}>Kapsul</option>
                            </select>
                        </div>

                        {{-- Harga Obat --}}
                        <div class="mb-3">
                            <label for="price" class="form-label">Harga Obat:</label>
                            <input type="number" class="form-control" id="price" name="price"
                                value="{{ old('price') }}">
                        </div>

                        {{-- Sisa Stock --}}
                        <div class="mb-3">
                            <label for="stock" class="form-label">Sisa Stock:</label>
                            <input type="number" class="form-control" id="stock" name="stock"
                                value="{{ old('stock') }}">
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Tambah Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
