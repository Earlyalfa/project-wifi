<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Pembayaran WiFiPay</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1e293b;
        }
        .header { width: 100%; border-bottom: 3px solid #7c3aed; padding-bottom: 10px; margin-bottom: 14px; }
        .header table { width: 100%; border-collapse: collapse; }
        .logo-title { font-size: 18px; font-weight: bold; color: #1a1a2e; }
        .logo-title span { color: #7c3aed; }
        .header-right { text-align: right; font-size: 9px; color: #64748b; }
        .title-laporan {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            color: #1a1a2e;
            margin-bottom: 4px;
        }
        .subtitle-laporan {
            text-align: center;
            font-size: 10px;
            color: #64748b;
            margin-bottom: 14px;
        }
        .info-box {
            width: 100%;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 14px;
            background: #f8fafc;
            font-size: 9px;
        }
        .info-box table { width: 100%; border-collapse: collapse; }
        .info-box td { padding: 2px 0; }
        .info-box .label { color: #64748b; width: 130px; }
        .info-box .value { font-weight: bold; color: #334155; }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        table.data th {
            background: #7c3aed;
            color: #ffffff;
            padding: 6px 6px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            border: 1px solid #6d28d9;
        }
        table.data td {
            padding: 5px 6px;
            border: 1px solid #e2e8f0;
            font-size: 9px;
        }
        table.data tr:nth-child(even) td { background: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge {
            padding: 2px 8px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 8px;
        }
        .badge-lunas { background: #d1fae5; color: #047857; }
        .badge-belum { background: #ffe4e6; color: #be123c; }
        .badge-batal { background: #f1f5f9; color: #64748b; }
        .badge-verifikasi { background: #fef3c7; color: #b45309; }
        .badge-penagihan { background: #e0f2fe; color: #0369a1; }
        .badge-tolak { background: #fee2e2; color: #b91c1c; }
        .badge-default { background: #f1f5f9; color: #475569; }
        .summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .summary td {
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            text-align: center;
            width: 25%;
        }
        .summary .num-label { font-size: 8px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .summary .num-value { font-size: 13px; font-weight: bold; color: #7c3aed; margin-top: 3px; }
        .kotak-nilai { color: #1e293b; }
        .footer {
            width: 100%;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            font-size: 8px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="logo-title">WiFi<span>Pay</span></div>
                    <div style="font-size: 9px; color:#64748b;">Layanan Internet WiFi</div>
                </td>
                <td class="header-right">
                    <div>Dicetak: {{ now()->format('d M Y H:i') }}</div>
                    <div>Oleh: {{ auth()->user()->name ?? '-' }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- JUDUL --}}
    <div class="title-laporan">LAPORAN PEMBAYARAN</div>
    <div class="subtitle-laporan">
        Periode {{ \Carbon\Carbon::parse($dari)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($sampai)->format('d M Y') }}
    </div>

    {{-- INFO FILTER --}}
    <div class="info-box">
        <table>
            <tr>
                <td class="label">Jenis Laporan</td>
                <td class="value">
                    @if ($jenis_laporan === 'tunggakan') Tunggakan
                    @elseif ($jenis_laporan === 'lunas') Lunas
                    @else Semua Transaksi @endif
                </td>
                <td class="label">Status Pembayaran</td>
                <td class="value">
                    @if ($status_pembayaran === 'lunas') Lunas
                    @elseif ($status_pembayaran === 'belum_bayar') Belum Dibayar
                    @elseif ($status_pembayaran === 'dibatalkan') Dibatalkan
                    @else Semua Status @endif
                </td>
                <td class="label">Jumlah Record</td>
                <td class="value">{{ $pembayaranList->count() }} transaksi</td>
            </tr>
        </table>
    </div>

    {{-- RINGKASAN --}}
    <table class="summary">
        <tr>
            <td>
                <div class="num-label">Total Pendapatan</div>
                <div class="num-value">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="num-label">Total Tunai</div>
                <div class="num-value">Rp {{ number_format($totalTunai, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="num-label">Total QRIS</div>
                <div class="num-value">Rp {{ number_format($totalQris, 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="num-label">Total Transaksi</div>
                <div class="num-value kotak-nilai">{{ $pembayaranList->count() }}</div>
            </td>
        </tr>
    </table>

    {{-- TABEL DATA --}}
    <table class="data">
        <thead>
            <tr>
                <th style="width:4%;">No</th>
                <th style="width:10%;">Tanggal</th>
                <th style="width:13%;">ID Transaksi</th>
                <th style="width:16%;">Nama Pelanggan</th>
                <th style="width:12%;">Paket WiFi</th>
                <th style="width:9%;">Metode</th>
                <th style="width:14%;">Nominal</th>
                <th style="width:12%;">Status</th>
                <th style="width:10%;">Pegawai</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pembayaranList as $index => $bayar)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $bayar->created_at->format('d/m/Y') }}</td>
                    <td>#{{ str_pad($bayar->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $bayar->pelanggan->nama ?? '-' }}</td>
                    <td>{{ $bayar->pelanggan->paket ?? '-' }}</td>
                    <td class="text-center">
                        @if ($bayar->metode_pembayaran === 'tunai') Tunai
                        @elseif ($bayar->metode_pembayaran === 'qris') QRIS
                        @elseif ($bayar->metode_pembayaran === 'transfer') Transfer
                        @else - @endif
                    </td>
                    <td class="text-right">Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @php
                            $st = $bayar->status;
                            $badge = 'badge-default';
                            if ($st === 'lunas') $badge = 'badge-lunas';
                            elseif ($st === 'belum_bayar') $badge = 'badge-belum';
                            elseif ($st === 'dibatalkan') $badge = 'badge-batal';
                            elseif ($st === 'menunggu_verifikasi') $badge = 'badge-verifikasi';
                            elseif ($st === 'menunggu_penagihan') $badge = 'badge-penagihan';
                            elseif ($st === 'ditolak') $badge = 'badge-tolak';
                        @endphp
                        <span class="badge {{ $badge }}">
                            @if ($st === 'lunas') Lunas
                            @elseif ($st === 'belum_bayar') Belum Dibayar
                            @elseif ($st === 'dibatalkan') Dibatalkan
                            @elseif ($st === 'menunggu_verifikasi') Menunggu Verifikasi
                            @elseif ($st === 'menunggu_penagihan') Menunggu Penagihan
                            @elseif ($st === 'ditolak') Ditolak
                            @else {{ $st }} @endif
                        </span>
                    </td>
                    <td class="text-center">{{ $bayar->catatan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 20px 8px; color:#94a3b8;">
                        Tidak ada data laporan pada periode dan filter ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- TOTAL --}}
    <table style="width:100%; border-collapse:collapse;">
        <tr>
            <td style="width:78%; font-size:9px; text-align:right; padding:6px 8px; font-weight:bold;">
                Total (Hanya Lunas)
            </td>
            <td style="width:22%; text-align:right; border:1px solid #7c3aed; border-radius:4px; padding:6px 8px; font-size:11px; font-weight:bold; color:#7c3aed;">
                Rp {{ number_format($pembayaranList->where('status', 'lunas')->sum('jumlah'), 0, ',', '.') }}
            </td>
        </tr>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        Dokumen ini digenerate otomatis oleh sistem WiFiPay — {{ now()->format('d-m-Y H:i:s') }}. Data sesuai filter laporan yang dipilih.
    </div>

</body>
</html>

