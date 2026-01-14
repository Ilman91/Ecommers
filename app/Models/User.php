<?php
// app/Models/User.php

namespace App\Models;

use App\Models\Cart;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang boleh diisi secara mass-assignment.
     * Ini mencegah vulnerability mass-assignment.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'google_id',
        'phone',
        'address',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi ke JSON/array.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data otomatis.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ==================== RELATIONSHIPS ====================

    /**
     * User memiliki satu keranjang aktif.
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * User memiliki banyak item wishlist.
     */

    /**
     * User memiliki banyak pesanan.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relasi many-to-many ke products melalui wishlists.
     */
    public function wishlists()
    {
        return $this->belongsToMany(Product::class, 'wishlists')
            ->withTimestamps();
    }

    // ==================== HELPER METHODS ====================

    /**
     * Cek apakah user adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah customer.
     */
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    /**
     * Cek apakah produk ada di wishlist user.
     */
    public function hasInWishlist(Product $product): bool
    {
        return $this->wishlists()
            ->where('product_id', $product->id)
            ->exists();
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return asset('storage/' . $this->avatar);
        }

        if (str_starts_with($this->avatar ?? '', 'http')) {
            return $this->avatar;
        }

        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&background=d74e4e&color=fff&length=1';
    }

/**
 * Get initials from name for avatar fallback.
 * Contoh: "Agung Wahyudi" -> "AW"
 * Berguna jika kita ingin membuat UI avatar berupa inisial huruf teks.
 */
    public function getInitialsAttribute(): string
    {
        // Ambil kata pertama saja, lalu ambil huruf pertamanya
        $firstName = explode(' ', trim($this->name))[0];
        return strtoupper(substr($firstName, 0, 1));
    }

}