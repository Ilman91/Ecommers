@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="row">
    <div class="col-lg-8">
        {{-- List Item --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Item Pesanan</h5>
            </div>
            <div class="card-body">
                @foreach($order->items as $item)
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold">{{ $item->product->name }}</h6>
                        <div class="small">
                            @if($item->product && $item->product->price > $item->price)
                                <span class="text-muted text-decoration-line-through me-1">
                                    Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </span>
                            @endif
                            <br>
                            <span class="text-dark">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
                <hr>
                <div class="d-flex justify-content-between fs-5 fw-bold">
                    <span>Total Pembayaran</span>
                    <span class="text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Info Customer --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Info Customer</h5>
            </div>
            <div class="card-body">
                <p class="mb-1 fw-bold">{{ $order->user->name }}</p>
                <p class="mb-1 text-muted">{{ $order->user->email }}</p>
            </div>
        </div>

                
            {{-- Action Card --}}
            <div class="card shadow-sm border-0 bg-light">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Update Status Order</h6>
                        
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label class="form-label small text-muted">
                                    Status Saat Ini: <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                </label>
                                
                                <select name="status" class="form-select">
                                    @if($order->status == 'pending')
                                        <option value="pending" selected>Pending (Menunggu Pembayaran)</option>
                                        <option value="cancelled">Cancelled (Batalkan Pesanan)</option>
                                    @else
                                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>
                                            Processing (Sedang Dikemas)
                                        </option>
                                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>
                                            Shipped (Dalam Pengiriman)
                                        </option>
                                        <option value="completed" {{ ($order->status == 'completed' || $order->status == 'delivered') ? 'selected' : '' }}>
                                            Completed (Selesai)
                                        </option>
                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled (Batalkan & Restock)
                                        </option>
                                    @endif
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100" 
                                {{ $order->status == 'cancelled' ? 'disabled' : '' }}>
                                Update Status
                            </button>
                        </form>

                        {{-- Warning jika masih pending --}}
                        @if($order->status == 'pending')
                            <div class="alert alert-warning mt-3 mb-0 small">
                                <i class="bi bi-exclamation-triangle-fill"></i> 
                                Pesanan belum dibayar. Anda hanya bisa membatalkan atau menunggu pembayaran masuk.
                            </div>
                        @endif

                        @if($order->status == 'cancelled')
                            <div class="alert alert-danger mt-3 mb-0 small">
                                <i class="bi bi-info-circle"></i> 
                                Pesanan ini telah dibatalkan. Stok produk telah dikembalikan otomatis.
                            </div>
                        @endif
                </div>
            </div>
    </div>
</div>
@endsection