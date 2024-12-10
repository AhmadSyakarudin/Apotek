<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Log;

class OrderExport implements FromCollection, WithHeadings, WithMapping
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // menentukan
        return Order::with('user')->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        // membuat th
        return [
            "ID",
            "Nama Kasir",
            "Daftar Obat",
            "Nama Pembeli",
            "Total Harga",
            "Tanggal",
        ];
    }

    public function map($order): array
    {
        // string penampung data2 obat
        $daftarObat = "";
        foreach ($order->medicines as $key => $value) {
            $obat = $key + 1 . ". " . $value['name_medicine'] . " ( " . $value['qty'] . " pcs) Rp. " . number_format($value['total_price'], 0, ',', '.') . " , ";
            // menggabungkan nilai di $daftarObat dengan string $obat
            $daftarObat .= $obat;
        }

        // Cek apakah user ada sebelum mengakses properti 'name'
        if (!$order->user) {
            Log::error("Order ID: {$order->id} tidak memiliki user.");
        }

        $namaKasir = $order->user ? $order->user->name : 'Tidak Ada Kasir';

        // membuat data
        return [
            $order->id,
            $namaKasir, // Gunakan nama kasir dengan cek null
            $daftarObat,
            $order->name_customer,
            "Rp. " . number_format($order->total_price, 0, ',', '.'),
            \Carbon\Carbon::create($order->created_at)->locale('id')->isoFormat('dddd DD MMMM, Y H:mm:ss'),
        ];
    }
}
