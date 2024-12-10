<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bukti Pembelian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        #receipt {
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .header-title {
            font-size: 1.5rem;
            color: #007bff;
        }
        .back-button, .print-button {
            text-decoration: none;
            color: white;
        }
        .back-button:hover, .print-button:hover {
            color: white;
        }
        .table-bordered th, .table-bordered td {
            border-color: #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('kasir.order') }}" class="btn btn-secondary back-button">Kembali</a>
        </div>

        <div class="card" id="receipt">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="header-title">Bukti Pembelian</h2>
            </div>
            <div class="mb-4">
                <p>
                    <strong>Alamat:</strong> Jl. cibalok tajur No. 1<br>
                    <strong>Email:</strong> apotekJayaAbadi@gmail.com<br>
                    <strong>Phone:</strong> 0857-7216-1321
                </p>
            </div>

            <table class="table table-bordered">
                <thead class="thead-light">
                    <tr>
                        <th>Obat</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order['medicines'] as $medicine)
                    <tr>
                        <td>{{ $medicine['name_medicine'] }}</td>
                        <td>{{ $medicine['qty'] }}</td>
                        <td>Rp {{ number_format($medicine['price'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" class="text-right"><strong>PPN (10%)</strong></td>
                        @php
                            $ppn = $order['total_price'] * 0.1;
                        @endphp
                        <td>Rp {{ number_format($ppn, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-right"><strong>Total Harga</strong></td>
                        <td>Rp {{ number_format($order['total_price'] + $ppn, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="text-center mt-4">
                <p class="text-muted">Terima Kasih Atas Pembelian Anda!</p>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
