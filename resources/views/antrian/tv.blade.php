<!DOCTYPE html>
<html class="bg-black text-white text-center text-2xl">
<head>
    <meta http-equiv="refresh" content="5"> {{-- refresh otomatis tiap 5 detik --}}
    <title>Nomor Antrian Hari Ini</title>
</head>
<body class="p-10">
    <h1 class="text-4xl mb-6 font-bold">Antrian Layanan Desa</h1>
    <table class="mx-auto border border-white">
        <thead>
            <tr>
                <th class="p-2 border border-white">Nomor</th>
                <th class="p-2 border border-white">Nama</th>
                <th class="p-2 border border-white">Layanan</th>
                <th class="p-2 border border-white">Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($antrianHariIni as $item)
                <tr>
                    <td class="p-2 border border-white">{{ $item->queue_number }}</td>
                    <td class="p-2 border border-white">{{ $item->nama_pemohon }}</td>
                    <td class="p-2 border border-white">{{ $item->layanan }}</td>
                    <td class="p-2 border border-white">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
