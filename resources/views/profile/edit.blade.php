@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-danger rounded-3 p-2 me-3 shadow-sm" style="background-color: rgb(215, 78, 78) !important;">
                    <i class="bi bi-person-gear text-white fs-4"></i>
                </div>
                <div>
                    <h2 class="fw-bold mb-0">Pengaturan Profil</h2>
                    <p class="text-muted mb-0 small">Kelola informasi akun dan keamanan KitaElektronik kamu.</p>
                </div>
            </div>

            <div class="row g-4">
            {{-- Kiri: Foto Profil & Akun Terhubung --}}
            <div class="col-lg-4">
                {{-- Langsung include tanpa dibungkus Card lagi --}}
                @include('profile.partials.update-avatar-form')
                
                <div class="mt-4">
                    @include('profile.partials.connected-accounts')
                </div>
            </div>

            {{-- Kanan: Form Update, Password & Danger Zone --}}
            <div class="col-lg-8">
                {{-- Profile Info --}}
                @include('profile.partials.update-profile-information-form')

                {{-- Update Password --}}
                <div class="mt-4">
                    @include('profile.partials.update-password-form')
                </div>

                {{-- Danger Zone --}}
                <div class="mt-4">
                    {{-- Hapus Akun jangan dibungkus d-flex justify-between di sini, 
                        biar file partial-nya yang ngatur layout sendiri --}}
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection