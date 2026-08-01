# TODO: Perbaikan Quick Action di Dashboard Admin

## Langkah
- [x] Analisis masalah: 3 tombol Quick Action masih menggunakan `href="#"`
- [x] Edit `resources/views/admin/dashboard.blade.php`:
  - [x] "Tambah Pengguna" → `route('admin.users.create')`
  - [x] "Tambah Paket WiFi" → `route('admin.paket.create')`
  - [x] "Cetak Laporan" → `route('admin.laporan')`
  - [x] "Lihat Semua Pengaduan" → `route('admin.pengaduan.index')`
- [x] Verifikasi perubahan berhasil — semua route terdaftar & valid

