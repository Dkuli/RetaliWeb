<?php

namespace App\Exports;

use App\Models\Luggage;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Database\Eloquent\Builder;

class LuggageExport implements FromQuery, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function query()
    {
        return Luggage::query()
            ->when($this->startDate && $this->endDate, function (Builder $query) {
                $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->with(['scans']);
    }

    public function headings(): array
    {
        return [
            'No. Koper',
            'Nama Jamaah',
            'No. Telepon',
            'Keloter',
            'Jumlah Scan',
            'Scan Terakhir',
            'Dibuat Pada',
        ];
    }

    public function map($luggage): array
    {
        return [
            $luggage->luggage_number,
            $luggage->pilgrim_name,
            $luggage->phone,
            $luggage->group,
            $luggage->scans_count,
            $luggage->scans->last()?->scanned_at?->format('d/m/Y H:i:s') ?? '-',
            $luggage->created_at->format('d/m/Y H:i:s'),
        ];
    }
}
