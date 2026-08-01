# TODO: Fitur Jatuh Tempo Tagihan Per Pelanggan

## Langkah (Selesai)
- [x] Migrasi: kolom `tagihan_jatuh_tempo` (tinyInteger, 1-31) di tabel `pelanggans`
- [x] Model: `$fillable` + cast di Pelanggan
- [x] **Pegawai:**
  - [x] Form create pelanggan — dropdown tanggal jatuh tempo
  - [x] Controller `store` — validasi & simpan
  - [x] Form edit pelanggan — dropdown tanggal jatuh tempo
  - [x] Controller `update` — validasi & simpan
  - [x] Detail pelanggan — tampilkan tanggal jatuh tempo
  - [x] Index pelanggan — kolom "Jatuh Tempo" dengan data per baris
- [x] **Admin:**
  - [x] Form create pengguna — dropdown tanggal jatuh tempo
  - [x] Controller `store` — validasi & simpan
  - [x] Form edit pengguna — dropdown tanggal jatuh tempo
  - [x] Controller `updateUser` — validasi & simpan
  - [x] Detail pengguna — card "Jatuh Tempo Tagihan"
- [x] **Pelanggan Dashboard:**
  - [x] Tampilkan `tagihan_jatuh_tempo` dari model pelanggan (fallback: tanggal 10)
- [x] Migrasi dijalankan ke database ✅

