@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="col-md-5">
        <div class="card border-0 shadow-lg" style="border-radius: 20px;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="bg-light d-inline-block p-3 rounded-circle mb-3">
                        <i class="bi bi-key text-danger" style="font-size: 2.5rem;"></i>
                    </div>
                    <h3 class="fw-bold">Buat Password Baru</h3>
                    <p class="text-muted small">Sedikit lagi! Silakan masukkan password baru kamu.</p>
                </div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Email</label>
                        <input type="email" class="form-control bg-light" name="email" value="{{ $email ?? old('email') }}" required readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password Baru</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autofocus placeholder="Minimal 8 karakter">
                        @error('password')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Konfirmasi Password Baru</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Ulangi password baru">
                    </div>

                    <button type="submit" class="btn btn-danger w-100 py-2 fw-bold shadow-sm" style="border-radius: 10px; background-color: rgb(215, 78, 78); border: none;">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection