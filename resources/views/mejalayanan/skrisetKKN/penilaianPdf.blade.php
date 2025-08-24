<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penilaian  Pengajuan Surat Keterangan Riset KKN / PKL</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 4px; }
        th { background-color: #f2f2f2; }
        .wrap { word-wrap:break-word; max-width:200px; }
    </style>
</head>
<body>
    <h3 style="text-align: center; margin-bottom: 15px;">Laporan Penilaian  Pengajuan Surat Keterangan Riset KKN / PKL</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pemohon</th>
                <th>NIK</th>
                <th>Layanan</th>
                <th>Tanggal Masuk</th>
                <th>Status</th>
                <th>Penilaian</th>
                <th class="wrap">Saran / Kritik</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $item)
                @php
                    // mapping angka: 1 = tidak puas, dst
                    $label = [
                        'tidak_puas' => 'Tidak Puas',
                        'cukup' => 'Cukup',
                        'puas' => 'Puas',
                        'sangat_puas' => 'Sangat Puas',
                    ];
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->nama_pemohon }}</td>
                    <td>{{ $item->nik_pemohon }}</td>
                    <td> Pengajuan Surat Keterangan Riset KKN / PKL</td>
                    <td>{{ $item->created_at ? \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') : '-' }}</td>
                    <td>{{ $item->status }}</td>
                    <td>{{ $label[$item->penilaian] ?? '' }}</td>
                    <td class="wrap">{{ $item->saran_kritik }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
