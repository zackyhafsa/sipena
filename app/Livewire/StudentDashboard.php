<?php

namespace App\Livewire;

use App\Models\Exam;
use App\Models\ExamResult;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class StudentDashboard extends Component
{
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
