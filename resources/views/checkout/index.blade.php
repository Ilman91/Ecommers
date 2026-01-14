@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <h1 class="h2 mb-5 fw-bold text-dark">
                <i class="bi bi-shield-check text-success me-2"></i>Checkout
            </h1>

            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf

                <div class="row g-4">
                    {{-- Sisi Kiri: Form Alamat --}}
                    <div class="col-lg-7">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-4">Informasi Pengiriman</h5>

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="name" class="form-label small fw-bold text-muted">Nama Lengkap Penerima</label>
                                        <input type="text" name="name" id="name" 
                                               class="form-control form-control-lg rounded-3 fs-6"
                                               value="{{ auth()->user()->name }}" placeholder="Contoh: Budi Santoso" required>
                                    </div>

                                    <div class="col-12">
                                        <label for="phone" class="form-label small fw-bold text-muted">Nomor WhatsApp/Telepon</label>
                                        <div class="input-group">
                                            <input type="text" name="phone" id="phone" 
                                                   class="form-control form-control-lg rounded-3 fs-6" 
                                                   placeholder="081234567xxx" required>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="address" class="form-label small fw-bold text-muted">Alamat Lengkap (Jalan, No. Rumah, Kel/Kec)</label>
                                        <textarea name="address" id="address" rows="4" 
                                                  class="form-control rounded-3 fs-6" 
                                                  placeholder="Tuliskan alamat lengkap pengiriman..." required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sisi Kanan: Ringkasan --}}
                    <div class="col-lg-5">
                        <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 2rem;">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>

                                <div class="mb-4" style="max-height: 400px; overflow-y: auto;">
                                    @php $grandTotal = 0; @endphp
                                    @foreach($cart->items as $item)
                                        @php
                                            $price = ($item->product->discount_price > 0) ? $item->product->discount_price : $item->product->price;
                                            $subtotal = $price * $item->quantity;
                                            $grandTotal += $subtotal;
                                        @endphp
                                        <div class="d-flex align-items-center mb-3">
                                            {{-- Gambar Produk Tanpa Badge --}}
                                            <div class="flex-shrink-0">
                                                <img src="{{ $item->product->image_url }}" 
                                                    class="rounded-3 border" 
                                                    width="55" height="55" 
                                                    style="object-fit: cover;">
                                            </div>

                                            <div class="ms-3 flex-grow-1" style="min-width: 0;"> {{-- min-width: 0 penting untuk text-truncate --}}
                                                <div class="small fw-bold text-dark text-truncate mb-0">
                                                    {{ $item->product->name }}
                                                </div>
                                                <div class="text-muted" style="font-size: 0.75rem;">
                                                    @if($item->product->has_discount)
                                                        <span class="text-decoration-line-through me-1">Rp{{ number_format($item->product->price, 0, ',', '.') }}</span>
                                                        <span class="text-danger fw-bold">Rp{{ number_format($item->product->discount_price, 0, ',', '.') }}</span>
                                                    @else
                                                        <span class="fw-bold">Rp{{ number_format($item->product->price, 0, ',', '.') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="border-top pt-3">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Subtotal</span>
                                        <span class="fw-bold">Rp{{ number_format($grandTotal, 0, ',', '.') }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-4">
                                        <span class="h5 fw-bold">Total Bayar</span>
                                        <span class="h4 fw-bold text-danger">
                                            Rp{{ number_format($grandTotal, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <button type="submit" class="btn btn-danger btn-lg w-100 rounded-pill shadow fw-bold py-3">
                                        Buat Pesanan Sekarang
                                    </button>
                                    
                                    <p class="text-center small text-muted mt-3 mb-0">
                                        <i class="bi bi-patch-check me-1"></i> Transaksi Aman & Terenkripsi
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection