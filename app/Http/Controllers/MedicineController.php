<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Menampilkan daftar obat dengan fitur pencarian dan pengurutan.
     */
    public function index(Request $request)
    {
        // Mendapatkan kolom pengurutan dan arah pengurutan, dengan default kolom 'name' dan arah 'ASC'
        $orderBy = $request->sort_column ?? 'name'; // Defaultnya berdasarkan nama
        $sortDirection = $request->sort_direction ?? 'ASC'; // Defaultnya ASC

        // Mencari obat berdasarkan input pencarian, dengan fitur pengurutan
        $medicines = Medicine::where('name', 'LIKE', '%' . $request->search_obat . '%')
            ->orderBy($orderBy, $sortDirection)
            ->simplePaginate(5)
            ->appends(request()->all()); // Mempertahankan query string pada pagination

        // Mengirim data obat ke view
        return view('medicine.index', compact('medicines'));
    }

    /**
     * Menampilkan form untuk menambahkan data obat.
     */
    public function create()
    {
        return view('medicine.create');
    }

    /**
     * Menambahkan data obat baru ke dalam database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|max:100',
            'type' => 'required|min:3',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ], [
            'name.required' => 'Nama Obat Harus Diisi',
            'type.required' => 'Tipe Obat Harus Diisi',
            'price.required' => 'Harga Obat Harus Diisi',
            'stock.required' => 'Stock Obat Harus Diisi',
            'name.max' => 'Nama Obat Maksimal 100 Karakter',
            'type.min' => 'Tipe Obat Harus Minimal 3 Karakter',
            'price.numeric' => 'Harga Obat Harus Menggunakan Angka',
            'stock.numeric' => 'Stock Obat Harus Menggunakan Angka',
        ]);

        // Menyimpan data obat ke database
        Medicine::create([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Berhasil menambahkan data obat');
    }

    /**
     * Menampilkan form untuk mengedit data obat.
     */
    public function edit(string $id)
    {
        $medicine = Medicine::find($id);
        return view('medicine.edit', compact('medicine'));
    }

    /**
     * Mengupdate data obat ke dalam database.
     */
    /**
 * Mengupdate data obat ke dalam database.
 */
public function update(Request $request, string $id)
{
    // Validasi input
    $request->validate([
        'name' => 'required|max:100',
        'type' => 'required|min:3',
        'price' => 'required|numeric',
    ], [
        'name.required' => 'Nama Obat Harus Diisi',
        'type.required' => 'Tipe Obat Harus Diisi',
        'price.required' => 'Harga Obat Harus Diisi',
        'name.max' => 'Nama Obat Maksimal 100 Karakter',
        'type.min' => 'Tipe Obat Harus Minimal 3 Karakter',
        'price.numeric' => 'Harga Obat Harus Menggunakan Angka',
    ]);

    // Mengupdate data obat
    Medicine::where('id', $id)->update([
        'name' => $request->name,
        'type' => $request->type,
        'price' => $request->price,
    ]);

    // Redirect dengan pesan sukses
    return redirect()->route('obat.data')->with('success', 'Berhasil mengupdate data obat');
}

// untuk modal tanpa ajax, tdk support validasi, jd gunakan isset untuk pengecekan required nya
    public function updateStock(Request $request, $id) {
        if(isset($request->stock) == FALSE) {
            $dataSebelumnya = Medicine::where('id', $id)->first();
            // kembali dengan pesan, id sebelumnya, dan stock sebelumnya (stock awal)
            return redirect()->back()->with([
                'failed' => 'Stock tidak boleh kosong',
                'id' => $id,
                'stock' => $dataSebelumnya->stock,
            ]);
        }
        // jika tidk kosong, langsung update stock
        Medicine::where('id', $id)->update([
            'stock' => $request->stock,
        ]);
        return redirect()->back()->with('success', 'Berhasil mengupdate stock obat');
    }


    /**
     * Menghapus data obat dari database.
     */
    public function destroy($id)
    {
        // Menghapus data obat
        $deleteData = Medicine::where('id', $id)->delete();

        if ($deleteData) {
            return redirect()->back()->with('success', 'Data obat berhasil dihapus');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus data obat');
        }
    }
}
