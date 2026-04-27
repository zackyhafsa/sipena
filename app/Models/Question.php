<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = [
        'exam_id', 'question_text', 'image_path', 'type', 'option_a', 'option_b',
        'option_c', 'option_d', 'option_e', 'correct_answer', 'correct_answer_essay', 'score_weight',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
}
