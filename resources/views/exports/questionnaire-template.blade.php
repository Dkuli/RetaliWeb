
<table>
    <thead>
        <tr>
            <th>title</th>
            <th>description</th>
            <th>start_date</th>
            <th>end_date</th>
            <th>question</th>
            <th>type</th>
            <th>options</th>
            <th>required</th>
            <th>order</th>
        </tr>
    </thead>
    <tbody>
        @foreach($examples as $example)
        <tr>
            <td>{{ $example['title'] }}</td>
            <td>{{ $example['description'] }}</td>
            <td>{{ $example['start_date'] }}</td>
            <td>{{ $example['end_date'] }}</td>
            <td>{{ $example['question'] }}</td>
            <td>{{ $example['type'] }}</td>
            <td>{{ $example['options'] }}</td>
            <td>{{ $example['required'] }}</td>
            <td>{{ $example['order'] }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="9" style="color: #666; font-style: italic;">
                * Untuk pertanyaan multiple_choice, isi kolom options dengan pilihan dipisahkan koma<br>
                * Kolom title hanya perlu diisi di baris pertama questionnaire<br>
                * Format tanggal: YYYY-MM-DD HH:mm:ss<br>
                * Type yang valid: multiple_choice, text, rating<br>
                * Required: true/false
            </td>
        </tr>
    </tbody>
</table>
