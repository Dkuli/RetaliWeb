<!DOCTYPE html>
<html>
<head>
    <title>Kartu Jamaah - {{ $pilgrim->name }}</title>
    <style>
        @page {
            size: 85.6mm 54mm;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .card {
            width: 85.6mm;
            height: 54mm;
            position: relative;
            background: white;
        }
        .card-front {
            padding: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .photo {
            width: 25mm;
            height: 35mm;
            object-fit: cover;
            float: left;
            margin-right: 10px;
        }
        .details {
            font-size: 10pt;
        }
        .details p {
            margin: 3px 0;
        }
        .name {
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 5px;
        }
        .group {
            color: #008000;
            font-weight: bold;
        }
        .footer {
            position: absolute;
            bottom: 5px;
            left: 10px;
            right: 10px;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-front">
            <div class="header">
                <strong>KARTU IDENTITAS JAMAAH</strong>
            </div>

            @if($pilgrim->photo)
                <img src="{{ storage_path('app/public/' . $pilgrim->photo) }}" class="photo">
            @else
                <img src="{{ public_path('images/default-avatar.png') }}" class="photo">
            @endif

            <div class="details">
                <p class="name">{{ $pilgrim->name }}</p>
                <p class="group">{{ $pilgrim->group->name ?? '-' }}</p>
                <p>{{ $pilgrim->gender == 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                <p>{{ $pilgrim->phone }}</p>
            </div>

            <div class="footer">
                Tanggal Cetak: {{ now()->format('d/m/Y') }}
            </div>
        </div>
    </div>
</body>
</html>
