{{-- ================================================
     FILE: resources/views/partials/navbar.blade.php
     FUNGSI: Navigation bar untuk customer
     ================================================ --}}

<style>
    /* Gunakan variabel supaya gampang ganti warna nanti */
    :root {
        --brand-red: rgb(215, 78, 78);
        --brand-pink: rgb(251, 186, 186);
    }

    .logo-brand {
        color: #000;
        font-weight: 700;
        transition: color 0.3s ease;
    }
    
    .logo-brand:hover {
        color: var(--brand-red);
    }

    /* Icon hati kita bikin merah biar identik sama logo di login */
    .logo-brand i {
        color: var(--brand-red);
    }

    .search {
        border: 2px solid #000;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    /* Fokus search bar kita bikin soft pink biar estetik */
    .search:focus {
        border-color: var(--brand-pink);
        box-shadow: 0 0 0 0.2rem rgba(215, 78, 78, 0.15);
        outline: none;
    }

    .search-btn {
        border: 2px solid #000;
        border-left: none; /* Biar nyambung sama input */
        background-color: #000;
        color: #fff;
        transition: all 0.3s ease;
    }

    .search-btn:hover {
        background-color: var(--brand-red);
        border-color: var(--brand-red);
        color: #fff;
    }

    /* Warna navigasi */
    .nav-link {
        font-weight: 500;
        color: #333 !important;
    }

    .nav-link:hover {
        color: var(--brand-red) !important;
    }

    /* Tombol Daftar disesuaikan dengan warna tema */
    .btn-register-nav {
        background-color: var(--brand-red);
        border: none;
        padding: 8px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-register-nav:hover {
        background-color: #a33b3b;
        transform: translateY(-1px);
    }
    /* Ganti warna background pas menu dropdown di-hover atau diklik */
    .dropdown-item:hover, 
    .dropdown-item:focus,
    .dropdown-item:active {
        background-color: var(--brand-pink); /* Pink soft yang kita buat tadi */
        color: #fff1f1 !important;   /* Teksnya jadi merah */
    }

    /* Khusus untuk menu logout biar pas di-hover tetep kerasa 'bahaya' */
    .dropdown-item.text-danger:hover {
        background-color: #fff1f1; /* Merah sangat muda */
        color: #dc3545 !important;
    }

    /* Biar transisinya halus, nggak langsung jepret berubah warna */
    .dropdown-item {
        transition: all 0.2s ease-in-out;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top py-3">
    <div class="container">
        {{-- Logo & Brand --}}
        <a class="navbar-brand logo-brand d-flex align-items-center" href="{{ route('home') }}">
            <i class="bi bi-box2-heart fs-3 me-2"></i>
            <span style="letter-spacing: -1px;">Kita<span style="color: var(--brand-red);">Elektronik</span></span>
        </a>

        {{-- Mobile Toggle --}}
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Navbar Content --}}
        <div class="collapse navbar-collapse" id="navbarMain">
            {{-- Search Form --}}
            <form class="d-flex mx-auto mt-3 mt-lg-0" style="max-width: 450px; width: 100%;" action="{{ route('catalog.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="q" class="form-control search" placeholder="Cari elektronik..." value="{{ request('q') }}">
                    <button class="btn search-btn px-3" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            {{-- Right Menu --}}
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link px-3" href="{{ route('catalog.index') }}">Katalog</a>
                </li>

                @auth
                    {{-- Wishlist --}}
                    <li class="nav-item">
                        <a class="nav-link position-relative px-3" href="{{ route('wishlist.index') }}">
                            <i class="bi bi-heart fs-5"></i>
                            
                            @php $wCount = auth()->user()->wishlists()->count(); @endphp
                            {{-- ID ini hukumnya WAJIB ada supaya JS bisa nemu targetnya --}}
                            <span id="wishlist-count" 
                                class="position-absolute top-0 start-50 badge rounded-pill bg-danger" 
                                style="font-size: 0.5rem; {{ $wCount > 0 ? '' : 'display: none;' }}">
                                {{ $wCount }}
                            </span>
                        </a>
                    </li>

                    {{-- Cart --}}
                    <li class="nav-item">
                        <a class="nav-link position-relative px-3" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart3 fs-5"></i>
                            @php $cartCount = auth()->user()->cart?->items()->count() ?? 0; @endphp
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-50 badge rounded-pill bg-dark" style="font-size: 0.5rem;">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    {{-- User Dropdown --}}
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle border me-2" width="35" height="35">
                            <span class="d-none d-lg-inline">{{ explode(' ', auth()->user()->name)[0] }}</span> {{-- Panggil nama depan aja biar ringkas --}}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                            <i class="bi bi-person me-2 text-muted"></i> Profil Saya
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item py-2" href="{{ route('orders.index') }}">
                            <i class="bi bi-bag me-2 text-muted"></i> Pesanan Saya
                        </a>
                    </li>
                    
                    @if(auth()->user()->isAdmin())
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i> Admin Panel
                            </a>
                        </li>
                    @endif
                    
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger py-2">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link px-3 fw-bold" href="{{ route('login') }}">Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-register-nav ms-lg-2 text-white" href="{{ route('register') }}">Daftar</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>