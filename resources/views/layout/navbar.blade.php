<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
        @if (Auth::user())
        <a href="{{ url('/') }}" class="navbar-brand">
            {{-- <img src="{{ asset('images/pakkasir-logo.png') }}" alt="logo" class="brand-image img-circle elevation-3"
                style="opacity: .8"> --}}
            <span class="brand-text font-weight-light">TERUM</span>
        </a>
        @else
        <a href="{{ url('/pesan/meja/'.$id_meja) }}" class="navbar-brand">
            {{-- <img src="{{ asset('images/pakkasir-logo.png') }}" alt="logo" class="brand-image img-circle elevation-3"
                style="opacity: .8"> --}}
            <span class="brand-text font-weight-light">TERUM</span>
        </a>
        @endif

        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-3" id="navbarCollapse">

            <ul class="navbar-nav">
                @if (Auth::user())
                <li class="nav-item">
                    <a href="{{ url('/') }}" class="nav-link">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                        class="nav-link dropdown-toggle">Kelola Produk</a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                        <li>
                            <a tabindex="-1" href="{{ route('tambah.produk') }}" class="dropdown-item">Tambah
                                produk</a>
                        </li>
                        <li>
                            <a tabindex="-1" href="{{ route('daftar.produk') }}" class="dropdown-item">Daftar
                                produk</a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('master.meja') }}" class="nav-link">Master Meja</a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('laporan') }}" class="nav-link">Laporan</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pesanan') }}" class="nav-link">Pesanan</a>
                </li>
                @else
                <li class="nav-item">
                    <a href="{{ url('/pesan/no_meja/'.$id_meja) }}" class="nav-link">Menu</a>
                </li>
                @endif
            </ul>
        </div>


        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
            <li class="nav-item">
                @if (Auth::user())
                <a href="{{ url('cart/meja/NULL') }}" class="nav-link"><i class="fas fa-shopping-cart mr-2"></i>
                    @if ($keranjang->keranjang <= 0) @else <span class="badge badge-warning navbar-badge">{{
                        $keranjang->keranjang }}</span>
                        @endif
                </a>
                @else
                <a href="{{ url('cart/meja/'.$id_meja) }}" class="nav-link"><i class="fas fa-shopping-cart mr-2"></i>
                    @if ($keranjang->keranjang <= 0) @else <span class="badge badge-warning navbar-badge">{{
                        $keranjang->keranjang }}</span>
                        @endif
                </a>
                @endif
            </li>
            @if (Auth::user())
            <li class="nav-item">
                <a href="{{ route('logout') }}" class="nav-link">Logout</a>
            </li>
            @else
            @endif
        </ul>
    </div>
</nav>