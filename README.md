# Smart POS (Template)

Template **Point of Sale (POS)** open-source untuk **retail + restoran** yang dibangun dengan Laravel 13, Filament v5, Livewire, dan Tailwind.

## Fitur

- **Admin panel (Filament)**: produk, pelanggan, transaksi, users (role admin/kasir)
- **Kasir (Breeze + Livewire)**: POS terminal, keranjang, pembayaran, cetak struk
- **API (Sanctum)**: endpoint dasar untuk integrasi eksternal
- **Real-time**: Reverb/WebSocket untuk update stok/transaksi (opsional)
- **Monitoring**: Laravel Pulse (opsional)

## 🛠️ Tech Stack

- **Backend**: [Laravel 13](https://laravel.com) (PHP 8.3+)
- **Admin Panel**: [Filament v5](https://filamentphp.com)
- **Frontend Kasir**: [Livewire](https://livewire.laravel.com) + Blade + Tailwind CSS
- **Database**: SQLite (default) / MySQL / PostgreSQL
- **Auth**: Laravel Breeze + Sanctum (API)
- **Real-time**: Laravel Reverb (WebSocket)
- **Monitoring**: Laravel Pulse

## 📁 Struktur Proyek

```
smart-pos/
├── app/
│   ├── Filament/           # Admin panel (Filament resources)
│   │   ├── Pages/          # Kasir page, Pulse dashboard
│   │   ├── Resources/      # Customers, Products, Transactions
│   │   └── Widgets/        # Dashboard widgets
│   ├── Http/
│   │   ├── Controllers/    # Kasir, API controllers
│   │   └── Middleware/     # Role checking
│   ├── Livewire/           # PosTerminal component
│   ├── Models/             # User, Product, Customer, Transaction
│   └── Events/             # Real-time broadcasting events
├── resources/views/
│   ├── kasir/              # Kasir dashboard views
│   ├── livewire/           # POS terminal view
│   └── welcome.blade.php   # Landing page
├── routes/
│   ├── web.php             # Web routes (kasir, profile)
│   └── api.php             # API routes (auth, products, transactions)
└── database/migrations/    # Database schema
```

## 🚀 Instalasi

### Persyaratan
- PHP 8.3+
- Composer 2+
- Node.js 18+ & npm
- SQLite / MySQL / PostgreSQL

### Langkah Setup

```bash
# 1) Install dependencies
composer install
npm install

# 2) Setup environment
cp .env.example .env
php artisan key:generate

# 3) Database (default: SQLite)
php artisan migrate

# 4) Storage symlink (untuk upload gambar)
php artisan storage:link

# 5) Run dev
composer run dev
```

Atau gunakan shortcut:
```bash
composer run dev
```

### Seed data (opsional)

Setelah migrate, kamu bisa isi data demo:

```bash
php artisan smartpos:seed-demo
```

Ini akan membuat akun demo (untuk lokal):

- **Admin**: `admin@smartpos.test` / `password`
- **Kasir**: `kasir@smartpos.test` / `password`

Catatan: jangan gunakan kredensial demo ini untuk production—ganti password setelah deploy.

## 📖 Penggunaan

### Admin Panel
Login lalu akses `http://localhost:8000/admin` untuk:
- Mengelola **Produk** (tambah, edit, hapus, upload gambar)
- Mengelola **Pelanggan** (CRUD data pelanggan)
- Melihat **Transaksi** (histori semua transaksi)
- Mengelola **Users** (kasir & admin)
- **Monitoring** performa via Laravel Pulse

### Kasir
Login lalu akses `http://localhost:8000/kasir` untuk:
- Mencari dan memilih produk
- Mengelola keranjang belanja
- Memproses pembayaran
- **Mencetak struk** transaksi
- Melihat riwayat transaksi sendiri

## Checklist (fresh install)

- `composer install` + `npm install`
- `cp .env.example .env` + `php artisan key:generate`
- `php artisan migrate`
- `php artisan storage:link`
- (opsional) `php artisan smartpos:seed-demo`
- `composer run dev`
- Cek halaman:
  - `/` (landing)
  - `/admin` (admin panel)
  - `/kasir` (kasir)

## Troubleshooting

- **Gambar tidak muncul**: pastikan sudah `php artisan storage:link`.
- **Pulse error / data korup**: jalankan `php artisan pulse:clear --force` lalu refresh `/pulse`.
- **Reset data lokal**: `php artisan migrate:fresh` lalu (opsional) `php artisan smartpos:seed-demo`.

## 🔌 API Endpoints

### Autentikasi
```
POST   /api/auth/login      # Login, mendapatkan token
GET    /api/auth/me          # Info user saat ini
POST   /api/auth/logout      # Logout, revoke token
```

### Produk
```
GET    /api/products         # List semua produk
GET    /api/products/{id}    # Detail produk
POST   /api/products         # Tambah produk baru
PUT    /api/products/{id}    # Update produk
DELETE /api/products/{id}    # Hapus produk
```

### Transaksi
```
GET    /api/transactions         # List transaksi
GET    /api/transactions/{id}    # Detail transaksi
POST   /api/transactions         # Buat transaksi baru
```

### Dashboard
```
GET    /api/dashboard/stats      # Statistik dashboard
```

> Semua endpoint (kecuali login) memerlukan header `Authorization: Bearer {token}`

## 📄 License

Smart POS Template is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Contributing

Lihat `CONTRIBUTING.md`.

## Security

Lihat `SECURITY.md`.
