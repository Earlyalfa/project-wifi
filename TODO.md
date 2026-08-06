# TODO - Perbaikan Pengaduan Gangguan Pelanggan

## Masalah
Pengaduan gangguan dari pelanggan tidak masuk ke:
- Halaman pengaduan gangguan pegawai (tabel `gangguans`)
- Notifikasi pegawai dan admin

## Penyebab
Terdapat 2 tabel terpisah: `pengaduans` (dibaca admin) dan `gangguans` (dibaca pegawai). Saat pelanggan mengirim pengaduan, hanya record di `pengaduans` yang dibuat, tanpa membuat record di `gangguans` dan tanpa notifikasi.

## Langkah Perbaikan
- [x] 1. Buat migration `add_pengaduan_id_to_gangguans_table` (kolom `pengaduan_id` nullable FK ke `pengaduans`)
- [x] 2. Update `app/Models/Gangguan.php` - tambah `pengaduan_id` ke `$fillable` + relasi `pengaduan()`
- [x] 3. Update `app/Http/Controllers/Pelanggan/PengaduanController.php`:
  - [x] Buat record `Gangguan` saat pelanggan mengirim pengaduan
  - [x] Kirim notifikasi ke semua pegawai & admin via `Notification::notifyPegawai()`
- [x] 4. Update `app/Http/Controllers/Admin/PengaduanController.php` - sinkronkan status ke `Gangguan` terkait
- [x] 5. Update `app/Http/Controllers/Pegawai/GangguanController.php` - sinkronkan status ke `Pengaduan` terkait
- [x] 6. Jalankan migration `php artisan migrate`
- [x] 7. Verifikasi hasil (cek sintaks semua file yang diubah)

