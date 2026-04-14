<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\ExamResult;
use Carbon\Carbon;
use Livewire\Component;

class StudentExam extends Component
{
    public $exam;

    public $questions;

    public $currentQuestionIndex = 0;

    // Menyimpan jawaban siswa: ['question_id' => 'A']
    public $answers = [];

    public $cheatWarnings = 0;

    public $isFinished = false;

    public $score = 0;

    public function mount($exam_id)
    {
        // Ambil data ujian yang sedang aktif beserta soalnya
        $this->exam = Exam::with('questions')->where('id', $exam_id)->where('is_active', true)->firstOrFail();
        $this->questions = $this->exam->questions;

        // Inisialisasi array jawaban kosong
        foreach ($this->questions as $question) {
            $this->answers[$question->id] = null;
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

    public function addCheatWarning()
    {
        $this->cheatWarnings++;

        // Jika pelanggaran sudah 3 kali, otomatis kumpulkan ujian
        if ($this->cheatWarnings >= 3) {
            $this->submitExam();
        }
    }

    public function submitExam()
    {
        $totalScore = 0;
        $maxScore = 0;

        // Hitung nilai
        foreach ($this->questions as $question) {
            $maxScore += $question->score_weight;

            if (isset($this->answers[$question->id]) && $this->answers[$question->id] === $question->correct_answer) {
                $totalScore += $question->score_weight;
            }
        }

        // Kalkulasi nilai ke skala 100
        $this->score = $maxScore > 0 ? round(($totalScore / $maxScore) * 100) : 0;

        // Simpan ke database
        ExamResult::create([
            'user_id' => 1, // DUMMY USER ID (Nanti bisa diganti)
            'exam_id' => $this->exam->id,
            'score' => $this->score,
            'answers_log' => $this->answers,
            'cheat_warning_count' => $this->cheatWarnings,
            'started_at' => Carbon::now()->subMinutes($this->exam->duration),
            'finished_at' => Carbon::now(),
        ]);

        $this->isFinished = true;
    }

    public function render()
    {
        // Pastikan kita menggunakan layout bawaan Livewire/Laravel
        return view('livewire.student-exam')->layout('layouts.app');
    }
}
