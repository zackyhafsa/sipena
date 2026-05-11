<?php

namespace App\Console\Commands;

use App\Models\ExamResult;
use Illuminate\Console\Command;

class RecalculateExamScores extends Command
{
    protected $signature = 'exam:recalculate-scores';

    protected $description = 'Recalculate all exam scores: convert option_a format to A and fix PG scores';

    public function handle()
    {
        $optionMap = [
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'option_e' => 'E',
        ];

        $results = ExamResult::with('exam.questions')->get();
        $fixed = 0;
        $total = $results->count();

        $this->info("Memproses {$total} hasil ujian...");

        foreach ($results as $result) {
            $answersLog = $result->answers_log;
            $answers = $answersLog['answers'] ?? [];
            $questions = $result->exam->questions ?? collect();

            if ($questions->isEmpty()) {
                continue;
            }

            // Konversi jawaban dari option_a → A
            $normalizedAnswers = [];
            $needsConversion = false;

            foreach ($answers as $qid => $answer) {
                if (isset($optionMap[$answer])) {
                    $normalizedAnswers[$qid] = $optionMap[$answer];
                    $needsConversion = true;
                } else {
                    $normalizedAnswers[$qid] = $answer;
                }
            }

            // Hitung ulang skor PG
            $totalWeightPG = 0;
            $earnedScorePG = 0;
            $hasEssay = false;

            foreach ($questions as $question) {
                $weight = $question->score_weight ?? 1;
                $userAnswer = $normalizedAnswers[$question->id] ?? null;

                if ($question->type === 'essay') {
                    $hasEssay = true;
                } else {
                    $totalWeightPG += $weight;
                    if ($userAnswer === $question->correct_answer) {
                        $earnedScorePG += $weight;
                    }
                }
            }

            $newScorePG = $totalWeightPG > 0 ? round(($earnedScorePG / $totalWeightPG) * 100, 2) : 0;
            $pgWeight = $result->exam->pg_weight ?? 70;
            $essayWeight = $result->exam->essay_weight ?? 30;

            // Hitung nilai akhir
            $scoreEssay = $result->score_essay;
            if ($hasEssay && $scoreEssay === null) {
                // Esai belum dikoreksi, nilai akhir tetap null
                $finalScore = null;
            } else {
                $essayVal = $scoreEssay ?? 0;
                $finalScore = round(($newScorePG * $pgWeight / 100) + ($essayVal * $essayWeight / 100), 2);
            }

            // Update record
            $oldScore = $result->score_pg;
            $answersLog['answers'] = $normalizedAnswers;
            $answersLog['pg_score'] = $newScorePG;

            $result->update([
                'answers_log' => $answersLog,
                'score_pg' => $newScorePG,
                'score' => $finalScore,
            ]);

            if ($needsConversion || $oldScore != $newScorePG) {
                $fixed++;
                $userName = $result->user->name ?? ('ID:' . $result->user_id);
                $examTitle = $result->exam->title ?? $result->exam_id;
                $this->line("  ✓ {$userName} | Ujian: {$examTitle} | PG: {$oldScore} → {$newScorePG}");
            }
        }

        $this->newLine();
        $this->info("Selesai! {$fixed} dari {$total} hasil ujian diperbaiki.");
    }
}
