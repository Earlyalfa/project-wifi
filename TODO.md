# TODO — Hapus Data Dummy Pembayaran Admin

## Tujuan
Menghapus seluruh data pembayaran dummy dari halaman admin dan memastikan data pembayaran yang tampil adalah data asli dari transaksi sistem.

## Temuan
- File `_seed_pembayaran.php` mengisi data dummy (6 record, `pelanggan_id=1`, status `lunas`, tanpa bukti bayar)
- Pelanggan CS-0001 (Lipiya Agustina) memiliki `user_id = NULL` sehingga tidak tertaut ke akun pelanggan (user id 3)

## Langkah
- [x] Baca file terkait (controller, view, model, migrasi, seeder, skrip seed)
- [x] Konfirmasi rencana ke user
- [x] Hapus seluruh data pembayaran dummy dari database (6 record dihapus)
- [x] Perbaiki tautan `user_id` pelanggan CS-0001 → user id 3 (Lipiya Agustina)
- [x] Hapus file `_seed_pembayaran.php`
- [x] Hapus file analisis sementara (`_check_pembayaran.php`, `_analyze_pembayaran.php`, `_cleanup_pembayaran.php`, `_verify_clean.php`)
- [x] Verifikasi data pembayaran di admin kosong (tabel bersih, 0 record)

