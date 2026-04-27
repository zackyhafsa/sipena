<?php

namespace App\Imports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToModel, WithHeadingRow
{
    public $exam_id;

    // Kita butuh ID Ujian untuk disematkan ke setiap soal yang di-import
    public function __construct($exam_id)
    {
        $this->exam_id = $exam_id;
    }

    public function model(array $row)
    {
        // Pastikan nama kolom di Excel persis seperti yang di dalam kurung siku ['...']
        return new Question([
            'exam_id' => $this->exam_id,
            'type' => $row['type'] ?? 'multiple_choice',
            'question_text' => $row['question_text'],
            'option_a' => $row['option_a'] ?? null,
            'option_b' => $row['option_b'] ?? null,
            'option_c' => $row['option_c'] ?? null,
            'option_d' => $row['option_d'] ?? null,
            'option_e' => $row['option_e'] ?? null,
            'correct_answer' => !empty($row['correct_answer']) ? strtoupper($row['correct_answer']) : null,
            'correct_answer_essay' => $row['correct_answer_essay'] ?? null,
            'score_weight' => $row['score_weight'] ?? 1,
        ]);
    }
}
