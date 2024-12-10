<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Order;

class UserExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Mengambil semua data pengguna.
     */
    public function collection()
    {
        // memastikan user relationship di-load dengan benar
        return Order::with('user')->orderBy('created_at', 'desc')->get();
    }

    /**
     * Mendefinisikan header kolom di Excel.
     */
    public function headings(): array
    {
        return [
            "ID",
            "Nama",
            "Email",
            "Role",
            "Tanggal Dibuat",
        ];
    }

    /**
     * Memetakan data pengguna ke kolom Excel.
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->role,
            $user->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
