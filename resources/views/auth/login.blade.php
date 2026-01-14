{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-red: rgb(215, 78, 78);
        --soft-pink: rgb(251, 186, 186);
        --dark-text: #333;
    }
    .card-login {
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }
    .card-header-custom {
        background: linear-gradient(45deg, var(--primary-red), var(--soft-pink));
        color: white;
        padding: 2rem;
        border: none;
    }
    .btn-primary-custom {
        background-color: var(--primary-red);
        border: none;
        color: white;
        transition: 0.3s;
    }
    .btn-primary-custom:hover {
        background-color: #a33b3b;
        color: white;
        transform: translateY(-2px);
    }
    .text-primary-custom {
        color: var(--primary-red) !important;
    }
    .form-control:focus {
        border-color: var(--soft-pink);
        box-shadow: 0 0 0 0.25rem rgba(215, 78, 78, 0.25);
    }
    .logo-brand {
        color: #000;
        font-weight: 700;
        transition: color 0.3s ease;
    }
    .logo-brand:hover {
        color: rgb(215, 78, 78); /* Berubah jadi merah pas di-hover */
    }
    .logo-brand i {
        color: rgb(215, 78, 78); /* Iconnya default merah */
    }
</style>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">

    <div class="text-center mb-4">
    <a href="{{ route('home') }}" class="text-decoration-none">
        {{-- Icon box-heart sesuai navbar --}}
        <i class="bi bi-box2-heart fs-1" style="color: rgb(215, 78, 78);"></i>
        {{-- Nama Brand --}}
        <h2 class="fw-bold mt-2" style="color: #000; letter-spacing: -1px;">
            Kita<span style="color: rgb(215, 78, 78);">Elektronik</span>
        </h2>
    </a>
    <p class="text-muted small">Kembali ke Beranda</p>
    </div>

      <div class="card card-login shadow-lg">
        <div class="card-header-custom text-center">
          <h3 class="fw-bold mb-0">Selamat Datang</h3>
          <small>Silahkan login ke akun Anda</small>
        </div>

        <div class="card-body p-4">
          <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-bold">Email</label>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                     value="{{ old('email') }}" required placeholder="nama@email.com">
              @error('email')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label fw-bold">Password</label>
              <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                     required placeholder="••••••••">
              @error('password')
                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
              @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small" for="remember">Ingat Saya</label>
                </div>
                @if (Route::has('password.request'))
                    <a class="small text-decoration-none text-primary-custom fw-bold" href="{{ route('password.request') }}">Lupa Password?</a>
                @endif
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary-custom btn-lg fw-bold">MASUK</button>
            </div>

            <div class="position-relative my-4">
                <hr>
                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">ATAU</span>
            </div>

            <div class="d-grid mb-3">
                <a href="{{ route('auth.google') }}" class="btn btn-outline-dark fw-bold">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="20" class="me-2">
                    Login dengan Google
                </a>
            </div>

            <p class="text-center mb-0 small">
                Belum punya akun? <a href="{{ route('register') }}" class="text-primary-custom fw-bold text-decoration-none">Daftar Sekarang</a>
            </p>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection