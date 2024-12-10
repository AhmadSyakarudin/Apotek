<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Medicine;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\OrderExport;

class OrderController extends Controller
{
    public function exportExcel()
    {
        return Excel::download(new OrderExport, 'rekap-pembelian.xlsx');
    }

    // public function exportUserExcel()
    // {
    //     return Excel::download(new OrderExport, 'rekap-akun.xlsx');
    // }

    public function index(Request $request)
    {
        $search_day = $request->search_day ? $request->search_day . '%' : '%';

        $orders = Order::where('user_id', Auth::user()->id) // Gunakan ID pengguna
            ->where('created_at', 'LIKE', $search_day) // Pencarian dengan LIKE pada created_at
            ->simplePaginate(5);

        return view("order.kasir.kasir", compact("orders"));
    }

    public function indexAdmin(Request $request)
    {
        $search_day = $request->search_day ? $request->search_day . '%' : '%';

        $orders = Order::with('user')
            ->where('created_at', 'LIKE', $search_day) // Pencarian dengan LIKE pada created_at
            ->simplePaginate(5);

        return view('order.admin.data', compact("orders"));
    }

    public function create()
    {
        $medicines = Medicine::all();
        return view('order.kasir.form', compact('medicines'));
    }

    public function store(Request $request)
    {
        // Validasi request
        $request->validate([
            'name_customer' => 'required',
            'medicines' => 'required|array',
        ]);

        // Menghitung jumlah setiap item dalam array medicines
        $arrayValues = array_count_values($request->medicines);
        $arrayNewMedicines = [];
        $medicines = Medicine::whereIn('id', array_keys($arrayValues))->get()->keyBy('id');

        foreach ($arrayValues as $key => $value) {
            $medicine = $medicines->get($key);

            if (!$medicine || $medicine->stock < $value) {
                return redirect()->back()->withInput()->with([
                    'failed' => 'Stok Obat Tidak Cukup',
                    'ValueBefore' => [
                        'name_customer' => $request->name_customer,
                        'medicines' => $request->medicines,
                    ],
                ]);
            }

            $medicine->stock -= $value;
            $medicine->save();

            $totalPrice = $medicine->price * $value;
            $arrayNewMedicines[] = [
                'id' => $key,
                'name_medicine' => $medicine->name,
                'qty' => $value,
                'price' => $medicine->price,
                'total_price' => $totalPrice,
            ];
        }

        // Total harga sebelum dan setelah PPN
        $total = array_reduce($arrayNewMedicines, fn($sum, $item) => $sum + $item['total_price'], 0);
        $totalWithPpn = $total * 1.1;

        // Simpan order ke database
        $order = Order::create([
            'user_id' => Auth::id(),
            'medicines' => $arrayNewMedicines,
            'name_customer' => $request->name_customer,
            'total_price' => $totalWithPpn,
        ]);

        // Redirect to the print page for the newly created order
        return redirect()->route('kasir.print', ['id' => $order->id])->with('success', 'Order berhasil disimpan!');
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('order.kasir.print', compact('order'));
    }

    public function downloadPDF($id)
    {
        // Ambil order dengan ID tertentu
        $order = Order::where('id', $id)->first();  // Menggunakan where dan first

        // Periksa apakah $order ditemukan
        if (!$order) {
            // Kembalikan respons jika order tidak ditemukan
            abort(404, 'Order tidak ditemukan');
        }

        // Share data order ke view
        view()->share('order', $order);

        // Generate PDF menggunakan view yang sesuai
        $pdf = Pdf::loadView('order.kasir.pdf', ['order' => $order]);

        // Proses download PDF dengan nama file
        return $pdf->download('struk-pembelian.pdf');
    }


    // public function downloadPDF($id)
    // {
    //     // Ambil order dengan ID tertentu
    //     $order = Order::findOrFail($id);  // Mengambil model langsung

    //     // Share data order ke view
    //     view()->share('order', $order);

    //     // Generate PDF menggunakan view yang sesuai
    //     $pdf = Pdf::loadView('order.kasir.pdf', ['order' => $order]);

    //     // Proses download PDF dengan nama file
    //     return $pdf->download('struk-pembelian.pdf');
    // }
}
