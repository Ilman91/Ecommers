<p class="text-muted small">Pastikan akun kamu aman dengan menggunakan password yang panjang dan acak.</p>

<form method="post" action="{{ route('profile.password.update') }}">
    @csrf
    @method('put')

    {{-- Password Saat Ini --}}
    <div class="mb-3">
        <label for="current_password" class="form-label small fw-bold text-secondary">Password Saat Ini</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-lock text-muted"></i></span>
            <input type="password"
                   name="current_password"
                   id="current_password"
                   class="form-control border-start-0 @error('current_password', 'updatePassword') is-invalid @enderror"
                   autocomplete="current-password"
                   placeholder="••••••••">
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row">
        {{-- Password Baru --}}
        <div class="col-md-6 mb-3">
            <label for="password" class="form-label small fw-bold text-secondary">Password Baru</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-key text-muted"></i></span>
                <input type="password"
                       name="password"
                       id="password"
                       class="form-control border-start-0 @error('password', 'updatePassword') is-invalid @enderror"
                       autocomplete="new-password"
                       placeholder="Min. 8 karakter">
                @error('password', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Konfirmasi Password --}}
        <div class="col-md-6 mb-3">
            <label for="password_confirmation" class="form-label small fw-bold text-secondary">Konfirmasi Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-check2-all text-muted"></i></span>
                <input type="password"
                       name="password_confirmation"
                       id="password_confirmation"
                       class="form-control border-start-0 @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                       autocomplete="new-password"
                       placeholder="Ulangi password">
                @error('password_confirmation', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center gap-3 mt-2">
        <button type="submit" class="btn btn-dark px-4 fw-bold shadow-sm" style="border-radius: 8px;">
            <i class="bi bi-arrow-repeat me-2"></i>Update Password
        </button>

        @if (session('status') === 'password-updated')
            <div class="text-success small fw-bold animate-fade">
                <i class="bi bi-check-circle-fill"></i> Berhasil diperbarui!
            </div>
            <script>
                setTimeout(() => {
                    const msg = document.querySelector('.animate-fade');
                    if(msg) msg.style.opacity = '0';
                }, 3000);
            </script>
        @endif
    </div>
</form>