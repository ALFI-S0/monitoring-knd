<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    {{-- Logo --}}
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-link">
            <span class="brand-text fw-semibold">Monitoring KND</span>
        </a>
    </div>

    {{-- Sidebar Wrapper --}}
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false">

                {{-- 1. Dashboard (Bisa diakses oleh SEMUA user) --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- 2. Kendaraan (HANYA jika departemen_id BUKAN 2 / Ekspedisi) --}}
                @if(auth()->check() && auth()->user()->departemen_id != 2)
                    <li class="nav-item {{ request()->routeIs('list', 'kendaraan.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('list', 'kendaraan.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-car-front-fill"></i>
                            <p>
                                Kendaraan
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('list') }}"
                                    class="nav-link {{ request()->routeIs('list') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-list-ul"></i>
                                    <p>Data Kendaraan</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                {{-- 3. Perbaikan Kendaraan --}}
                <li class="nav-item {{ request()->routeIs('perbaikan.*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs('perbaikan.*') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-tools"></i>
                        <p>
                            Perbaikan Kendaraan
                            <i class="nav-arrow bi bi-chevron-right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        {{-- Tambah Perbaikan HANYA TAMPIL jika BUKAN Ekspedisi (departemen_id != 2) --}}
                        @if(auth()->check() && auth()->user()->departemen_id != 2)
                            <li class="nav-item">
                                <a href="{{ route('perbaikan.create') }}"
                                    class="nav-link {{ request()->routeIs('perbaikan.create') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-plus-circle"></i>
                                    <p>Tambah Perbaikan</p>
                                </a>
                            </li>
                        @endif

                        {{-- Data Perbaikan BISA DIAKSES OLEH SEMUA TERMASUK EKSPEDISI --}}
                        <li class="nav-item">
                            <a href="{{ route('perbaikan.index') }}"
                                class="nav-link {{ request()->routeIs('perbaikan.index') ? 'active' : '' }}">
                                <i class="nav-icon bi bi-wrench-adjustable-circle"></i>
                                <p>Data Perbaikan</p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- 4. Master Data (HANYA TAMPIL UNTUK DEPARTEMEN ID 3) --}}
                @if(auth()->check() && auth()->user()->departemen_id == 3)
                    <li class="nav-item {{ request()->routeIs('masterdata.*', 'users.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('masterdata.*', 'users.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-database-gear"></i>
                            <p>
                                Master Data
                                <i class="nav-arrow bi bi-chevron-right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            {{-- User --}}
                            <li class="nav-item">
                                <a href="{{ route('masterdata.user') }}"
                                    class="nav-link {{ request()->routeIs('masterdata.user*', 'users.*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-people-fill"></i>
                                    <p>User</p>
                                </a>
                            </li>
                            {{-- Departemen --}}
                            <li class="nav-item">
                                <a href="{{ route('masterdata.departemen') }}"
                                    class="nav-link {{ request()->routeIs('masterdata.departemen*') ? 'active' : '' }}">
                                    <i class="nav-icon bi bi-building"></i>
                                    <p>Departemen</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

            </ul>
        </nav>
    </div>
</aside>