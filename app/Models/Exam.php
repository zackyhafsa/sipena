<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'title',
        'description',
        'duration',
        'randomize_questions',
        'randomize_answers',
        'pg_weight',
        'essay_weight',
        'max_violations',
        'show_result_on_completion',
    ];

    protected $casts = [
        'randomize_questions' => 'boolean',
        'randomize_answers' => 'boolean',
        'show_result_on_completion' => 'boolean',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    public function schools(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(School::class)->withPivot('is_active', 'token')->withTimestamps();
    }
}
