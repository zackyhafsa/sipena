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
        // Mendukung format bahasa indonesia dan format lama (inggris)
        $jenisSoal = strtolower($row['jenis_soal'] ?? $row['type'] ?? 'pilihan_ganda');
        $tipe = match ($jenisSoal) {
            'esai', 'essay', 'essay_question' => 'essay',
            default => 'multiple_choice',
        };

        $teksSoal = $row['teks_soal'] ?? $row['question_text'] ?? null;
        if (empty($teksSoal)) return null; // skip jika soal kosong

        $jawabanPilgan = $row['jawaban_benar_pilgan'] ?? $row['correct_answer'] ?? null;

        return new Question([
            'exam_id' => $this->exam_id,
            'type' => $tipe,
            'question_text' => $teksSoal,
            'option_a' => $row['opsi_a'] ?? $row['option_a'] ?? null,
            'option_b' => $row['opsi_b'] ?? $row['option_b'] ?? null,
            'option_c' => $row['opsi_c'] ?? $row['option_c'] ?? null,
            'option_d' => $row['opsi_d'] ?? $row['option_d'] ?? null,
            'option_e' => $row['opsi_e'] ?? $row['option_e'] ?? null,
            'correct_answer' => !empty($jawabanPilgan) ? strtoupper(trim($jawabanPilgan)) : null,
            'correct_answer_essay' => $row['jawaban_benar_esai'] ?? $row['correct_answer_essay'] ?? null,
            'score_weight' => $row['bobot_nilai'] ?? $row['score_weight'] ?? 1,
        ]);
    }
}
