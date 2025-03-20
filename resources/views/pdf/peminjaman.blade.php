<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 14px;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }
        .status-dipinjam { background: #e3f2fd; color: #1565c0; }
        .status-dikembalikan { background: #e8f5e9; color: #2e7d32; }
        .status-terlambat { background: #ffebee; color: #c62828; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Data Peminjaman - {{ $status }}</h1>
        <p>Periode: {{ $dateStart }} - {{ $dateEnd }}</p>
        <p>Dicetak pada: {{ $timestamp }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Peminjaman</th>
                <th>Tanggal</th>
                <th>Buku</th>
                <th>Peminjam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->id }}</td>
                <td>{{ $item->created_at->format('d M Y') }}</td>
                <td>
                    <div style="font-weight: bold;">{{ $item->buku->judul }}</div>
                    <div>Penulis: {{ $item->buku->penulis }}</div>
                    <div>ISBN: {{ $item->buku->isbn }}</div>
                </td>
                <td>
                    <div style="font-weight: bold;">{{ $item->user->name }}</div>
                    <div>{{ $item->user->email }}</div>
                    <div>Telp: {{ $item->user->phone }}</div>
                </td>
                <td>
                    <div class="status {{ strtolower('status-'.$item->status) }}">
                        {{ $item->status }}
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 