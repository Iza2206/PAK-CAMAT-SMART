<!DOCTYPE html>
<html lang="id" class="bg-black text-white text-center">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="5"> {{-- refresh otomatis tiap 5 detik --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antrian Layanan Desa</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            padding: 2rem;
            background-color: #000;
            color: #fff;
        }

        h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 2rem;
            color: #00e0ff;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            font-size: 1.5rem;
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.2);
        }

        th, td {
            padding: 1rem;
            border: 1px solid #fff;
        }

        thead {
            background-color: #0f172a;
        }

        tbody tr:nth-child(even) {
            background-color: #1e293b;
        }

        tbody tr:nth-child(odd) {
            background-color: #111827;
        }

        td {
            color: #e0f2fe;
        }

        th {
            color: #7dd3fc;
        }

        @media screen and (max-width: 768px) {
            table {
                font-size: 1rem;
            }

            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <h1>📢 Antrian Layanan Desa Hari Ini</h1>

    <table>
        <thead>
            <tr>
                <th>Nomor</th>
                <th>Nama</th>
                <th>Layanan</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($antrianHariIni as $item)
                <tr>
                    <td><strong>{{ $item->queue_number }}</strong></td>
                    <td>{{ $item->nama_pemohon }}</td>
                    <td>{{ $item->layanan }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-lg py-6 text-red-400">Belum ada antrian hari ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
