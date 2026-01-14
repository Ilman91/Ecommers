{{-- resources/views/wishlist/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Wishlist Saya')

@section('content')
<div class="container py-5">
    {{-- Header Halaman --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 fw-bold mb-0">Wishlist Saya</h1>
        <span class="badge bg-light text-dark border p-2">
            <span id="wishlist-total-count">{{ $products->total() }}</span> Produk
        </span>
    </div>

    <div id="wishlist-container">
        @if($products->count())
            {{-- Grid Produk --}}
            <div class="row row-cols-2 row-cols-md-4 g-4" id="wishlist-grid">
                @foreach($products as $product)
                    <div class="col" id="wishlist-item-{{ $product->id }}">
                        {{-- 
                            KUNCINYA DI SINI: 
                            Kita panggil komponen kartu produk. 
                            Logika harga (Hitam/Merah) harusnya ada di dalam file:
                            resources/views/components/product-card.blade.php
                        --}}
                         <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>

            {{-- Navigasi Halaman (Pagination) --}}
            <div class="mt-5 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        @else
            {{-- Tampilan kalau Wishlist Kosong --}}
            <div class="text-center py-5 bg-light rounded-4 border border-dashed">
                <div class="mb-3">
                    <i class="bi bi-heart-break text-muted opacity-50" style="font-size: 4rem;"></i>
                </div>
                <h3 class="h5 fw-bold text-dark">Wishlist Masih Kosong</h3>
                <p class="text-muted">Simpan produk yang kamu incar di sini agar tidak lupa!</p>
                <a href="{{ route('catalog.index') }}" class="btn btn-danger mt-3 px-4 shadow-sm" style="border-radius: 10px;">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>
</div>

{{-- 
    Template Tersembunyi:
    Digunakan oleh JavaScript untuk mengganti konten halaman 
    menjadi "Kosong" secara realtime jika semua item dihapus.
--}}
<div id="empty-wishlist-template" class="d-none">
    <div class="text-center py-5 bg-light rounded-4 border border-dashed">
        <div class="mb-3">
            <i class="bi bi-heart-break text-muted opacity-50" style="font-size: 4rem;"></i>
        </div>
        <h3 class="h5 fw-bold text-dark">Wishlist Masih Kosong</h3>
        <p class="text-muted">Simpan produk yang kamu incar di sini agar tidak lupa!</p>
        <a href="{{ route('catalog.index') }}" class="btn btn-danger mt-3 px-4 shadow-sm" style="border-radius: 10px;">
            Mulai Belanja
        </a>
    </div>
</div>
@endsection