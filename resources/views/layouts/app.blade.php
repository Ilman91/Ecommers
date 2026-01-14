{{-- ================================================
FILE: resources/views/layouts/app.blade.php
FUNGSI: Master layout untuk halaman customer/publik
================================================ --}}

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- CSRF Token untuk AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO Meta Tags --}}
    <title>@yield('title', 'Toko Online') - {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta_description', 'Toko online terpercaya dengan produk berkualitas')">

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Vite CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Stack untuk CSS tambahan per halaman --}}
    @stack('styles')
</head>

<body>
    {{-- Tampilkan Navbar HANYA jika bukan halaman login, register, atau lupa password --}}
    @if(!Route::is('login') && !Route::is('register') && !Str::contains(Route::currentRouteName(), 'password'))
        @include('partials.navbar')
    @endif

    {{-- Flash messages tetap muncul buat notif kalau ada error --}}
    <div class="container mt-3">
        @include('partials.flash-messages')
    </div>

    <main class="min-vh-100">
        @yield('content')
    </main>

    {{-- Tampilkan Footer HANYA jika bukan halaman login, register, atau lupa password --}}
    @if(!Route::is('login') && !Route::is('register') && !Str::contains(Route::currentRouteName(), 'password'))
        @include('partials.footer')
    @endif

    {{-- Stack untuk JS tambahan per halaman --}}
    <script>
        /**
       * Fungsi AJAX untuk Toggle Wishlist
       * Menggunakan Fetch API (Modern JS) daripada jQuery.
       */
      async function toggleWishlist(productId) {
        try {
          // 1. Ambil CSRF token dari meta tag HTML
          // Laravale mewajibkan token ini untuk setiap request POST demi keamanan.
          const token = document.querySelector('meta[name="csrf-token"]').content;

          // 2. Kirim Request ke Server
          const response = await fetch(`/wishlist/toggle/${productId}`, {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "X-CSRF-TOKEN": token, // Tempel token di header
            },
          });

          // 3. Handle jika user belum login (Error 401 Unauthorized)
          if (response.status === 401) {
            window.location.href = "/login"; // Lempar ke halaman login
            return;
          }

          // 4. Baca respon JSON dari server
          const data = await response.json();

          if (data.status === "success") {
            // 5. Update UI tanpa reload halaman
            updateWishlistUI(productId, data.added); // Ganti warna ikon
            updateWishlistCounter(data.count); // Update angka di header
            showToast(data.message); // Tampilkan notifikasi
          }
        } catch (error) {
          console.error("Error:", error);
          showToast("Terjadi kesalahan sistem.", "error");
        }

        const data = await response.json();

        if (data.status === "success") {
            updateWishlistUI(productId, data.added); 
            // Fungsi di bawah ini sudah cukup untuk mengupdate semua angka di layar
            updateWishlistCounter(data.count); 
            showToast(data.message);
        }
      }

      function updateWishlistUI(productId, isAdded) {
          const buttons = document.querySelectorAll(`.wishlist-btn-${productId}`);
          
          // Update angka total di header wishlist (jika ada)
          const totalLabel = document.getElementById('wishlist-total-count');

          buttons.forEach((btn) => {
              const icon = btn.querySelector("i");
              const textSpan = btn.querySelector(`.wishlist-text-${productId}`);

              if (isAdded) {
                  icon.classList.remove("bi-heart", "text-secondary");
                  icon.classList.add("bi-heart-fill", "text-danger");
                  if (textSpan) textSpan.innerText = "Hapus dari Wishlist";
              } else {
                  // JIKA STATUSNYA REMOVED (isAdded == false)
                  icon.classList.remove("bi-heart-fill", "text-danger");
                  icon.classList.add("bi-heart", "text-secondary");
                  if (textSpan) textSpan.innerText = "Simpan ke Wishlist";

                  // LOGIC KHUSUS HALAMAN WISHLIST: Hapus Card dari Grid
                  const cardItem = document.getElementById(`wishlist-item-${productId}`);
                  if (cardItem) {
                      cardItem.style.transition = "0.3s";
                      cardItem.style.opacity = "0";
                      cardItem.style.transform = "scale(0.8)";
                      
                      setTimeout(() => {
                          cardItem.remove();
                          
                          // Update label total produk di halaman wishlist
                          if (totalLabel) {
                              let currentTotal = parseInt(totalLabel.innerText);
                              totalLabel.innerText = Math.max(0, currentTotal - 1);
                              
                              // Jika habis, tampilkan empty state
                              const grid = document.getElementById('wishlist-grid');
                              if (grid && grid.children.length === 0) {
                                  const container = document.getElementById('wishlist-container');
                                  const template = document.getElementById('empty-wishlist-template');
                                  container.innerHTML = template.innerHTML;
                              }
                          }
                      }, 300);
                  }
              }
          });
      }

      function updateWishlistCounter(count) {
          // 1. Update Badge di Navbar
          const navBadge = document.getElementById("wishlist-count");
          if (navBadge) {
              navBadge.innerText = count;
              // Paksa sembunyi jika nol, munculkan jika lebih dari nol
              if (parseInt(count) <= 0) {
                  navBadge.style.setProperty('display', 'none', 'important');
              } else {
                  navBadge.style.display = "inline-block";
              }
          }

          // 2. Update Angka di Halaman Wishlist
          const pageTotal = document.getElementById("wishlist-total-count");
          if (pageTotal) {
              pageTotal.innerText = count;
          }
      }

      function showToast(message, type = "success") {
          // Kalau kamu pakai library seperti SweetAlert2 atau Toastr, panggil di sini.
          // Contoh versi native alert sederhana:
          console.log(`${type.toUpperCase()}: ${message}`);
          
          // Atau kalau mau pakai Toast Bootstrap (asumsi ada elemen toast di HTML):
          // const toastElement = document.getElementById('liveToast');
          // ... logic bootstrap toast ...
      }
    </script>
    @stack('scripts')
  </body>

  </html>