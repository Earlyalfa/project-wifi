<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gangguan;
use App\Models\Pembayaran;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $filters = $this->getFilters($request);

        $pembayaranList = $this->getFilteredPembayaran($request)->latest()->get();

        // Statistik
        $dari = $filters['dari'];
        $sampai = $filters['sampai'];
        $totalPendapatan = Pembayaran::where('status', 'lunas')
            ->whereBetween('created_at', [$dari, $sampai . ' 23:59:59'])
            ->sum('jumlah');

        $totalTunai = Pembayaran::where('status', 'lunas')
            ->where('metode_pembayaran', 'tunai')
            ->whereBetween('created_at', [$dari, $sampai . ' 23:59:59'])
            ->sum('jumlah');

        $totalQris = Pembayaran::where('status', 'lunas')
            ->where('metode_pembayaran', 'qris')
            ->whereBetween('created_at', [$dari, $sampai . ' 23:59:59'])
            ->sum('jumlah');

        $totalPengaduan = Gangguan::whereBetween('created_at', [$dari, $sampai . ' 23:59:59'])->count();

        return view('admin.laporan.index', array_merge($filters, [
            'pembayaranList' => $pembayaranList,
            'totalPendapatan' => $totalPendapatan,
            'totalTunai' => $totalTunai,
            'totalQris' => $totalQris,
            'totalPengaduan' => $totalPengaduan,
        ]));
    }

    /**
     * Detail transaksi pembayaran dalam laporan.
     */
    public function show(Pembayaran $pembayaran)
    {
        $pembayaran->load(['pelanggan', 'penerima']);
        return view('admin.laporan.show', compact('pembayaran'));
    }

    /**
     * Export laporan pembayaran ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $filters = $this->getFilters($request);
        $pembayaranList = $this->getFilteredPembayaran($request)->latest()->get();

        $totalPendapatan = $pembayaranList->where('status', 'lunas')->sum('jumlah');
        $totalTunai = $pembayaranList->where('status', 'lunas')->where('metode_pembayaran', 'tunai')->sum('jumlah');
        $totalQris = $pembayaranList->where('status', 'lunas')->where('metode_pembayaran', 'qris')->sum('jumlah');

        $pdf = Pdf::loadView('admin.laporan.pdf', array_merge($filters, [
            'pembayaranList' => $pembayaranList,
            'totalPendapatan' => $totalPendapatan,
            'totalTunai' => $totalTunai,
            'totalQris' => $totalQris,
        ]))
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isRemoteEnabled' => true,
            ]);

        $filename = 'laporan-' . $filters['dari'] . '_' . $filters['sampai'] . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Export laporan pembayaran ke Excel (XLSX).
     */
    public function exportExcel(Request $request)
    {
        $filters = $this->getFilters($request);
        $pembayaranList = $this->getFilteredPembayaran($request)->latest()->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Pembayaran');

        // ---- Gaya umum ----
        $headerFill = [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['argb' => 'FF7C3AED'],
        ];
        $headerFont = [
            'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ];
        $thinBorder = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFCBD5E1']]],
        ];

        // ---- Judul ----
        $sheet->setCellValue('A1', 'LAPORAN PEMBAYARAN WIFIPAY');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->setCellValue('A2', 'Periode: ' . \Carbon\Carbon::parse($filters['dari'])->format('d M Y') . ' s/d ' . \Carbon\Carbon::parse($filters['sampai'])->format('d M Y'));
        $sheet->setCellValue('A3', 'Jenis Laporan: ' . $this->labelJenisLaporan($filters['jenis_laporan']) . ' | Status: ' . $this->labelStatus($filters['status_pembayaran']));
        $sheet->getStyle('A2:A3')->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FF64748B'));

        // ---- Header kolom ----
        $headers = ['No', 'Tanggal', 'ID Transaksi', 'Nama Pelanggan', 'Paket WiFi', 'Metode', 'Nominal', 'Status', 'Pegawai'];
        $colLetters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

        $rowHeader = 5;
        foreach ($headers as $i => $header) {
            $cell = $colLetters[$i] . $rowHeader;
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->applyFromArray($headerFill);
            $sheet->getStyle($cell)->applyFromArray($headerFont);
            $sheet->getStyle($cell)->applyFromArray($thinBorder);
        }

        // ---- Data ----
        $row = $rowHeader + 1;
        foreach ($pembayaranList as $index => $bayar) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $bayar->created_at->format('d/m/Y'));
            $sheet->setCellValue('C' . $row, '#' . str_pad($bayar->id, 5, '0', STR_PAD_LEFT));
            $sheet->setCellValue('D' . $row, $bayar->pelanggan->nama ?? '-');
            $sheet->setCellValue('E' . $row, $bayar->pelanggan->paket ?? '-');
            $sheet->setCellValue('F' . $row, $this->labelMetode($bayar->metode_pembayaran));
            $sheet->setCellValue('G' . $row, $bayar->jumlah);
            $sheet->getStyle('G' . $row)->getNumberFormat()->setFormatCode('"Rp" #,##0');
            $sheet->setCellValue('H' . $row, $this->labelStatusBayar($bayar->status));
            $sheet->setCellValue('I' . $row, $bayar->catatan ?? '-');
            $sheet->getStyle('A' . $row . ':I' . $row)->applyFromArray($thinBorder);
            $row++;
        }

        // ---- Baris total ----
        $totalRow = $row + 1;
        $sheet->setCellValue('F' . $totalRow, 'TOTAL LUNAS');
        $sheet->getStyle('F' . $totalRow)->getFont()->setBold(true);
        $sheet->setCellValue('G' . $totalRow, $pembayaranList->where('status', 'lunas')->sum('jumlah'));
        $sheet->getStyle('G' . $totalRow)->getNumberFormat()->setFormatCode('"Rp" #,##0');
        $sheet->getStyle('G' . $totalRow)->getFont()->setBold(true);
        $sheet->getStyle('F' . $totalRow . ':G' . $totalRow)->applyFromArray($thinBorder);

        // ---- Auto width ----
        foreach ($colLetters as $letter) {
            $sheet->getColumnDimension($letter)->setAutoSize(true);
        }
        $sheet->getColumnDimension('D')->setWidth(28);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('I')->setWidth(22);

        // ---- Export ----
        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan-' . $filters['dari'] . '_' . $filters['sampai'] . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'laporan');
        $writer->save($tempFile);

        return Response::download($tempFile, $filename, ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])
            ->deleteFileAfterSend(true);
    }

    /**
     * Ambil & validasi parameter filter laporan.
     */
    private function getFilters(Request $request): array
    {
        $dari = $request->input('dari', now()->startOfMonth()->format('Y-m-d'));
        $sampai = $request->input('sampai', now()->endOfMonth()->format('Y-m-d'));
        $jenis_laporan = $request->input('jenis_laporan', 'semua');
        $status_pembayaran = $request->input('status_pembayaran', 'semua');

        return compact('dari', 'sampai', 'jenis_laporan', 'status_pembayaran');
    }

    /**
     * Query pembayaran berdasarkan filter (dipakai di index & export).
     */
    private function getFilteredPembayaran(Request $request)
    {
        $filters = $this->getFilters($request);
        $dari = $filters['dari'];
        $sampai = $filters['sampai'];
        $jenis_laporan = $filters['jenis_laporan'];
        $status_pembayaran = $filters['status_pembayaran'];

        $query = Pembayaran::with(['pelanggan' => function ($q) {
            $q->select('id', 'nama', 'paket');
        }])
        ->whereBetween('created_at', [$dari, $sampai . ' 23:59:59']);

        // Filter jenis laporan
        if ($jenis_laporan === 'tunggakan') {
            $query->where('status', 'belum_bayar');
        } elseif ($jenis_laporan === 'lunas') {
            $query->where('status', 'lunas');
        }

        // Filter status pembayaran
        if ($status_pembayaran !== 'semua') {
            $query->where('status', $status_pembayaran);
        }

        return $query;
    }

    private function labelJenisLaporan(string $jenis): string
    {
        return match ($jenis) {
            'pembayaran' => 'Semua Transaksi',
            'tunggakan' => 'Tunggakan',
            'lunas' => 'Lunas',
            default => 'Semua Transaksi',
        };
    }

    private function labelStatus(string $status): string
    {
        return match ($status) {
            'lunas' => 'Lunas',
            'belum_bayar' => 'Belum Dibayar',
            'dibatalkan' => 'Dibatalkan',
            default => 'Semua Status',
        };
    }

    private function labelMetode(?string $metode): string
    {
        return match (strtolower($metode ?? '')) {
            'tunai' => 'Tunai',
            'qris' => 'QRIS',
            'transfer' => 'Transfer',
            default => '-',
        };
    }

    private function labelStatusBayar(?string $status): string
    {
        return match ($status) {
            'lunas' => 'Lunas',
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'menunggu_penagihan' => 'Menunggu Penagihan',
            'ditolak' => 'Ditolak',
            'belum_bayar' => 'Belum Dibayar',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst($status ?? '-'),
        };
    }
}

