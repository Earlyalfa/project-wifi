# TODO: Halaman Pembayaran WiFi untuk Pegawai - SELESAI ✅

## Step 1: Create Controller
- [x] `app/Http/Controllers/Pegawai/PembayaranController.php`
  - index() - daftar pembayaran dengan filter
  - show() - detail pembayaran + bukti bayar
  - verifikasi() - setujui/tolak pembayaran

## Step 2: Create View - Index
- [x] `resources/views/pegawai/pembayaran/index.blade.php`
  - Statistik cards (Total, Menunggu, Lunas, Ditolak)
  - Tabel dengan filter (search, status, periode/month)

## Step 3: Create View - Show
- [x] `resources/views/pegawai/pembayaran/show.blade.php`
  - Detail pembayaran + pelanggan
  - Bukti bayar (image preview / PDF link)
  - Form verifikasi (setujui/tolak dengan konfirmasi SweetAlert2)

## Step 4: Update Routes
- [x] `routes/web.php` - Tambah route pembayaran pegawai (index, show, verifikasi)

## Step 5: Update Sidebar
- [x] `resources/views/layouts/pegawai.blade.php` - Update menu Pembayaran dari `#` ke route `pegawai.pembayaran.index`

