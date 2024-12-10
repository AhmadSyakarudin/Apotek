{{-- Pemnggilan href/link :
    1. path (/) jika name route tidak di setting
    2. pake {{route('nama')}} jika routenya sudah diset namenya
--}}
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    {{-- {{asset()}} : mengambil file yang afa fi folder public --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/css/js/images/imagess.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    @stack('style')
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand" href="#">Apotek</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            @if (Auth::check())
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('landing_page') ? 'active' : '' }}" aria-current="page"
                                href="/">Dashboard</a>
                        </li>
                        @if (Auth::user()->role == 'admin')
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle {{ Route::is('obat.tambah') ? 'active' : '' }}"
                                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Obat
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('obat.data') }}">Data Obat</a></li>
                                    <li><a class="dropdown-item " href="{{ route('obat.tambah') }}">Tambah</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('akun.home') ? 'active' : '' }}"
                                href="{{ route('user.admin') }}">Kelola Akun</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('landing_page') ? '' : '' }}"
                                    href="{{ route('pembelian.admin') }}">Data Pembelian</a>
                            </li>
                        @endif

                        @if (Auth::user()->role == 'cashier')
                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('landing_page') ? '' : '' }}"
                                href="{{ route('kasir.order') }}">Pembelian</a>
                        </li>
                        @endif
                        @if (Auth::check())
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('logout') ? 'activate' : '' }}"
                                    href="/logout">Logout</a>
                            </li>
                        @endif
                    </ul>
            @endif
        </div>
        </div>
    </nav>

    @yield('content')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    @stack('script')
</body>

</html>
