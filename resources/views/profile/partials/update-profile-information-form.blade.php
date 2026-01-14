{{-- resources/views/profile/partials/update-profile-information-form.blade.php --}}

<p class="text-muted small">Perbarui informasi profil dan alamat email kamu.</p>

{{-- Form Tersembunyi untuk Verifikasi Email --}}
{{-- <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">
    @csrf
</form> --}}

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="row">
        {{-- Nama --}}
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label small fw-bold text-secondary">Nama Lengkap</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                <input type="text"
                       name="name"
                       id="name"
                       class="form-control border-start-0 @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name) }}"
                       required autofocus autocomplete="name">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Email --}}
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label small fw-bold text-secondary">Email</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                <input type="email"
                       name="email"
                       id="email"
                       class="form-control border-start-0 @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email) }}"
                       required autocomplete="username">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 bg-light-warning p-2 rounded border border-warning">
                    <p class="text-dark small mb-0">
                        <i class="bi bi-exclamation-triangle-fill text-warning"></i> Email belum diverifikasi.
                        <button form="send-verification" class="btn btn-link p-0 fw-bold text-danger text-decoration-none small">
                            Kirim Ulang Link?
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="text-success small fw-bold mb-0 mt-1">
                            <i class="bi bi-check-circle-fill"></i> Link verifikasi telah dikirim!
                        </p>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Phone --}}
    <div class="mb-3">
        <label for="phone" class="form-label small fw-bold text-secondary">Nomor Telepon (WhatsApp)</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="bi bi-whatsapp text-success"></i></span>
            <input type="tel"
                   name="phone"
                   id="phone"
                   class="form-control border-start-0 @error('phone') is-invalid @enderror"
                   value="{{ old('phone', $user->phone) }}"
                   placeholder="08xxxxxxxxxx">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-text" style="font-size: 0.7rem;">Format: 08xxxxxxxxxx atau +628xxxxxxxxxx</div>
    </div>

    {{-- Address --}}
    <div class="mb-4">
        <label for="address" class="form-label small fw-bold text-secondary">Alamat Lengkap Pengiriman</label>
        <textarea name="address"
                  id="address"
                  rows="3"
                  class="form-control @error('address') is-invalid @enderror"
                  placeholder="Contoh: Jl. Elektronik No. 1, Kec. Gatot, Kota Bandung, 40222">{{ old('address', $user->address) }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-danger px-4 fw-bold shadow-sm" style="background-color: #d74e4e; border: none; border-radius: 8px;">
        <i class="bi bi-save me-2"></i>Simpan Informasi
    </button>
</form>