<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        {{-- PROFILE --}}
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile">
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ auth()->user()->name }}</span>
                    <span class="text-secondary text-small">{{ auth()->user()->role }}</span>
                </div>
            </a>
        </li>

        {{-- DASHBOARD --}}
        <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>

        {{-- KATEGORI --}}
        <li class="nav-item {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kategori.index') }}">
                <span class="menu-title">Kategori</span>
                <i class="mdi mdi-shape menu-icon"></i>
            </a>
        </li>

        {{-- BUKU --}}
        <li class="nav-item {{ request()->routeIs('buku.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('buku.index') }}">
                <span class="menu-title">Buku</span>
                <i class="mdi mdi-book-open-page-variant menu-icon"></i>
            </a>
        </li>

        {{-- SERTIFIKAT --}}
        <li class="nav-item {{ request()->routeIs('pdf.sertifikat') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pdf.sertifikat') }}" target="_blank">
                <span class="menu-title">Sertifikat</span>
                <i class="mdi mdi-certificate-outline menu-icon"></i>
            </a>
        </li>

        {{-- UNDANGAN --}}
        <li class="nav-item {{ request()->routeIs('pdf.undangan') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pdf.undangan') }}" target="_blank">
                <span class="menu-title">Undangan</span>
                <i class="mdi mdi-email-outline menu-icon"></i>
            </a>
        </li>

        {{-- TAG HARGA --}}
        <li class="nav-item {{ request()->routeIs('barang.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('barang.index') }}">
                <span class="menu-title">Tag Harga</span>
                <i class="mdi mdi-tag-outline menu-icon"></i>
            </a>
        </li>

        {{-- TABEL BIASA --}}
        <li class="nav-item {{ request()->routeIs('js.tabel_biasa') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('js.tabel_biasa') }}">
                <span class="menu-title">Tabel Biasa</span>
                <i class="mdi mdi-table menu-icon"></i>
            </a>
        </li>

        {{-- TABEL DATATABLES --}}
        <li class="nav-item {{ request()->routeIs('js.tabel_datatables') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('js.tabel_datatables') }}">
                <span class="menu-title">Tabel DataTables</span>
                <i class="mdi mdi-table-search menu-icon"></i>
            </a>
        </li>

        {{-- SELECT & SELECT2 --}}
        <li class="nav-item {{ request()->routeIs('js.select') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('js.select') }}">
                <span class="menu-title">Select & Select2</span>
                <i class="mdi mdi-form-select menu-icon"></i>
            </a>
        </li>

        {{-- WILAYAH AJAX --}}
        <li class="nav-item {{ request()->routeIs('js.wilayah_ajax') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('js.wilayah_ajax') }}">
                <span class="menu-title">Wilayah Ajax</span>
                <i class="mdi mdi-map-marker menu-icon"></i>
            </a>
        </li>

        {{-- WILAYAH AXIOS --}}
        <li class="nav-item {{ request()->routeIs('js.wilayah_axios') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('js.wilayah_axios') }}">
                <span class="menu-title">Wilayah Axios</span>
                <i class="mdi mdi-map-marker-outline menu-icon"></i>
            </a>
        </li>

        {{-- POS / KASIR --}}
        <li class="nav-item {{ request()->routeIs('pos.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pos.index') }}">
                <span class="menu-title">Point of Sales</span>
                <i class="mdi mdi-cash-register menu-icon"></i>
            </a>
        </li>

    </ul>
</nav>