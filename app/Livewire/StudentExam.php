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

    protected $exam;

    protected $questions;

    public $questionIds = [];

    public $answers = [];

    public $shuffledOptions = [];

    public $violationCount = 0;

    public $maxViolations = 0;

    public $started_at;

    // Pagination properties
    public $currentQuestionIndex = 0;

    public function mount($exam_id)
    {
        $this->exam_id = $exam_id;
        $this->loadExamAndQuestions();

        $this->maxViolations = $this->exam->max_violations;

        // Verifikasi token via session
        $schoolId = auth()->user()->school_id;
        
        $pivot = $this->exam->schools()->where('school_id', $schoolId)->select('exam_school.token')->first()?->token;
        $token = $pivot;

        if (! empty($token) && ! session("exam_token_verified_{$exam_id}")) {
            session()->flash('error', 'Anda harus memasukkan token terlebih dahulu.');
            $this->redirect(route('student.dashboard'));
            return;
        }

        $alreadyFinished = ExamResult::where('user_id', auth()->id())
            ->where('exam_id', $this->exam_id)
            ->exists();

        if ($alreadyFinished) {
            session()->flash('error', 'Anda sudah menyelesaikan ujian ini.');
            $this->redirect(route('student.dashboard'));
            return;
        }

        $progressKey = "exam_progress_{$this->exam_id}_" . auth()->id();
        if (session()->has($progressKey)) {
            $progress = session()->get($progressKey);
            $this->answers = $progress['answers'] ?? [];
            $this->violationCount = $progress['violationCount'] ?? 0;
            $this->shuffledOptions = $progress['shuffledOptions'] ?? [];
            $this->started_at = $progress['started_at'] ?? now()->toDateTimeString();
            $this->currentQuestionIndex = $progress['currentQuestionIndex'] ?? 0;
            $this->questionIds = $progress['questions'] ?? [];
            
            // Re-order questions based on session IDs
            $questionsMap = $this->exam->questions->keyBy('id');
            $this->questions = collect($this->questionIds)->map(fn($id) => $questionsMap->get($id))->filter()->values();
            
            // Tambahkan soal baru jika ada yang baru dimasukkan guru
            $newQuestions = $this->exam->questions->filter(fn($q) => !in_array($q->id, $this->questionIds))->values();
            if ($newQuestions->isNotEmpty()) {
                if ($this->exam->randomize_questions) {
                    $newPg = $newQuestions->where('type', '!=', 'essay')->shuffle();
                    $newEssay = $newQuestions->where('type', 'essay')->shuffle();
                } else {
                    $newPg = $newQuestions->where('type', '!=', 'essay');
                    $newEssay = $newQuestions->where('type', 'essay');
                }
                
                $oldPg = $this->questions->where('type', '!=', 'essay');
                $oldEssay = $this->questions->where('type', 'essay');
                
                $this->questions = $oldPg->merge($newPg)->merge($oldEssay)->merge($newEssay)->values();
                $this->questionIds = $this->questions->pluck('id')->toArray();
                
                foreach ($newQuestions as $question) {
                    if (!array_key_exists($question->id, $this->answers)) {
                        $this->answers[$question->id] = null;
                    }
                    if ($this->exam->randomize_answers && $question->type === 'multiple_choice' && !isset($this->shuffledOptions[$question->id])) {
                        $opts = ['option_a', 'option_b', 'option_c', 'option_d', 'option_e'];
                        shuffle($opts);
                        $this->shuffledOptions[$question->id] = $opts;
                    }
                }
                
                $this->saveProgressToSession();
            }
        } else {
            if ($this->exam->randomize_questions) {
                $pgQuestions = $this->exam->questions->where('type', '!=', 'essay')->shuffle();
                $essayQuestions = $this->exam->questions->where('type', 'essay')->shuffle();
                $this->questions = $pgQuestions->merge($essayQuestions)->values();
            } else {
                $this->questions = $this->exam->questions;
            }

            $this->questionIds = $this->questions->pluck('id')->toArray();

            foreach ($this->questions as $question) {
                $this->answers[$question->id] = null;

                if ($this->exam->randomize_answers && $question->type === 'multiple_choice') {
                    $opts = ['option_a', 'option_b', 'option_c', 'option_d', 'option_e'];
                    shuffle($opts);
                    $this->shuffledOptions[$question->id] = $opts;
                }
            }

            $this->started_at = now()->toDateTimeString();
            $this->saveProgressToSession();
        }
    }

    protected function loadExamAndQuestions()
    {
        // Ensure classes are loaded before unserialization from cache
        class_exists(\App\Models\Exam::class);
        class_exists(\App\Models\Question::class);

        $cacheKey = "exam_structure_{$this->exam_id}";
        $this->exam = cache()->remember($cacheKey, 600, function() {
            return Exam::with(['questions' => function($q) {
                $q->select('id', 'exam_id', 'question_text', 'image_path', 'option_a', 'option_b', 'option_c', 'option_d', 'option_e', 'option_a_image', 'option_b_image', 'option_c_image', 'option_d_image', 'option_e_image', 'correct_answer', 'score_weight', 'type');
            }])->findOrFail($this->exam_id);
        });

        // Fallback for incomplete objects (corrupted cache)
        if ($this->exam instanceof \__PHP_Incomplete_Class) {
            cache()->forget($cacheKey);
            $this->exam = Exam::with(['questions' => function($q) {
                $q->select('id', 'exam_id', 'question_text', 'image_path', 'option_a', 'option_b', 'option_c', 'option_d', 'option_e', 'option_a_image', 'option_b_image', 'option_c_image', 'option_d_image', 'option_e_image', 'correct_answer', 'score_weight', 'type');
            }])->findOrFail($this->exam_id);
        }

        // Ensure questions are loaded if we have questionIds (for hydration)
        if (!empty($this->questionIds)) {
            $questionsMap = $this->exam->questions->keyBy('id');
            $this->questions = collect($this->questionIds)->map(fn($id) => $questionsMap->get($id))->filter()->values();
        } else {
            $this->questions = $this->exam->questions;
        }
    }

    /**
     * Auto-save: hanya dipanggil saat jawaban berubah (bukan saat navigasi).
     * Livewire memanggil ini setiap kali public property berubah via wire:model.
     */
    public function updated($propertyName)
    {
        // Hanya simpan ke session jika jawaban atau violationCount berubah
        if (str_starts_with($propertyName, 'answers.') || $propertyName === 'violationCount') {
            $this->saveProgressToSession();
        }
    }

    /**
     * Periodic sync: dipanggil oleh JavaScript setInterval (setiap 30 detik)
     * untuk menyimpan currentQuestionIndex dan memastikan progres aman.
     */
    public function syncProgress()
    {
        $this->saveProgressToSession();
    }

    public function saveProgressToSession()
    {
        $progressKey = "exam_progress_{$this->exam_id}_" . auth()->id();
        session()->put($progressKey, [
            'answers' => $this->answers,
            'violationCount' => $this->violationCount,
            'shuffledOptions' => $this->shuffledOptions,
            'questions' => $this->questionIds,
            'started_at' => $this->started_at,
            'currentQuestionIndex' => $this->currentQuestionIndex,
        ]);
    }

    public function goToQuestion($index)
    {
        $this->currentQuestionIndex = $index;
        // Tidak perlu saveProgressToSession di sini — auto-save periodik yang menangani
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < count($this->questionIds) - 1) {
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
        $this->loadExamAndQuestions();
        if ($this->exam->max_violations == 0) return;

        $this->violationCount++;
        $this->saveProgressToSession();

        if ($this->violationCount >= $this->exam->max_violations) {
            $this->dispatch('show-fatal-warning');
        } else {
            $this->dispatch('show-violation-warning', count: $this->violationCount, max: $this->exam->max_violations);
        }
    }

    public function forceSubmit()
    {
        $this->submit();
    }

    public function submit()
    {
        $alreadyFinished = ExamResult::where('user_id', auth()->id())
            ->where('exam_id', $this->exam_id)
            ->exists();

        if ($alreadyFinished) {
            return redirect()->to(route('student.dashboard'));
        }

        $this->saveResult();
        return redirect()->to(route('student.dashboard'));
    }

    private function saveResult()
    {
        $this->loadExamAndQuestions();
        
        // Peta konversi: key internal → huruf di database
        $optionMap = [
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'option_e' => 'E',
        ];

        $totalWeightPG = 0;
        $earnedScorePG = 0;
        $hasEssay = false;
        $normalizedAnswers = [];

        foreach ($this->questions as $question) {
            $weight = $question->score_weight ?? 1;
            $userAnswer = $this->answers[$question->id] ?? null;

            // Konversi option_a → A, option_b → B, dst.
            $normalizedAnswer = $optionMap[$userAnswer] ?? $userAnswer;
            $normalizedAnswers[$question->id] = $normalizedAnswer;

            if ($question->type === 'essay') {
                $hasEssay = true;
            } else {
                $totalWeightPG += $weight;
                if ($normalizedAnswer === $question->correct_answer) {
                    $earnedScorePG += $weight;
                }
            }
        }

        $finalScorePG = $totalWeightPG > 0 ? round(($earnedScorePG / $totalWeightPG) * 100, 2) : 0;
        $pgWeight = $this->exam->pg_weight ?? 70;
        $weightedPGScore = ($finalScorePG * $pgWeight) / 100;

        ExamResult::create([
            'user_id' => auth()->id(),
            'exam_id' => $this->exam_id,
            'answers_log' => ['answers' => $normalizedAnswers, 'pg_score' => $finalScorePG],
            'score_pg' => $finalScorePG,
            'score_essay' => $hasEssay ? null : 0,
            'score' => $hasEssay ? null : $weightedPGScore,
            'cheat_warning_count' => $this->violationCount,
            'started_at' => $this->started_at,
            'finished_at' => now(),
            'is_scored_manually' => $hasEssay,
        ]);

        $this->clearProgressSession();
    }

    private function clearProgressSession()
    {
        $progressKey = "exam_progress_{$this->exam_id}_" . auth()->id();
        session()->forget($progressKey);
    }

    public function render()
    {
        // Hanya load exam data jika belum dimuat (saat hydration setelah request Livewire)
        if (!$this->exam) {
            $this->loadExamAndQuestions();
        }
        
        return view('livewire.student-exam', [
            'exam' => $this->exam,
            'questions' => $this->questions,
        ]);
    }
}
