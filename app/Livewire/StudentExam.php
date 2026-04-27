<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\ExamResult;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StudentExam extends Component
{
    public $exam_id;

    public $exam;

    public $questions;

    public $answers = [];

    public $shuffledOptions = [];

    public $violationCount = 0;

    public $maxViolations = 3;

    public $started_at;

    // Pagination properties
    public $currentQuestionIndex = 0;

    public function mount($exam_id)
    {
        $this->exam_id = $exam_id;
        $this->exam = Exam::with('questions')->findOrFail($exam_id);

        // Verifikasi token via session (diset dari dashboard)
        if (! empty($this->exam->token) && ! session("exam_token_verified_{$exam_id}")) {
            session()->flash('error', 'Anda harus memasukkan token terlebih dahulu.');
            $this->redirect(route('student.dashboard'));

            return;
        }

        $result = ExamResult::where('user_id', auth()->id())
            ->where('exam_id', $this->exam_id)
            ->first();

        if ($result) {
            session()->flash('error', 'Anda sudah menyelesaikan ujian ini.');
            $this->redirect(route('student.dashboard'));

            return;
        }

        if ($this->exam->randomize_questions) {
            $pgQuestions = $this->exam->questions->where('type', '!=', 'essay')->shuffle();
            $essayQuestions = $this->exam->questions->where('type', 'essay')->shuffle();
            $this->questions = $pgQuestions->merge($essayQuestions)->values();
        } else {
            $this->questions = $this->exam->questions;
        }

        foreach ($this->questions as $question) {
            $this->answers[$question->id] = null;

            if ($this->exam->randomize_answers && $question->type === 'multiple_choice') {
                $opts = ['option_a', 'option_b', 'option_c', 'option_d', 'option_e'];
                shuffle($opts);
                $this->shuffledOptions[$question->id] = $opts;
            }
        }

        $this->started_at = now()->toDateTimeString();
    }

    public function goToQuestion($index)
    {
        if (isset($this->questions[$index])) {
            $this->currentQuestionIndex = $index;
        }
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function registerViolation()
    {
        $this->violationCount++;

        if ($this->violationCount >= $this->maxViolations) {
            $this->dispatch('show-fatal-warning');
        } else {
            $this->dispatch('show-violation-warning', count: $this->violationCount, max: $this->maxViolations);
        }
    }

    public function forceSubmit()
    {
        $existingResult = ExamResult::where('user_id', auth()->id())
            ->where('exam_id', $this->exam_id)
            ->first();

        if (! $existingResult) {
            $this->saveResult();
        }

        session()->flash('error', 'Ujian Anda dihentikan otomatis karena terdeteksi meninggalkan halaman pengerjaan lebih dari batas yang diizinkan (curang).');

        return redirect()->to(route('student.dashboard'));
    }

    public function submit()
    {

        $existingResult = ExamResult::where('user_id', auth()->id())
            ->where('exam_id', $this->exam_id)
            ->first();

        if (! $existingResult) {
            $this->saveResult();
        }

        session()->flash('success', 'Ujian berhasil diselesaikan! Hasil anda telah disimpan.');

        return redirect()->to(route('student.dashboard'));
    }

    private function saveResult()
    {
        $totalWeightPG = 0;
        $earnedScorePG = 0;
        $hasEssay = false;

        foreach ($this->questions as $question) {
            $weight = $question->score_weight ?? 1;
            $userAnswer = $this->answers[$question->id] ?? null;

            if ($question->type === 'essay') {
                $hasEssay = true;
            } else {
                $totalWeightPG += $weight;
                if ($userAnswer === $question->correct_answer) {
                    $earnedScorePG += $weight;
                }
            }
        }

        $finalScorePG = $totalWeightPG > 0 ? round(($earnedScorePG / $totalWeightPG) * 100, 2) : 0;

        // Simpan log lengkap jawaban (termasuk esai) dan nilai PG sementara
        $answersLog = [
            'answers' => $this->answers,
            'pg_score' => $finalScorePG,
        ];

        ExamResult::create([
            'user_id' => auth()->id(),
            'exam_id' => $this->exam_id,
            'answers_log' => $answersLog,
            'score' => $hasEssay ? null : $finalScorePG,
            'cheat_warning_count' => $this->violationCount,
            'started_at' => $this->started_at,
            'finished_at' => now(),
            'is_scored_manually' => $hasEssay,
        ]);
    }

    public function render()
    {
        return view('livewire.student-exam');
    }
}
