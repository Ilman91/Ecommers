{{-- resources/views/profile/partials/delete-user-form.blade.php --}}

<div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 12px; border-left: 5px solid #dc3545 !important;">
    <div class="card-body p-4">
        <h5 class="fw-bold text-danger mb-3">
            <i class="bi bi-exclamation-octagon-fill me-2"></i>Zona Berbahaya
        </h5>
        
        <div class="p-3 mb-4 shadow-sm" style="background-color: #fff5f5; border-radius: 10px; border: 1px solid #fed7d7;">
            <p class="text-dark small mb-2 fw-bold text-uppercase" style="letter-spacing: 0.5px;">Peringatan Penting</p>
            <p class="text-muted small mb-0" style="line-height: 1.6;">
                Setelah akun kamu dihapus, semua data akan hilang permanen dari server <strong>KitaElektronik</strong>. 
                Data pesanan, alamat, dan informasi profil tidak dapat dipulihkan.
            </p>
        </div>

        <div class="d-grid d-md-block">
            <button type="button" class="btn btn-danger px-4 fw-bold shadow-sm" 
                    style="border-radius: 8px; background-color: #d74e4e; border: none; transition: all 0.2s;"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.backgroundColor='#bd4444'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.backgroundColor='#d74e4e'"
                    data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
                <i class="bi bi-trash3 me-2"></i>Hapus Akun Saya
            </button>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI --}}
<div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="{{ route('profile.destroy') }}" class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            @csrf
            @method('delete')

            <div class="modal-header border-0 pt-4 px-4 text-center d-block">
                <h5 class="modal-title fw-bold text-dark w-100">Konfirmasi Hapus Akun</h5>
                <p class="text-muted small mb-0">Aksi ini tidak dapat dibatalkan</p>
            </div>

            <div class="modal-body px-4 pt-2">
                <div class="mb-3">
                    <label for="password_delete" class="form-label small fw-bold text-secondary">Password Kamu</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-lock text-muted"></i></span>
                        <input type="password"
                               name="password"
                               id="password_delete"
                               class="form-control border-start-0 @error('password', 'userDeletion') is-invalid @enderror"
                               placeholder="••••••••"
                               required>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 pb-4 px-4 pt-0">
                <button type="button" class="btn btn-light fw-bold px-4 text-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Batal</button>
                <button type="submit" class="btn btn-danger fw-bold px-4" style="border-radius: 8px; background-color: #d74e4e; border: none;">
                    Hapus Permanen
                </button>
            </div>
        </form>
    </div>
</div>

@if($errors->userDeletion->isNotEmpty())
    <script type="module">
        const myModal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
        myModal.show();
    </script>
@endif