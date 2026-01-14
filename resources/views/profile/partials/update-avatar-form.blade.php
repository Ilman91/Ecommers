<div class="text-center">
    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- Avatar Preview --}}
        <div class="position-relative d-inline-block mb-3">
            <img id="avatar-preview"
                 class="rounded-circle object-fit-cover border shadow-sm"
                 style="width: 120px; height: 120px; border: 3px solid #fff !important;"
                 src="{{ $user->avatar_url }}"
                 alt="{{ $user->name }}">

            @if($user->avatar)
                <button type="button"
                        onclick="if(confirm('Hapus foto profil?')) document.getElementById('delete-avatar-form').submit()"
                        class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 shadow-sm"
                        style="width: 28px; height: 28px; padding: 0;"
                        title="Hapus foto">
                    <i class="bi bi-x"></i>
                </button>
            @endif
        </div>

        <div class="mb-3">
            <label for="avatar" class="form-label small fw-bold d-block text-muted">Upload Foto Baru</label>
            <input type="file"
                   name="avatar"
                   id="avatar"
                   accept="image/*"
                   onchange="previewAvatar(event)"
                   class="form-control form-control-sm @error('avatar') is-invalid @enderror">
            @error('avatar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text mt-2" style="font-size: 0.7rem;">
                JPG, PNG, WebP (Maks. 2MB)
            </div>
        </div>

        <button type="submit" class="btn btn-danger w-100 fw-bold shadow-sm" style="background-color: rgb(215, 78, 78); border: none;">
            Simpan Foto
        </button>
    </form>
</div>

{{-- Hidden Form Delete Avatar --}}
<form id="delete-avatar-form" action="{{ route('profile.avatar.destroy') }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form>

<script>
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }
</script>