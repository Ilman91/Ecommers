{{-- resources/views/catalog/show.blade.php --}}
@extends('layouts.app')

@section('title', $product->name . ' - KitaElektronik')

@section('content')
<div class="container py-4">
    {{-- Breadcrumb Clean --}}
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent p-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}" class="text-decoration-none">Katalog</a></li>
            <li class="breadcrumb-item active text-truncate" style="max-width: 200px;">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- SISI KIRI: Image Gallery --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm sticky-lg-top" style="top: 100px; border-radius: 20px; overflow: hidden;">
                {{-- Main Preview --}}
                <div class="main-img-container p-4 bg-white text-center">
                    <img src="{{ $product->image_url }}"
                         id="main-image"
                         class="img-fluid"
                         alt="{{ $product->name }}"
                         style="max-height: 450px; width: auto; object-fit: contain; transition: 0.3s;">
                    
                    @if($product->has_discount)
                        <span class="badge bg-danger position-absolute top-0 start-0 m-4 px-3 py-2 shadow-sm" style="border-radius: 10px;">
                            <i class="bi bi-tag-fill me-1"></i> Hemat {{ $product->discount_percentage }}%
                        </span>
                    @endif
                </div>

                {{-- Thumbnails --}}
                @if($product->images->count() > 1)
                <div class="card-footer bg-white border-0 p-3">
                    <div class="d-flex gap-2 overflow-auto pb-2 custom-scrollbar">
                        {{-- Gambar Utama --}}
                        <img src="{{ $product->image_url }}"
                             class="thumb-img rounded border p-1 active"
                             onclick="changeImg(this)"
                             style="width: 70px; height: 70px; object-fit: cover; cursor: pointer;">
                        
                        @foreach($product->images as $image)
                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                 class="thumb-img rounded border p-1"
                                 onclick="changeImg(this)"
                                 style="width: 70px; height: 70px; object-fit: cover; cursor: pointer;">
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- SISI KANAN: Product Detail --}}
        <div class="col-lg-6">
            <div class="ps-lg-4">
                <div class="mb-2">
                    <a href="{{ route('catalog.index', ['category' => $product->category->slug]) }}"
                       class="text-danger text-decoration-none fw-bold small text-uppercase tracking-wider">
                        {{ $product->category->name }}
                    </a>
                </div>

                <h1 class="fw-bold mb-3" style="color: #2d3436;">{{ $product->name }}</h1>

                {{-- Price Section --}}
                <div class="price-box mb-4 p-3 bg-light rounded-4">
                    @if($product->has_discount)
                        <div class="text-muted text-decoration-line-through small mb-1">
                            {{ $product->formatted_original_price }}
                        </div>
                    @endif
                    <div class="d-flex align-items-center gap-2">
                        <span class="h2 text-danger fw-bold mb-0">{{ $product->formatted_price }}</span>
                        @if($product->stock > 0)
                            <span class="badge bg-success-subtle text-success border border-success-subtle">Tersedia</span>
                        @endif
                    </div>
                </div>

                {{-- Deskripsi Produk --}}
                <div class="mb-4">
                    <h6 class="fw-bold"><i class="bi bi-justify-left me-2"></i>Deskripsi Produk</h6>
                    <p class="text-muted mb-3" style="line-height: 1.8;">
                        {!! nl2br(e($product->description)) !!}
                    </p>

                    <hr class="my-4 opacity-50">

                    {{-- Info Berat & SKU --}}
                    <div class="row g-2 mt-2">
                        <div class="col-6">
                            <div class="p-2 border rounded-3 bg-light-subtle">
                                <small class="text-muted d-block">Berat Produk</small>
                                <span class="fw-bold small"><i class="bi bi-box-seam me-1 text-danger"></i> {{ $product->weight }} Gram</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 border rounded-3 bg-light-subtle">
                                <small class="text-muted d-block">Kode SKU</small>
                                <span class="fw-bold small"><i class="bi bi-qr-code me-1 text-danger"></i> PROD-{{ $product->id }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Action Box --}}
                <div class="action-card p-4 border rounded-4 bg-white shadow-sm mb-4">
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">Jumlah</label>
                                <div class="input-group border rounded-3 overflow-hidden">
                                    <button type="button" class="btn btn-light border-0" onclick="decrementQty()">-</button>
                                    <input type="number" name="quantity" id="quantity"
                                           value="1" min="1" max="{{ $product->stock }}"
                                           class="form-control border-0 text-center fw-bold" readonly>
                                    <button type="button" class="btn btn-light border-0" onclick="incrementQty()">+</button>
                                </div>
                            </div>
                            <div class="col-md-8 d-flex align-items-end">
                                <button type="submit" class="btn btn-danger btn-lg w-100 fw-bold shadow-sm"
                                        style="border-radius: 12px; background-color: #d74e4e;"
                                        @if($product->stock == 0) disabled @endif>
                                    <i class="bi bi-bag-plus-fill me-2"></i>
                                    {{ $product->stock == 0 ? 'Stok Habis' : 'Masukkan Keranjang' }}
                                </button>
                            </div>
                        </div>
                    </form>

                    @auth
                    <div class="mt-3 text-center">
                        <button type="button" onclick="toggleWishlist({{ $product->id }})"
                                class="btn btn-link text-danger text-decoration-none fw-semibold btn-sm wishlist-btn-{{ $product->id }}">
                            {{-- Ikon --}}
                            <i class="bi {{ auth()->user()->hasInWishlist($product) ? 'bi-heart-fill text-danger' : 'bi-heart text-secondary' }} me-1"></i>
                            
                            {{-- Teks (Kita kasih class supaya bisa diubah JS) --}}
                            <span class="wishlist-text-{{ $product->id }}">
                                {{ auth()->user()->hasInWishlist($product) ? 'Hapus dari Wishlist' : 'Simpan ke Wishlist' }}
                            </span>
                        </button>
                    </div>
                    @endauth
                </div>

                {{-- Info Tambahan --}}
                <div class="card border-0 bg-light rounded-4">
                    <div class="card-body p-3">
                        <div class="row text-center g-2">
                            <div class="col-4">
                                <div class="p-2">
                                    <i class="bi bi-shield-check text-primary fs-4"></i>
                                    <p class="small mb-0 mt-1">Garansi Resmi</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 border-start border-end">
                                    <i class="bi bi-truck text-primary fs-4"></i>
                                    <p class="small mb-0 mt-1">Pengiriman Aman</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-2">
                                    <i class="bi bi-arrow-repeat text-primary fs-4"></i>
                                    <p class="small mb-0 mt-1">7 Hari Retur</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .thumb-img:hover, .thumb-img.active {
        border-color: #d74e4e !important;
        opacity: 0.8;
    }
    .custom-scrollbar::-webkit-scrollbar { height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 10px; }
</style>

@push('scripts')
<script>
    function changeImg(element) {
        document.getElementById('main-image').src = element.src;
        // Reset active state
        document.querySelectorAll('.thumb-img').forEach(img => img.classList.remove('active'));
        element.classList.add('active');
    }

    function incrementQty() {
        const input = document.getElementById('quantity');
        const max = parseInt(input.max);
        if (parseInt(input.value) < max) {
            input.value = parseInt(input.value) + 1;
        }
    }

    function decrementQty() {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) > 1) {
            input.value = parseInt(input.value) - 1;
        }
    }
</script>
@endpush
@endsection