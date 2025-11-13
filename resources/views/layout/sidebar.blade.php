<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                   href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>DATA MASTER</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('master.barang*') ? 'active' : '' }}" 
                   href="{{ route('master.barang') }}">
                    <i class="bi bi-box-seam"></i>
                    Data Barang
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('master.satuan*') ? 'active' : '' }}" 
                   href="{{ route('master.satuan') }}">
                    <i class="bi bi-rulers"></i>
                    Data Satuan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('master.vendor*') ? 'active' : '' }}" 
                   href="{{ route('master.vendor') }}">
                    <i class="bi bi-truck"></i>
                    Data Vendor
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('master.user') ? 'active' : '' }}" 
                   href="{{ route('master.user') }}">
                    <i class="bi bi-people"></i>
                    Data User
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('master.role') ? 'active' : '' }}" 
                   href="{{ route('master.role') }}">
                    <i class="bi bi-person-badge"></i>
                    Data Role
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('master.margin*') ? 'active' : '' }}" 
                   href="{{ route('master.margin') }}">
                    <i class="bi bi-percent"></i>
                    Data Margin
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>TRANSAKSI</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('transaksi.pengadaan') ? 'active' : '' }}" 
                   href="{{ route('transaksi.pengadaan') }}">
                    <i class="bi bi-clipboard-data"></i>
                    Pengadaan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('transaksi.penerimaan') ? 'active' : '' }}" 
                   href="{{ route('transaksi.penerimaan') }}">
                    <i class="bi bi-box-arrow-in-down"></i>
                    Penerimaan Barang
                </a>
            </li>
        </ul>
        
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>LAPORAN</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('laporan.penjualan') ? 'active' : '' }}" 
                   href="{{ route('laporan.penjualan') }}">
                    <i class="bi bi-file-earmark-text"></i>
                    Laporan Penjualan
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('laporan.kartu.stok') ? 'active' : '' }}" 
                   href="{{ route('laporan.kartu.stok') }}">
                    <i class="bi bi-card-checklist"></i>
                    Kartu Stok
                </a>
            </li>
        </ul>
    </div>
</nav>