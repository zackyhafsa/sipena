<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\ExamResult;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class StudentDashboard extends Component
{
    public $selectedExamId = null;
    public $selectedExam = null;
    public $showTokenModal = false;
    public $inputToken = '';

    public function openTokenModal($examId)
    {
        $exam = Exam::findOrFail($examId);
        $this->selectedExamId = $examId;
        $this->selectedExam = $exam;
        $this->inputToken = '';
        $this->resetErrorBag('inputToken');

        // Jika ujian tidak memiliki token, langsung redirect
        if (empty($exam->token)) {
            session()->put("exam_token_verified_{$examId}", true);
            return redirect()->to(route('student.exam', $examId));
        }

        $this->showTokenModal = true;
    }

    public function verifyToken()
    {
        $exam = Exam::findOrFail($this->selectedExamId);

        if ($this->inputToken === $exam->token) {
            $this->showTokenModal = false;
            session()->put("exam_token_verified_{$exam->id}", true);
            return redirect()->to(route('student.exam', $exam->id));
        }

        $this->addError('inputToken', 'Token yang Anda masukkan salah!');
    }

    public function closeTokenModal()
    {
        $this->showTokenModal = false;
        $this->selectedExamId = null;
        $this->selectedExam = null;
        $this->inputToken = '';
        $this->resetErrorBag('inputToken');
    }

    public function render()
    {
        $user = auth()->user();

        // Get all active exams
        $exams = Exam::where('is_active', true)->get();

        // Map exam results to easily check if student has completed an exam
        $completedExams = ExamResult::where('user_id', $user->id)
            ->whereIn('exam_id', $exams->pluck('id'))
            ->get()
            ->keyBy('exam_id');

        return view('livewire.student-dashboard', [
            'exams' => $exams,
            'completedExams' => $completedExams,
            'user' => $user
        ]);
    }
}
