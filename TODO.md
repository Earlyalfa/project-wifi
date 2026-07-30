# Theme Color Adjustment (Completed)
- [x] All layout files (admin, pegawai, pelanggan, guest)
- [x] All auth pages (login, register, pegawai login)
- [x] All dashboard pages (admin, pegawai, pelanggan)
- [x] All admin feature pages (paket, users, laporan, settings)
- [x] All pegawai feature pages (pelanggan index, detail, create, scan-barcode, gangguan, settings)
- [x] All pelanggan feature pages (pembayaran, profile, pengaduan)

# QR Code ID Pelanggan Feature (Completed)
- [x] `resources/views/pegawai/pelanggan/create.blade.php` - Added QR code panel on the right side with:
  - Real-time QR code generation using `qrcodejs` library
  - Download QR button
  - Print QR button
  - Info alert about QR usage

- [x] `resources/views/pegawai/pelanggan/detail.blade.php` - Added QR code section in right column with:
  - QR code display (150x150px)
  - Download QR button
  - Print QR button
  - JavaScript for QR generation, download, and print functions
