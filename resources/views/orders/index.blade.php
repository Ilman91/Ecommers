@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="h3 mb-4 fw-bold">Daftar Pesanan Saya</h1>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">No. Order</th>
                            <th>Pesanan</th> {{-- Kolom baru --}}
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th class="text-end pe-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td class="fw-bold text-primary">#{{ $order->order_number }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    @if($order->items->isNotEmpty())
                                        {{-- Menampilkan nama produk pertama --}}
                                        <span class="text-dark fw-medium">
                                            {{ Str::limit($order->items->first()->product_name, 35) }}
                                        </span>
                                        
                                        {{-- Jika ada lebih dari 1 produk, tampilkan badge jumlah sisa --}}
                                        @if($order->items->count() > 1)
                                            <small class="text-muted">
                                                +{{ $order->items->count() - 1 }} produk lainnya
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted small">Tidak ada detail produk</span>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <span class="badge
                                    @if($order->status == 'pending') bg-warning text-dark
                                    @elseif($order->status == 'processing') bg-primary text-dark
                                    @elseif($order->status == 'shipped') bg-info
                                    @elseif($order->status == 'delivered') bg-success
                                    @elseif($order->status == 'cancelled') bg-danger
                                    @endif
                                ">
                                    @if($order->status == 'pending') Pending
                                    @elseif($order->status == 'processing') Diproses
                                    @elseif($order->status == 'shipped') Dikirim
                                    @elseif($order->status == 'delivered') Sampai
                                    @elseif($order->status == 'cancelled') Batal
                                    @else {{ ucfirst($order->status) }}
                                    @endif
                                </span>
                            </td>
                            <td class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="text-end">
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">Belum ada pesanan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection