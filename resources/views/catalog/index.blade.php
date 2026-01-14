@extends('layouts.app')

@section('content')
{{-- Style khusus buat Katalog --}}
<style>
    :root {
        --brand-red: rgb(215, 78, 78);
        --brand-pink: rgb(251, 186, 186);
    }

    /* Ganti warna radio button jadi merah */
    .form-check-input:checked {
        background-color: var(--brand-red);
        border-color: var(--brand-red);
    }

    /* Tombol Terapkan Filter */
    .btn-apply {
        background-color: var(--brand-red);
        color: white;
        border: none;
        font-weight: 600;
        transition: 0.3s;
    }
    .btn-apply:hover {
        background-color: #a33b3b;
        color: white;
    }

    /* Pagination Merah */
    .pagination .page-item.active .page-link {
        background-color: var(--brand-red);
        border-color: var(--brand-red);
    }
    .pagination .page-link {
        color: var(--brand-red);
    }

    /* Sidebar Header */
    .filter-header {
        border-bottom: 2px solid var(--brand-pink);
    }
    
    .card-filter {
        border-radius: 12px;
        overflow: hidden;
    }
</style>

<div class="content-wrapper bg-light">
<div class="container py-5">
    <div class="row">
        {{-- SIDEBAR FILTER --}}
        <div class="col-lg-3 mb-4">
            <div class="card card-filter border-0 shadow-sm">
                <div class="card-header bg-white fw-bold py-3 filter-header">
                    <i class="bi bi-filter-left me-2"></i> Filter Produk
                </div>
                <div class="card-body">
                    <form action="{{ route('catalog.index') }}" method="GET">
                        @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif

                        {{-- Filter Kategori --}}
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 small text-uppercase text-muted">Kategori</h6>
                            @foreach($categories as $cat)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="category" value="{{ $cat->slug }}"
                                        id="cat-{{ $cat->id }}"
                                        {{ request('category') == $cat->slug ? 'checked' : '' }}
                                        onchange="this.form.submit()">
                                    <label class="form-check-label d-flex justify-content-between align-items-center w-100" for="cat-{{ $cat->id }}">
                                        {{ $cat->name }}
                                        <span class="badge bg-light text-dark rounded-pill" style="font-size: 0.7rem;">{{ $cat->products_count }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        {{-- Filter Harga --}}
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3 small text-uppercase text-muted">Rentang Harga</h6>
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text bg-white">Rp</span>
                                <input type="number" name="min_price" class="form-control" placeholder="Min" value="{{ request('min_price') }}">
                            </div>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white">Rp</span>
                                <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ request('max_price') }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-apply w-100 btn-sm py-2">Terapkan Filter</button>
                        <a href="{{ route('catalog.index') }}" class="btn btn-outline-secondary w-100 btn-sm mt-2 py-2 border-0">
                            Reset Filter
                        </a>
                    </form>
                </div>
            </div>
        </div>

        {{-- PRODUCT GRID --}}
        <div class="col-lg-9">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                <div>
                    <h4 class="mb-1 fw-bold">Katalog Produk</h4>
                    @if(request('q'))
                        <p class="text-muted mb-0 small">Hasil pencarian untuk: <strong>"{{ request('q') }}"</strong></p>
                    @endif
                </div>
                
                {{-- Sorting --}}
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small d-none d-md-block">Urutkan:</span>
                    <form method="GET" class="d-inline-block">
                        @foreach(request()->except('sort') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <select name="sort" class="form-select form-select-sm border-0 shadow-sm" style="min-width: 150px;" onchange="this.form.submit()">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                            {{-- Tambahan A-Z dan Z-A --}}
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama (A - Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama (Z - A)</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="row row-cols-2 row-cols-md-3 g-3 g-md-4">
                @forelse($products as $product)
                    <div class="col">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-search text-muted opacity-25" style="font-size: 5rem;"></i>
                        </div>
                        <h5 class="fw-bold">Produk tidak ditemukan</h5>
                        <p class="text-muted small">Coba hapus filter atau gunakan kata kunci lain.</p>
                        <a href="{{ route('catalog.index') }}" class="btn btn-sm btn-outline-danger px-4 mt-2">Lihat Semua Produk</a>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
</div>
@endsection