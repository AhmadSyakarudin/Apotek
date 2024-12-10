@extends('layouts.layout')

@section('content')
    @if (Session::get('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
    <div class="container">
        <form action="{{ route('obat.data') }}" method="GET" class="me-2">
            <input type="hidden" name="sort_column" value="stock">
            <button class="btn btn-primary" name="sort_direction" value="ASC" type="submit">Urutkan Stock Terkecil -
                Terbesar</button>
            <button class="btn btn-primary" name="sort_direction" value="DESC" type="submit">Urutkan Stock Terbesar -
                Terkecil</button>
        </form>

        <form class="d-flex" role="search" action="{{ route('obat.data') }}" method="GET">
            {{-- <input type="text" class="form-control me-2" placeholder="Search Data Obat" aria-label="Search"
            name="search_obat"> --}}
            <input type="text" class="form-control me-2" placeholder="Search Data Obat"
                aria-label="Search" name="search_obat">
            <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
        <table class="table table-bordered table-stripped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Obat</th>
                    <th>Tipe</th>
                    <th>Harga</th>
                    <th>Stock</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if (count($medicines) < 1)
                    <tr>
                        <td colspan="6" class="text-center">Data Obat Kosong</td>
                    </tr>
                @else
                    @foreach ($medicines as $index => $item)
                        <tr>
                            <td>{{ ($medicines->currentPage() - 1) * $medicines->perPage() + ($index + 1) }}</td>
                            <td>{{ $item['name'] }}</td>
                            <td>{{ $item['type'] }}</td>
                            <td>Rp. {{ number_format($item['price'], 0, ',', '.') }}</td>
                            <td class="{{ $item['stock'] <= 3 ? 'bg-danger text-white' : '' }}" style="cursor: pointer" onclick="showModalStock('{{ $item->id }}', '{{ $item->stock }}')">
                                {{ $item['stock'] }}</td>
                            <td class="d-flex">
                                <a href="{{ route('obat.edit', $item['id']) }}" class="btn btn-primary me-3">Edit</a>
                                <button class="btn btn-danger btn-sm"
                                    onclick="showModal('{{ $item['id'] }}', '{{ $item['name'] }}')">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        {{-- modal hapus --}}
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="form-delete-obat" method="POST">
                    @csrf
                    {{-- menimpa method="POST" diganti menjadi delete, sesuai dengan http
                method untul menghapus data- --}}
                    @method('DELETE')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Hapus Data Obat</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Apakah anda yakin ingin menghapus obat <span id="nama-obat"></span>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                            <button type="submit" class="btn btn-danger" id="confirm-delete">Hapus</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- modal edit stok --}}
        <div class="modal fade" id="modal_edit_stock" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="form_edit_stock" method="POST">
                    @csrf
                    {{-- menimpa method="POST" diganti menjadi delete, sesuai dengan http
                method untul menghapus data- --}}
                    @method('PATCH')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Edit Stok Obat</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="stok_edit" class="form-label">Stok :</label>
                                <input type="number" class="form-control" id="stok_edit" name="stock">
                                @if (Session::get('failed'))
                                    <small class="text-danger">{{ Session::get('failed') }}</small>
                                @endif
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                            <button type="submit" class="btn btn-danger" id="confirm-delete">Edit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- links : memunculkan button pagination --}}
        <div class="d-flex justify-content-end">{{ $medicines->links() }}</div>
    @endsection

    @push('script')
        <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
            integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
        </script>
        <script>
            function showModal(id, name) {
                // ini untuk url delete nya (Route)
                let urlDelete = '{{ route('obat.hapus', ':id') }}';
                urlDelete = urlDelete.replace(":id", id);
                // ini untuk action atribut nya
                $('#form-delete-obat').attr('action', urlDelete);
                // ini untuk show modalnya
                $('#exampleModal').modal('show');
                // ini untuk mengisi modalnya
                $('#nama-obat').text(name);
            }

            function showModalStock(id, stock) {
                // mengisi stock yang dikirim ke input yg id nya stok_edit
                $('#stok_edit').val(stock);
                // ambil route patch stok
                let url = "{{ route('obat.edit.stok', ':id') }}";
                // isi path dinamis :id dengan id dr parameter ($item->id)
                url = url.replace(":id", id);
                // url td kirim ke action
                $('#form_edit_stock').attr('action', url);
                // show modal
                $("#modal_edit_stock").modal("show");
            }
            // if menggunakan @ karna session
            @if(Session::get('failed'))
                // jika halaman html nya sudah selesai load cdn, jalankan didalamnya
                $(document).ready(function() {
                    // id dari with failed 'id' controller redirect back
                    let id = "{{ Session::get('id') }}";
                    // stock dari with failed 'stock' controller redirect back
                    let stock = "{{ Session::get('stock') }}";
                    // panggil func showModalStock dengan data id dan stock diatas
                    showModalStock(id, stock);
                });
            @endif
        </script>
    @endpush
