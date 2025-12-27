{{-- resources/views/orders/show.blade.php --}}
@extends('layouts.app') {{-- Pastikan layouts.app sudah include Bootstrap 5 CSS & JS --}}

@section('title', 'Detail Pesanan')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
            <div class="card shadow">
                {{-- Header Order --}}
                <div class="card-header bg-white border-bottom py-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h1 class="h3 mb-1 fw-bold text-dark">
                                Order #{{ $order->order_number }}
                            </h1>
                            <p class="text-muted mb-0">
                                {{ $order->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>

                        {{-- Status Badge --}}
                        <span class="badge rounded-pill fs-6 px-4 py-2
                            @switch($order->status)
                                @case('pending')
                                    bg-warning text-dark
                                    @break
                                @case('processing')
                                    bg-info text-white
                                    @break
                                @case('shipped')
                                    bg-secondary text-white
                                    @break
                                @case('delivered')
                                    bg-success text-white
                                    @break
                                @case('cancelled')
                                    bg-danger text-white
                                    @break
                                @default
                                    bg-secondary text-white
                            @endswitch
                        ">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                {{-- Detail Items --}}
                <div class="card-body py-5">
                    <h3 class="h5 fw-semibold mb-4">Produk yang Dipesan</h3>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="text-start">Produk</th>
                                    <th scope="col" class="text-center">Qty</th>
                                    <th scope="col" class="text-end">Harga</th>
                                    <th scope="col" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="py-3">{{ $item->product_name }}</td>
                                    <td class="py-3 text-center">{{ $item->quantity }}</td>
                                    <td class="py-3 text-end">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 text-end">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                @if($order->shipping_cost > 0)
                                <tr class="border-top">
                                    <td colspan="3" class="pt-4 text-end fw-medium">Ongkos Kirim:</td>
                                    <td class="pt-4 text-end fw-medium">
                                        Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endif
                                <tr class="border-top border-3">
                                    <td colspan="3" class="pt-4 text-end fw-bold fs-4">
                                        TOTAL BAYAR:
                                    </td>
                                    <td class="pt-4 text-end fw-bold fs-4 text-primary">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                {{-- Alamat Pengiriman --}}
                <div class="card-footer bg-light py-4">
                    <h3 class="h5 fw-semibold mb-3">Alamat Pengiriman</h3>
                    <address class="mb-0 text-dark">
                        <strong>{{ $order->shipping_name }}</strong><br>
                        {{ $order->shipping_phone }}<br>
                        {{ $order->shipping_address }}
                    </address>
                </div>

                {{-- Tombol Bayar (hanya jika status pending) --}}
                @if($order->status === 'pending' && $snapToken)
                <div class="card-footer bg-primary bg-opacity-10 py-5 text-center border-top">
                    <p class="text-muted mb-4 fs-6">
                        Selesaikan pembayaran Anda sebelum batas waktu berakhir.
                    </p>
                    <button id="pay-button" class="btn btn-primary btn-lg px-5 shadow">
                        💳 Bayar Sekarang
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Midtrans Snap Integration --}}
@if($snapToken)
@push('scripts')
    <script src="{{ config('midtrans.snap_url') }}"
            data-client-key="{{ config('midtrans.client_key') }}"></script>

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const payButton = document.getElementById('pay-button');

            if (payButton) {
                payButton.addEventListener('click', function () {
                    // Disable button & tambah spinner
                    payButton.disabled = true;
                    payButton.innerHTML = `
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Memproses...
                    `;

                    window.snap.pay('{{ $snapToken }}', {
                        onSuccess: function (result) {
                            console.log('Success:', result);
                            window.location.href = '{{ route("orders.success", $order) }}';
                        },
                        onPending: function (result) {
                            console.log('Pending:', result);
                            window.location.href = '{{ route("orders.pending", $order) }}';
                        },
                        onError: function (result) {
                            console.log('Error:', result);
                            alert('Pembayaran gagal! Silakan coba lagi.');
                            payButton.disabled = false;
                            payButton.innerHTML = '💳 Bayar Sekarang';
                        },
                        onClose: function () {
                            console.log('Popup closed');
                            payButton.disabled = false;
                            payButton.innerHTML = '💳 Bayar Sekarang';
                        }
                    });
                });
            }
        });
    </script>
@endpush
@endif
@endsection