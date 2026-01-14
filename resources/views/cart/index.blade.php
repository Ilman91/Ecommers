{{-- resources/views/cart/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <div class="bg-danger text-white rounded-circle p-2 me-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
            <i class="bi bi-cart3 fs-4"></i>
        </div>
        <h2 class="fw-bold mb-0">Keranjang Belanja</h2>
    </div>

    @if($cart && $cart->items->count())
        @php
            // Hitung Grand Total menggunakan harga yang benar (Normal vs Diskon)
            $grandTotal = $cart->items->sum(function($item) {
                return ($item->product->has_discount ? $item->product->discount_price : $item->product->price) * $item->quantity;
            });
        @endphp

        <div class="row g-4">
            {{-- Daftar Item Keranjang --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light text-muted small uppercase">
                                <tr>
                                    <th class="ps-4 py-3">Produk</th>
                                    <th class="text-center py-3">Harga</th>
                                    <th class="text-center py-3">Jumlah</th>
                                    <th class="text-end pe-4 py-3">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart->items as $item)
                                    @php
                                        $hasDiscount = $item->product->has_discount;
                                        $currentPrice = $hasDiscount ? $item->product->discount_price : $item->product->price;
                                        $itemSubtotal = $currentPrice * $item->quantity;
                                    @endphp
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="position-relative">
                                                    <img src="{{ $item->product->image_url }}" 
                                                         class="rounded-3 border" 
                                                         width="70" height="70" 
                                                         style="object-fit: cover;">
                                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="position-absolute top-0 start-0 translate-middle">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm" 
                                                                style="width: 24px; height: 24px;" 
                                                                onclick="return confirm('Hapus produk ini?')">
                                                            <i class="bi bi-x" style="font-size: 18px;"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                <div class="ms-3">
                                                    <a href="{{ route('catalog.show', $item->product->slug) }}" class="text-decoration-none text-dark fw-bold mb-0 d-block">
                                                        {{ Str::limit($item->product->name, 35) }}
                                                    </a>
                                                    <span class="badge bg-light text-muted border-0 p-0" style="font-size: 0.75rem;">
                                                        {{ $item->product->category->name }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center py-3">
                                            @if($hasDiscount)
                                                <div class="text-muted small text-decoration-line-through">
                                                    Rp{{ number_format($item->product->price, 0, ',', '.') }}
                                                </div>
                                                <div class="fw-bold text-danger">
                                                    Rp{{ number_format($item->product->discount_price, 0, ',', '.') }}
                                                </div>
                                            @else
                                                <div class="fw-bold text-dark">
                                                    Rp{{ number_format($item->product->price, 0, ',', '.') }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="text-center py-3" style="min-width: 120px;">
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex justify-content-center">
                                                @csrf
                                                @method('PATCH')
                                                <div class="input-group input-group-sm border rounded-pill overflow-hidden" style="width: 100px;">
                                                    <input type="number" name="quantity" 
                                                           value="{{ $item->quantity }}" 
                                                           min="1" max="{{ $item->product->stock }}" 
                                                           class="form-control border-0 text-center fw-bold bg-white"
                                                           onchange="this.form.submit()">
                                                </div>
                                            </form>
                                        </td>
                                        <td class="text-end pe-4 py-3 fw-bold fs-6 text-dark">
                                            Rp{{ number_format($itemSubtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Pesanan --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-2">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Ringkasan Belanja</h5>
                        
                        <div class="d-flex justify-content-between mb-2 text-muted">
                            <span>Total Harga ({{ $cart->items->sum('quantity') }} barang)</span>
                            <span>Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                        
                        <hr class="my-4" style="border-style: dashed;">
                        
                        <div class="d-flex justify-content-between mb-4 align-items-center">
                            <span class="fw-bold fs-5">Total Bayar</span>
                            <span class="fw-bold text-danger fs-4">
                                Rp{{ number_format($grandTotal, 0, ',', '.') }}
                            </span>
                        </div>
                        
                        <a href="{{ route('checkout.index') }}" class="btn btn-danger w-100 btn-lg rounded-pill py-3 fw-bold mb-3 shadow-sm">
                            Lanjut ke Pembayaran
                        </a>
                        <a href="{{ route('catalog.index') }}" class="btn btn-link w-100 text-decoration-none text-muted">
                            <i class="bi bi-arrow-left me-2"></i>Tambah Produk Lain
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Tampilan Keranjang Kosong --}}
        <div class="text-center py-5">
            <div class="mb-4">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                    <i class="bi bi-cart-x text-muted" style="font-size: 5rem;"></i>
                </div>
            </div>
            <h3 class="fw-bold">Wah, keranjangmu masih kosong!</h3>
            <p class="text-muted mb-4">Yuk, cari barang impianmu dan mulai belanja sekarang.</p>
            <a href="{{ route('catalog.index') }}" class="btn btn-danger btn-lg px-5 rounded-pill shadow-sm">
                Mulai Belanja
            </a>
        </div>
    @endif
</div>
@endsection