<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\ExamResult;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class StudentExam extends Component
{
    public $exam_id;
    public $exam;
    public $questions;
    public $answers = [];
    public $violationCount = 0;
    public $maxViolations = 3;
    public $started_at;
    
    // Pagination properties
    public $currentQuestionIndex = 0;
    
    // Token properties
    public $isTokenVerified = false;
    public $inputToken = '';

    public function mount($exam_id)
    {
        $this->exam_id = $exam_id;
        $this->exam = Exam::with('questions')->findOrFail($exam_id);
        
        $result = ExamResult::where('user_id', auth()->id())
            ->where('exam_id', $this->exam_id)
            ->first();
            
        if ($result) {
            session()->flash('error', 'Anda sudah menyelesaikan ujian ini.');
            $this->redirect(route('student.dashboard'));
            return;
        }

        $this->questions = $this->exam->questions;

        foreach ($this->questions as $question) {
            $this->answers[$question->id] = null;
        }
        
        // Memeriksa jika ujian tidak memiliki token, langsung verifikasi
        if (empty($this->exam->token)) {
            $this->isTokenVerified = true;
            $this->started_at = now()->toDateTimeString();
        }
    }

    public function verifyToken()
    {
        if ($this->inputToken === $this->exam->token) {
            $this->isTokenVerified = true;
            $this->started_at = now()->toDateTimeString();
            $this->addError('inputToken', ''); // clear errors if any
        } else {
            $this->addError('inputToken', 'Token yang Anda masukkan salah!');
        }
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

        if (!$existingResult && $this->isTokenVerified) {
            $this->saveResult();
        }

        session()->flash('error', 'Ujian Anda dihentikan otomatis karena terdeteksi meninggalkan halaman pengerjaan lebih dari batas yang diizinkan (curang).');
        return redirect()->to(route('student.dashboard'));
    }

    public function submit()
    {
        // Require double check when submitting to ensure token was verified
        if (!$this->isTokenVerified) return;

        $existingResult = ExamResult::where('user_id', auth()->id())
            ->where('exam_id', $this->exam_id)
            ->first();

        if (!$existingResult) {
            $this->saveResult();
        }

        session()->flash('success', 'Ujian berhasil diselesaikan! Hasil anda telah disimpan.');
        return redirect()->to(route('student.dashboard'));
    }
    
    private function saveResult()
    {
        $totalWeight = 0;
        $earnedScore = 0;
        
        foreach($this->questions as $question) {
            $weight = $question->score_weight ?? 1;
            $totalWeight += $weight;
            
            $userAnswer = $this->answers[$question->id] ?? null;
            if ($userAnswer === $question->correct_answer) {
                $earnedScore += $weight;
            }
        }
        
        $finalScore = $totalWeight > 0 ? round(($earnedScore / $totalWeight) * 100, 2) : 0;
        ExamResult::create([
            'user_id' => auth()->id(),
            'exam_id' => $this->exam_id,
            'answers_log' => $this->answers,
            'score' => $finalScore,
            'cheat_warning_count' => $this->violationCount,
            'started_at' => $this->started_at,
            'finished_at' => now(),
            'is_scored_manually' => false,
        ]);
    }

    public function render()
    {
        return view('livewire.student-exam');
    }
}
