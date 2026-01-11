<?php
// app/Services/OrderService.php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Membuat Order baru dari Keranjang belanja.
     *
     * ALUR PROSES (TRANSACTION):
     * 1. Hitung total & Validasi Stok terakhir
     * 2. Buat Record Order (Header)
     * 3. Pindahkan Cart Items ke Order Items (Detail)
     * 4. Kurangi Stok Produk (Atomic Decrement)
     * 5. Hapus Keranjang
     */
    public function createOrder(User $user, array $shippingData): Order
    {
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            throw new \Exception("Keranjang belanja kosong.");
        }

        return DB::transaction(function () use ($user, $cart, $shippingData) {

            // A. VALIDASI STOK & HITUNG TOTAL (DENGAN DISKON)
            $totalAmount = 0;
            foreach ($cart->items as $item) {
                if ($item->quantity > $item->product->stock) {
                    throw new \Exception("Stok produk {$item->product->name} tidak mencukupi.");
                }

                // LOGIKA: Ambil harga diskon jika ada dan valid
                $effectivePrice = ($item->product->discount_price > 0) 
                                  ? $item->product->discount_price 
                                  : $item->product->price;

                $totalAmount += $effectivePrice * $item->quantity;
            }

            // B. BUAT HEADER ORDER
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'shipping_name' => $shippingData['name'],
                'shipping_address' => $shippingData['address'],
                'shipping_phone' => $shippingData['phone'],
                'total_amount' => $totalAmount, // Sekarang total sudah benar
            ]);

            // C. PINDAHKAN ITEMS
            foreach ($cart->items as $item) {
                // Tentukan harga beli (Snapshot)
                $finalPrice = ($item->product->discount_price > 0) 
                              ? $item->product->discount_price 
                              : $item->product->price;

                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $finalPrice, // Menyimpan harga diskon ke riwayat order
                    'quantity' => $item->quantity,
                    'subtotal' => $finalPrice * $item->quantity,
                ]);

                // D. KURANGI STOK
                $item->product->decrement('stock', $item->quantity);
            }

            // E. BERSIHKAN KERANJANG
            $cart->items()->delete();

            return $order;
        });
    }
}