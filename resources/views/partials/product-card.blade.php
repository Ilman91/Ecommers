{{-- ================================================
     FILE: resources/views/partials/product-card.blade.php
     FUNGSI: Komponen kartu produk yang reusable
     ================================================ --}}

<style>
    /* Badge Diskon Melayang */
    .badge-discount {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: rgb(215, 78, 78);
        color: white;
        padding: 4px 10px;
        border-radius: 5px;
        font-weight: bold;
        font-size: 0.8rem;
        z-index: 2;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    /* Kunci warna teks agar tidak terpengaruh stretched-link biru */
    .price-text-dark {
        color: #212529 !important; /* Hitam pekat Bootstrap */
    }
    
    .price-text-danger {
        color: rgb(215, 78, 78) !important; /* Merah brand kita */
    }

    .btn-light.wishlist-btn-{{ $product->id }} {
        z-index: 3;
        border: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    /* SELEKTOR SPESIFIK: Mengunci warna harga agar tetap hitam/merah 
       meskipun berada di dalam atau di dekat stretched-link */
    .product-card .price-text-dark,
    .product-card a.stretched-link ~ .mt-auto .price-text-dark {
        color: #212529 !important;
        text-decoration: none !important;
    }
    
    .product-card .price-text-danger,
    .product-card a.stretched-link ~ .mt-auto .price-text-danger {
        color: rgb(215, 78, 78) !important;
        text-decoration: none !important;
    }

    /* Memastikan link nama produk tidak berwarna biru secara default */
    .product-card .card-title a {
        color: #212529 !important;
    }
</style>
     
<div class="card product-card h-100 border-0 shadow-sm">
    <div class="position-relative">
        <a href="{{ route('catalog.show', $product->slug) }}">
            <img src="{{ $product->image_url }}"
                 class="card-img-top"
                 alt="{{ $product->name }}"
                 style="height: 200px; object-fit: cover;">
        </a>

        @if($product->has_discount)
            <span class="badge-discount">
                -{{ $product->discount_percentage }}%
            </span>
        @endif

        @auth
            <button type="button"
                    onclick="toggleWishlist({{ $product->id }})"
                    class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 rounded-circle wishlist-btn-{{ $product->id }}">
                <i class="bi {{ auth()->user()->hasInWishlist($product) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
            </button>
        @endauth
    </div>

    <div class="card-body d-flex flex-column">
        <small class="text-muted mb-1">{{ $product->category->name }}</small>

        <h6 class="card-title mb-2">
            <a href="{{ route('catalog.show', $product->slug) }}"
               class="text-decoration-none text-dark stretched-link">
                {{ Str::limit($product->name, 40) }}
            </a>
        </h6>

        <div class="mt-auto">
            @if($product->has_discount)
                <small class="text-muted text-decoration-line-through">
                    {{ $product->formatted_original_price }}
                </small>
                {{-- Pakai STYLE langsung di tag agar tidak bisa didebat browser --}}
                <div class="fw-bold fs-5" style="color: #d74e4e !important; text-decoration: none !important;">
                    {{ $product->formatted_price }}
                </div>
            @else
                {{-- WARNA HITAM PEKAT --}}
                <div class="fw-bold fs-5" style="color: #212529 !important; text-decoration: none !important;">
                    {{ $product->formatted_price }}
                </div>
            @endif
        </div>

        @if($product->stock <= 5 && $product->stock > 0)
            <small class="text-warning mt-2">
                <i class="bi bi-exclamation-triangle"></i>
                Stok tinggal {{ $product->stock }}
            </small>
        @elseif($product->stock == 0)
            <small class="text-danger mt-2">
                <i class="bi bi-x-circle"></i> Stok Habis
            </small>
        @endif
    </div>

    <div class="card-footer bg-white border-0 pt-0">
        <a href="{{ route('catalog.show', $product->slug) }}" 
           class="btn btn-light btn-outline-dark btn-sm w-100 @if($product->stock == 0) disabled @endif"
           style="border-radius: 8px; font-weight: 600; position: relative; z-index: 4;">
            <i class="bi bi-eye me-1"></i>
            @if($product->stock == 0) Stok Habis 
            @else
             Lihat Detail @endif
        </a>
    </div>
</div>