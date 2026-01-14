<?php
// ================================================
// FILE: app/Http/Controllers/CatalogController.php
// FUNGSI: Menangani halaman katalog dan detail produk
// ================================================

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Menampilkan halaman katalog produk.
     * Mendukung filter: kategori, harga, pencarian, sorting.
     */
    public function index(Request $request)
    {
        // ================================================
        // 1. BASE QUERY
        // Mulai dengan produk aktif dan ada stok
        // ================================================
        $query = Product::query()
            ->with(['category', 'primaryImage'])  // Eager load relasi
            ->active()
            ->inStock();

        // ================================================
        // 2. FILTER: PENCARIAN
        // Cari di nama dan deskripsi produk
        // ================================================
        if ($request->filled('q')) {
            $query->search($request->q);  // Scope search di Model
        }

        // ================================================
        // 3. FILTER: KATEGORI
        // Gunakan slug kategori, bukan ID
        // ================================================
        if ($request->filled('category')) {
            $query->byCategory($request->category);  // Scope di Model
        }

        // ================================================
        // 4. FILTER: RENTANG HARGA
        // ================================================
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // ================================================
        // 5. FILTER: DISKON
        // Hanya produk yang sedang diskon
        // ================================================
        if ($request->boolean('on_sale')) {
            $query->onSale();  // Scope: discount_price < price
        }

        // ================================================
        // 6. SORTING
        // Default: terbaru (newest)
        // ================================================
        $sort = $request->get('sort', 'newest');

        match($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            default => $query->latest(),  // newest
        };

        // ================================================
        // 7. PAGINATION
        // withQueryString() menjaga parameter filter di URL pagination
        // ================================================
        $products = $query->paginate(12)->withQueryString();

        // Hitung Range harga global untuk keperluan UI (misal slider harga minimum-maksimum).
        // selectRaw lebih efisien daripada tarik semua data lalu di loop php.
        $priceRange = Product::available()
            ->selectRaw('MIN(price) as min, MAX(price) as max')
            ->first();

        // ================================================
        // 8. DATA SIDEBAR
        // Kategori untuk filter
        // ================================================
        $categories = Category::query()
            ->active()
            ->withCount(['activeProducts'])
            ->having('active_products_count', '>', 0)
            ->orderBy('name')
            ->get();

        return view('catalog.index', compact('products', 'categories', 'priceRange'));
    }

    /**
     * Menampilkan halaman detail produk.
     * Menggunakan Route Model Binding dengan slug.
     */
    public function show(string $slug)
    {
        // Cari produk berdasarkan SLUG, bukan ID (SEO Friendly).
        // PENTING: Gunakan scope available() agar user tidak bisa akses produk yang non-aktif via URL langsung.
        $product = Product::available()
            ->with(['category', 'images']) // Load semua gambar galeri
            ->where('slug', $slug)
            ->firstOrFail(); // 404 jika tidak ketemu

        // ================================================
        // PRODUK TERKAIT (RELATED)
        // Produk lain di kategori yang sama
        // ================================================
        $relatedProducts = Product::query()
            ->with(['category', 'primaryImage'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)  // Kecuali produk ini
            ->active()
            ->inStock()
            ->take(4)
            ->get();

        return view('catalog.show', compact('product', 'relatedProducts'));
    }
}   