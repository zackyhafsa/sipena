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
            'question_text' => $row['pertanyaan'],
            'option_a' => $row['opsi_a'],
            'option_b' => $row['opsi_b'],
            'option_c' => $row['opsi_c'],
            'option_d' => $row['opsi_d'],
            'option_e' => $row['opsi_e'] ?? null, // Opsional
            'correct_answer' => strtoupper($row['kunci_jawaban']), // Memastikan huruf besar (A, B, C, D, E)
            'score_weight' => $row['bobot'] ?? 1,
        ]);
    }
}
