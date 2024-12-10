@extends('layouts.layout')

@section('content')
    <div class="container mt-3">
        @if (Session::get('failed'))
            <div class="alert alert-danger">{{ Session::get('failed') }}</div>
        @endif
        @if (Session::get('success'))
            <div class="alert alert-success">{{ Session::get('success') }}</div>
        @endif

        <form action="{{ route('kasir.order.store') }}" method="POST" class="card mx-auto my-5 p-5 w-75">
            @csrf
            <p>Penanggung Jawab: <b>{{ Auth::user()->name }}</b></p>

            <div class="mb-3 row">
                <label for="name_customer" class="col-sm-2 col-form-label">Nama Pembeli</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name_customer" name="name_customer" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="medicines" class="col-sm-2 col-form-label">Obat</label>
                <div class="col-sm-10">
                    @if (isset($valueBefore))
                        @foreach ($valueBefore['medicines'] as $key => $medicine)
                            <div class="d-flex mb-2" id="medicines-{{ $key }}">
                                <select name="medicines[]" class="form-select">
                                    @foreach ($medicine as $item)
                                        <option value="{{ $item['id'] }}"
                                            {{ $medicines == $item['id'] ? 'selected' : '' }}>
                                            {{ $item['name'] }} (Stock: {{ $item['stock'] }})
                                        </option>
                                    @endforeach
                                </select>
                                @if ($key > 0)
                                    <span style="cursor: pointer" class="text-danger p-2"
                                        onclick="deleteSelect('medicines-{{ $key }}')">X</span>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <select name="medicines[]" class="form-select" required>
                            <option selected hidden disabled>Pesanan 1</option>
                            @foreach ($medicines as $item)
                                <option value="{{ $item['id'] }}">{{ $item['name'] }} (Stock: {{ $item['stock'] }})
                                </option>
                            @endforeach
                        </select>
                    @endif

                    <div id="medicines-wrap"></div>
                    <p style="cursor: pointer" class="text-primary" id="add-select">+ Tambah Obat</p>
                </div>
            </div>

            <button type="submit" class="btn btn-block btn-lg btn-primary">Konfirmasi Pembelian</button>
        </form>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>

    <script>
        let no = 2;

        $("#add-select").on("click", function() {
            let html = `<div id="medicines-${no}" class="d-flex mb-2">
                            <select name="medicines[]" class="form-select">
                                <option selected hidden disabled>Pesanan ${no}</option>
                                @foreach ($medicines as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }} (Stock: {{ $item['stock'] }})</option>
                                @endforeach
                            </select>
                            <span style="cursor: pointer" class="text-danger p-2" onclick="deleteSelect('medicines-${no}')">X</span>
                        </div>`;

            $("#medicines-wrap").append(html);
            no++;
        });

        function deleteSelect(id) {
            $(`#${id}`).remove();
            no--;
        }
    </script>
@endpush
