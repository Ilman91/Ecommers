<?php
// app/Http/Requests/ProfileUpdateRequest.php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Dalam konteks profil, semua user yang login boleh update profilnya sendiri.
        // Route middleware 'auth' sudah menjamin user login.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // Nama: wajib, string, max 255 karakter
            'name' => [
                'sometimes', // Validasi hanya jika field ini ada di request
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'sometimes', // Validasi hanya jika field ini ada di request
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],

            // Phone: opsional, regex khusus format Indonesia
            // Menerima: 0812..., 62812..., +62812...
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(\+62|62|0)8[1-9][0-9]{6,10}$/',
            ],

            // Address: opsional, text max 500 karakter
            'address' => [
                'nullable',
                'string',
                'max:500',
            ],

            // Avatar: opsional
            // Harus file gambar (mime: jpg, png, webp)
            // Max ukuran 2MB (2048 KB)
            // Dimensi minimal 100x100px agar tidak pecah/blur
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpeg,jpg,png,webp',
                'max:2048', // 2MB udah cukup banget buat avatar
            ],
        ];
    }

    /**
     * Custom error messages (Bahasa Indonesia).
     * Laravel menyediakan default message (b.inggris), kita override agar lebih user friendly.
     */
    public function messages(): array
    {
        return [
            'phone.regex' => 'Format nomor telepon tidak valid. Gunakan format 08xx atau +628xx.',
            'avatar.max' => 'Ukuran foto maksimal 2MB.',
            'avatar.dimensions' => 'Dimensi foto harus antara 100x100 hingga 2000x2000 pixel.',
            'email.unique' => 'Email ini sudah digunakan oleh pengguna lain.',
        ];
    }

    /**
     * Custom attribute names for error messages.
     * Mengubah ":attribute is required" menjadi "nama wajib diisi".
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'email' => 'alamat email',
            'phone' => 'nomor telepon',
            'address' => 'alamat domisili',
            'avatar' => 'foto profil',
        ];
    }
}