<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QuestionnaireTemplateExport implements FromView, WithStyles
{
    public function view(): View
    {
        return view('exports.questionnaire-template', [
            'examples' => [
                [
                    'title' => 'Survey Kepuasan Pelanggan',
                    'description' => 'Survey untuk mengukur tingkat kepuasan pelanggan',
                    'start_date' => '2024-01-01 00:00:00',
                    'end_date' => '2024-12-31 23:59:59',
                    'question' => 'Seberapa puas Anda dengan pelayanan kami?',
                    'type' => 'multiple_choice',
                    'options' => 'Sangat Puas,Puas,Cukup Puas,Tidak Puas',
                    'required' => 'true',
                    'order' => 1
                ],
                [
                    'title' => '',
                    'description' => '',
                    'start_date' => '',
                    'end_date' => '',
                    'question' => 'Apa saran Anda untuk perbaikan kami?',
                    'type' => 'text',
                    'options' => '',
                    'required' => 'true',
                    'order' => 2
                ]
            ]
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A:H' => ['alignment' => ['wrapText' => true]],
        ];
    }
}
