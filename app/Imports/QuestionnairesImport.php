<?php

namespace App\Imports;

use App\Models\Questionnaire;
use App\Models\Question;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class QuestionnairesImport implements ToCollection, WithHeadingRow
{
    private $currentQuestionnaire;
    private $rowNumber = 2; // Start from row 2 (after header)

    public function collection(Collection $rows)
    {
        Validator::make($rows->toArray(), [
            '*.title' => 'required_without:*.question|string|max:255',
            '*.question' => 'required_without:*.title|string',
            '*.type' => [
                'nullable',
                Rule::in(['multiple_choice', 'text', 'rating'])
            ],
            '*.options' => 'required_if:*.type,multiple_choice',
            '*.required' => 'nullable|boolean',
            '*.start_date' => 'nullable|date_format:Y-m-d H:i:s',
            '*.end_date' => 'nullable|date_format:Y-m-d H:i:s|after:*.start_date',
        ])->validate();

        foreach ($rows as $row) {
            $this->processRow($row);
            $this->rowNumber++;
        }
    }

    private function processRow($row)
    {
        try {
            // Handle Questionnaire
            if (!empty($row['title'])) {
                $this->currentQuestionnaire = Questionnaire::create([
                    'title' => $row['title'],
                    'description' => $row['description'] ?? null,
                    'start_date' => $this->parseDate($row['start_date']),
                    'end_date' => $this->parseDate($row['end_date']),
                    'status' => 'draft',
                    'is_template' => true,
                ]);
            }

            // Handle Question
            if (!empty($row['question']) && $this->currentQuestionnaire) {
                Question::create([
                    'questionnaire_id' => $this->currentQuestionnaire->id,
                    'question_text' => $row['question'],
                    'type' => $row['type'] ?? 'text',
                    'options' => $this->parseOptions($row['options'] ?? null),
                    'is_required' => filter_var($row['required'] ?? true, FILTER_VALIDATE_BOOLEAN),
                    'order' => $row['order'] ?? 0,
                ]);
            }
        } catch (\Exception $e) {
            throw new \Exception("Error pada baris {$this->rowNumber}: " . $e->getMessage());
        }
    }

    private function parseDate($date)
    {
        if (empty($date)) return now();

        try {
            return Carbon::createFromFormat('Y-m-d H:i:s', $date);
        } catch (\Exception $e) {
            throw new \Exception("Format tanggal tidak valid. Gunakan format: YYYY-MM-DD HH:mm:ss");
        }
    }

    private function parseOptions($options)
    {
        if (empty($options)) return null;

        return array_map('trim', explode(',', $options));
    }
}
