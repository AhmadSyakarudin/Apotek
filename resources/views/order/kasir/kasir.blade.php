@extends('layouts.layout')

@section('content')
    <div class="container mt-3">
        @if (Session::get('success'))
            <div class="alert alert-success text-center">
                {{ Session::get('success') }}
            </div>
        @endif

        <div class="d-flex justify-content-between">
            <form action="{{ route('kasir.order') }}" method="GET" class="d-flex">
                <input type="date" name="search_day" class="form-control me-2" placeholder="Pilih tanggal"
                    value="{{ request('tanggal') }}">
                <button type="submit" class="btn btn-primary me-3">Cari</button>
                <a href="{{ route('kasir.order') }}" class="btn btn-secondary">clear</a>
            </form>
            <a href="{{ route('kasir.formulir') }}" class="btn btn-primary">+ Tambah Pesanan</a>
        </div>

        <h1>Data Pembelian: {{ Auth::user()->name }}</h1>
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pembeli</th>
                    <th>Nama Obat</th>
                    <th>Total Bayar</th>
                    <th>Nama Kasir</th>
                    <th>Tanggal Pembelian</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $no = ($orders->currentPage() - 1) * $orders->perPage() + 1; @endphp
                @foreach ($orders as $order)
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $order->name_customer }}</td>
                        <td>
                            <ul class="list-unstyled">
                                @foreach ($order->medicines as $medicine)
                                    <li>{{ $medicine['name_medicine'] }} (qty {{ $medicine['qty'] }}) : Rp.
                                        {{ number_format($medicine['total_price'], 0, ',', '.') }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>Rp. {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>{{ Auth::user()->role }}</td>
                        <td>{{ \Carbon\Carbon::create($order->created_at)->locale('id')->isoFormat('dddd DD MMMM, Y H:1:s') }}</td>
                        <td class="text-center">
                            <a href="{{ route('kasir.download_pdf', $order->id) }}" class="btn btn-secondary btn-sm">Download Struk</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
