@extends('layouts.layout')

@section('content')
    <div class="container mt-3">
        @if (Session::get('success'))
            <div class="alert alert-success text-center">
                {{ Session::get('success') }}
            </div>
        @endif

        <div class="d-flex justify-content-between">
            <form action="#" method="GET" class="d-flex">
                <input type="date" name="search_day" class="form-control me-2" placeholder="Pilih tanggal"
                    value="{{ request('tanggal') }}">
                <button type="submit" class="btn btn-primary me-3">Cari</button>
                <a href="{{ route('pembelian.admin') }}" class="btn btn-secondary">clear</a>
            </form>
        </div>

        <a href="{{ route('pembelian.admin.export') }}" class="btn btn-success">Export Excel</a>

        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pembeli</th>
                    <th>Nama Obat</th>
                    <th>Total Bayar</th>
                    <th>Nama Kasir</th>
                    <th>Tanggal Pembelian</th>
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
                        <td>{{ $order['user']['name'] }}</td>
                        <td>{{ \Carbon\Carbon::create($order->created_at)->locale('id')->isoFormat('dddd DD MMMM, Y H:mm:ss') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </div>
@endsection
