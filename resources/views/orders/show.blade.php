@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            
            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 fw-bold text-dark mb-1">Detail Pesanan</h1>
                    <p class="text-muted mb-0">Order #{{ $order->order_number }} • {{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
                <a href="{{ url('/') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Beranda
                </a>
            </div>

            <div class="row g-4 align-items-start">
                {{-- Sisi Kiri: Daftar Produk & Alamat --}}
                <div class="col-lg-7">
                    {{-- Produk Card --}}
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold mb-0">Produk yang Dipesan</h5>
                                <span class="badge rounded-pill px-3 py-2 
                                    @switch($order->status)
                                        @case('pending') bg-warning text-dark @break
                                        @case('processing') bg-primary text-white @break
                                        @case('shipped') bg-info text-white @break
                                        @case('delivered') bg-success text-white @break
                                        @case('cancelled') bg-danger text-white @break
                                        @default bg-secondary text-white
                                    @endswitch
                                ">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>

                            <div class="order-items">
                                @foreach($order->items as $item)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-3 bg-light border d-flex align-items-center justify-content-center" style="width: 65px; height: 65px; overflow: hidden;">
                                            @if($item->product && $item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" class="img-fluid" style="object-fit: cover; height: 100%;">
                                            @else
                                                <i class="bi bi-box text-muted fs-4"></i>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="ms-3 flex-grow-1">
                                        <div class="fw-bold text-dark mb-0">{{ $item->product_name }}</div>
                                        <div class="text-muted small">
                                            {{ $item->quantity }}x 
                                            @if($item->product && $item->price < $item->product->price)
                                                <span class="text-decoration-line-through">Rp{{ number_format($item->product->price, 0, ',', '.') }}</span>
                                            @endif
                                            Rp{{ number_format($item->price, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <div class="fw-bold text-dark">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Alamat Card --}}
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">Informasi Pengiriman</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <p class="small text-muted fw-bold mb-1 text-uppercase">Penerima</p>
                                    <p class="mb-0 fw-bold">{{ $order->shipping_name }}</p>
                                    <p class="text-muted small mb-0">{{ $order->shipping_phone }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="small text-muted fw-bold mb-1 text-uppercase">Alamat Lengkap</p>
                                    <p class="text-muted small mb-0">{{ $order->shipping_address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sisi Kanan: Ringkasan Pembayaran (Sticky) --}}
                <div class="col-lg-5 position-relative">
                    <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 2rem; z-index: 10;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">Ringkasan Pembayaran</h5>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted small">Subtotal Produk</span>
                                <span class="small fw-bold">Rp {{ number_format($order->total_amount - ($order->shipping_cost ?? 0), 0, ',', '.') }}</span>
                            </div>

                            <hr class="my-3 opacity-25">

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 fw-bold mb-0">Total Bayar</span>
                                <span class="h4 fw-bold text-danger mb-0">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                            </div>

                            {{-- Bagian Tombol Bayar (Logika Tidak Diubah) --}}
                            @if($order->status === 'pending' && $snapToken)
                            <div class="mt-4 pt-3 border-top">
                                <p class="text-muted small text-center mb-3">
                                    Selesaikan pembayaran Anda sebelum batas waktu berakhir.
                                </p>
                                <button id="pay-button" class="btn btn-danger btn-lg w-100 rounded-pill shadow-sm fw-bold py-3">
                                    <i class="bi bi-credit-card me-2"></i> Bayar Sekarang
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Snap.js Integration --}}
@if($snapToken)
    @push('scripts')
{{-- Load Snap JS dari Midtrans --}}
    <script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const payButton = document.getElementById('pay-button');

            if (payButton) {
                payButton.addEventListener('click', function () {
                // Disable button untuk mencegah double click
                    payButton.disabled = true;
                payButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memproses...';

                window.snap.pay('{{ $snapToken }}', {
                    onSuccess: function (result) {
                        console.log('Payment Success:', result);
                        window.location.href = '{{ route("orders.success", $order) }}';
                    },
                    onPending: function (result) {
                        console.log('Payment Pending:', result);
                        window.location.href = '{{ route("orders.pending", $order) }}';
                    },
                    onError: function (result) {
                        console.log('Payment Error:', result);
                        alert('Pembayaran gagal! Silakan coba lagi.');
                        payButton.disabled = false;
                        payButton.innerHTML = '<i class="bi bi-credit-card me-2"></i> Bayar Sekarang';
                    },
                        onClose: function () {
                        console.log('Payment popup closed');
                            payButton.disabled = false;
                        payButton.innerHTML = '<i class="bi bi-credit-card me-2"></i> Bayar Sekarang';
                        }
                    });
                });
            }
        });
    </script>
    @endpush
@endif

@endsection