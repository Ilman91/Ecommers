@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="col-md-5 text-center">
        <div class="card border-0 shadow-lg" style="border-radius: 20px;">
            <div class="card-body p-5">
                <div class="mb-4">
                    <i class="bi bi-shield-lock text-danger" style="font-size: 3rem;"></i>
                </div>
                <h4 class="fw-bold">Konfirmasi Password</h4>
                <p class="text-muted small mb-4">Demi keamanan, silakan masukkan password Anda kembali sebelum melanjutkan.</p>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="mb-4 text-start">
                        <label for="password" class="form-label small fw-bold">Password Anda</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Masukkan password kamu">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-danger w-100 py-2 fw-bold mb-3 shadow-sm" style="border-radius: 10px; background-color: rgb(215, 78, 78); border: none;">
                        Konfirmasi Sekarang
                    </button>

                    @if (Route::has('password.request'))
                        <a class="btn btn-link btn-sm text-decoration-none text-muted" href="{{ route('password.request') }}">
                            Lupa Password?
                        </a>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection