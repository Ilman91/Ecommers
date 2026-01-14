{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary-red: rgb(215, 78, 78);
        --soft-pink: rgb(251, 186, 186);
    }
    .card-register {
        border: none;
        border-radius: 15px;
    }
    .header-register {
        color: var(--primary-red);
        font-weight: 800;
    }
    .btn-register {
        background-color: var(--primary-red);
        border: none;
        color: white;
        transition: 0.3s;
    }
    .btn-register:hover {
        background-color: #a33b3b;
        color: white;
        transform: translateY(-2px);
    }
    .text-primary-custom {
        color: var(--primary-red) !important;
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
        <div class="col-md-6">

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

            <div class="card card-register shadow-lg border-0">
                <div class="card-body p-5">
                    <h2 class="header-register text-center mb-4">BUAT AKUN</h2>
                    
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Masukkan nama lengkap">
                            @error('name')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="email@contoh.com">
                            @error('email')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="••••••••">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required placeholder="••••••••">
                            </div>
                            @error('password')
                                <div class="col-12 mt-1">
                                    <small class="text-danger"><strong>{{ $message }}</strong></small>
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-register btn-lg">DAFTAR SEKARANG</button>
                        </div>

                        <div class="mt-4 text-center">
                            <p class="small text-muted">Atau daftar lebih cepat dengan:</p>
                            <a href="{{ route('auth.google') }}" class="btn btn-outline-dark w-100 mb-3 fw-bold">
                                <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="20" class="me-2">
                                 Google
                            </a>
                            <p class="mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="text-primary-custom fw-bold text-decoration-none">Login di sini</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection