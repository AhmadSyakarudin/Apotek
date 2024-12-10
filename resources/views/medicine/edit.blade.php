@extends('layouts.layout')

{{-- put merubah semua --}}
{{-- patch merubah sebagian atau salah satu --}}
@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Halaman Edit Obat</h1>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card p-4">
                    {{-- koma buat misahin route dan parameter dinamis --}}
                    <form action="{{ route('obat.edit.formulir', $medicine['id']) }}" method="POST">
                        @csrf
                        @method('PATCH')

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
                            <input type="text" class="form-control" id="name" name="name" value="{{ $medicine->name }}">
                        </div>

                        {{-- Jenis Obat --}}
                        <div class="mb-3">
                            <label for="type" class="form-label">Jenis Obat:</label>
                            <select class="form-select" name="type" id="type">
                                <option selected disabled hidden>Pilih Obat</option>
                                <option value="tablet" {{ $medicine['type'] == 'tablet' ? 'selected' : '' }}>Tablet</option>
                                <option value="sirup" {{ $medicine['type'] == 'sirup' ? 'selected' : '' }}>Sirup</option>
                                <option value="kapsul" {{ $medicine['type'] == 'kapsul' ? 'selected' : '' }}>Kapsul</option>
                            </select>
                        </div>

                        {{-- Harga Obat --}}
                        <div class="mb-3">
                            <label for="price" class="form-label">Harga Obat:</label>
                            <input type="number" class="form-control" id="price" name="price" value="{{ $medicine['price'] }}">
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
