@extends('layouts.app')

@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="row justify-content-center w-100">
        <div class="col-md-5">
            {{-- Card Utama --}}
            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-body p-5">
                    
                    {{-- Header & Icon --}}
                    <div class="text-center mb-4">
                        <div class="bg-light d-inline-block p-3 rounded-circle mb-3">
                            <i class="bi bi-envelope-paper text-danger" style="font-size: 2.5rem;"></i>
                        </div>
                        <h3 class="fw-bold">Lupa Password?</h3>
                        <p class="text-muted small">Masukkan email yang terdaftar, kami akan kirimkan link reset password ke Mailtrap kamu.</p>
                    </div>

                    {{-- Alert Status dari Laravel --}}
                    @if (session('status'))
                        <div class="alert alert-success border-0 shadow-sm mb-4 small" role="alert" style="border-radius: 12px;">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
                        </div>
                    @endif

                    {{-- Form --}}
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label small fw-bold">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-envelope text-muted"></i>
                                </span>
                                <input id="email" type="email" 
                                       class="form-control border-start-0 py-2 @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" 
                                       placeholder="nama@email.com"
                                       required autocomplete="email" autofocus>
                                
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-0">
                            <button type="submit" class="btn btn-danger w-100 py-2 fw-bold shadow-sm" 
                                    style="border-radius: 10px; background-color: rgb(215, 78, 78); border: none;">
                                <i class="bi bi-send-fill me-2"></i> Kirim Link Reset
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-decoration-none small text-muted">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Login
                        </a>
                    </div>

                </div>
            </div>
            
            {{-- Footer Tambahan --}}
            <p class="text-center text-muted mt-4 small">
                &copy; {{ date('Y') }} KitaElektronik - Keamanan Akun Prioritas Kami.
            </p>
        </div>
    </div>
</div>
@endsection