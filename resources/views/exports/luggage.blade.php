
<!DOCTYPE html>
<html>
<head>
    <title>Data Koper</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
        }
    </style>
</head>
<body>
    <h2>Data Koper</h2>
    @if($startDate && $endDate)
        <p>Periode: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No. Koper</th>
                <th>Nama Jamaah</th>
                <th>No. Telepon</th>
                <th>Keloter</th>
                <th>Jumlah Scan</th>
                <th>Scan Terakhir</th>
                <th>Dibuat Pada</th>
            </tr>
        </thead>
        <tbody>
            @foreach($luggage as $item)
                <tr>
                    <td>{{ $item->luggage_number }}</td>
                    <td>{{ $item->pilgrim_name }}</td>
                    <td>{{ $item->phone }}</td>
                    <td>{{ $item->group }}</td>
                    <td>{{ $item->scans_count }}</td>
                    <td>{{ $item->scans->last()?->scanned_at?->format('d/m/Y H:i:s') ?? '-' }}</td>
                    <td>{{ $item->created_at->format('d/m/Y H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
