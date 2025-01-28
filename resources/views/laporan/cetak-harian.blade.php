<!DOCTYPE html>
<html>
<head>
    <title>Laporan Parkir Harian - {{ $tanggal }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .summary-item {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Parkir Harian</h1>
        <p>{{ Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <h3>Total Kendaraan</h3>
            <p>{{ $totalKendaraan }}</p>
        </div>
        <div class="summary-item">
            <h3>Total Pendapatan</h3>
            <p>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
        <div class="summary-item">
            <h3>Kendaraan per Jenis</h3>
            @foreach($kendaraanPerJenis as $jenis => $jumlah)
                <p>{{ ucfirst($jenis) }}: {{ $jumlah }}</p>
            @endforeach
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nomor Plat</th>
                <th>Jenis</th>
                <th>Waktu Masuk</th>
                <th>Waktu Keluar</th>
                <th>Biaya</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporanHarian as $kendaraan)
            <tr>
                <td>{{ $kendaraan->nomor_plat }}</td>
                <td>{{ ucfirst($kendaraan->jenis_kendaraan) }}</td>
                <td>{{ Carbon\Carbon::parse($kendaraan->waktu_masuk)->format('H:i:s') }}</td>
                <td>
                    {{ $kendaraan->waktu_keluar ? Carbon\Carbon::parse($kendaraan->waktu_keluar)->format('H:i:s') : '-' }}
                </td>
                <td>Rp {{ number_format($kendaraan->biaya_parkir ?? 0, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada data kendaraan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
