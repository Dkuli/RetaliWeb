
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $record->title }} - Responses</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .summary-card {
            padding: 15px;
            margin-bottom: 20px;
            background: #f3f4f6;
            border-radius: 8px;
        }
        .question { margin-bottom: 30px; }
        .answers { margin-left: 20px; }
        .chart { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $record->title }}</h1>
        <p>{{ $record->description }}</p>
        <p>Total Responses: {{ $summary['total'] }}</p>
        <p>Completion Rate: {{ $summary['completion_rate'] }}%</p>
    </div>

    @foreach($summary['questions'] as $question)
    <div class="question">
        <h3>{{ $question['text'] }}</h3>

        @if($question['type'] === 'multiple_choice')
            <table>
                <thead>
                    <tr>
                        <th>Option</th>
                        <th>Count</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($question['answers'] as $option => $count)
                    <tr>
                        <td>{{ $option }}</td>
                        <td>{{ $count }}</td>
                        <td>{{ round(($count / $summary['total']) * 100, 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="answers">
                @foreach($question['answers'] as $answer)
                <p>- {{ $answer }}</p>
                @endforeach
            </div>
        @endif
    </div>
    @endforeach
</body>
</html>
