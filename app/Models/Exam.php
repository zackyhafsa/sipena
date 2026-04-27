<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = ['title', 'description', 'duration', 'is_active', 'token', 'randomize_questions', 'randomize_answers'];

    protected $casts = [
        'is_active' => 'boolean',
        'randomize_questions' => 'boolean',
        'randomize_answers' => 'boolean',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }
}
