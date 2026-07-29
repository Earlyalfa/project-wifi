# TODO: Ubah Scan QR Code → Scan QR ID Pelanggan + Pembayaran Tunai

## Step 1: Update `app/Http/Controllers/Pegawai/ScanBarcodeController.php`
- [x] Ganti logic controller:
  - Method `create()` — tetap tampilkan halaman scan
  - Method `cariPelanggan(Request)` — AJAX JSON: cari pelanggan by kode, return data + tagihan
  - Method `konfirmasiBayar(Request)` — AJAX POST: update status jadi lunas, catat kunjungan

## Step 2: Update `resources/views/pegawai/scan-barcode.blade.php`
- [x] Layout baru: Kolom kiri (Scanner QR + Upload Gambar), Kolom kanan (Data Pelanggan + Tagihan + Form Pembayaran Tunai)
- [x] Scanner QR menggunakan jsQR (sudah ada)
- [x] Upload gambar QR + auto-detect dengan jsQR dari canvas
- [x] Form konfirmasi pembayaran: nominal diterima, catatan, tombol konfirmasi
- [x] AJAX flow tanpa reload halaman

## Step 3: Update `routes/web.php`
- [x] Tambah route POST `/pegawai/scan-barcode/cari` → `cariPelanggan`
- [x] Tambah route POST `/pegawai/scan-barcode/konfirmasi` → `konfirmasiBayar`
</create_file>
