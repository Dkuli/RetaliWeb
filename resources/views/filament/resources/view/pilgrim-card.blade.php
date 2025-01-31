{{-- resources/views/pilgrim-card.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <style>
        .card {
            width: 3.375in;
            height: 2.125in;
            border: 1px solid #000;
            position: relative;
            padding: 10px;
            font-family: Arial, sans-serif;
        }
        .photo {
            width: 1in;
            height: 1in;
            position: absolute;
            top: 10px;
            left: 10px;
            border: 1px solid #ddd;
        }
        .details {
            margin-left: 1.2in;
            font-size: 12px;
        }
        .qr {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 1in;
            height: 1in;
        }
    </style>
</head>
<body>
    <div class="card">
        <img src="{{ $pilgrim->getFirstMediaUrl('photo') }}" class="photo">
        <div class="details">
            <h2>{{ $pilgrim->name }}</h2>
            <p>ID: {{ str_pad($pilgrim->id, 6, '0', STR_PAD_LEFT) }}</p>
            <p>Group: {{ $pilgrim->groups->first()?->name }}</p>
            <p>Gender: {{ ucfirst($pilgrim->gender) }}</p>
        </div>
        <div class="qr">{!! $qrCode !!}</div>
    </div>
</body>
</html>
