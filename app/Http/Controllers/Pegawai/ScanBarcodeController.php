<?php

namespace App\Http\Controllers\Pegawai;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class ScanBarcodeController extends Controller
{
    public function create()
    {
        return view('pegawai.scan-barcode');
    }

    /**
     * Dipanggil lewat JS (fetch) setelah kamera berhasil membaca QR/barcode,
     * atau lewat form input manual kode pelanggan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode' => ['required', 'string'],
        ]);

        $pelanggan = Pelanggan::where('kode', $request->kode)->first();

        if (! $pelanggan) {
            return back()->withErrors(['kode' => 'Kode pelanggan tidak ditemukan.']);
        }

        return redirect()->route('pegawai.pelanggan.show', $pelanggan);
    }
}
