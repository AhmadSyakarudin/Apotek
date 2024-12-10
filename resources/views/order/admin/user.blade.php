@extends('layouts.layout')

@section('content')
    @if (Session::get('success'))
        <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
    @if (Session::get('deleted'))
        <div class="alert alert-danger">{{ Session::get('deleted') }}</div>
    @endif

    <div class="container">

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('akun.create') }}" class="btn btn-secondary">Tambah Pengguna</a>
        </div>

        <a href="{{ route('user.admin.export') }}" class="btn btn-success">Export Excel</a>

        <table class="table table-bordered table-stripped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach ($users as $item)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->email }}</td>
                        <td>{{ $item->role }}</td>
                        <td class="d-flex justify-content-center">
                            <a href="{{ route('akun.edit', $item->id) }}" class="btn btn-primary me-3">Edit</a>
                            <button class="btn btn-danger btn-sm"
                                onclick="showModal('{{ $item->id }}', '{{ $item->name }}')">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal Hapus -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form id="form-delete-akun" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Hapus Pengguna</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Apakah anda yakin ingin menghapus pengguna <span id="nama-user"></span>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                            <button type="submit" class="btn btn-danger" id="confirm-delete">Hapus</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script>
        function showModal(id, name) {
            let urlDelete = '{{ route('akun.delete', ':id') }}';
            urlDelete = urlDelete.replace(":id", id);
            $('#form-delete-akun').attr('action', urlDelete);
            $('#nama-user').text(name);
            $('#exampleModal').modal('show');
        }
    </script>
@endpush
