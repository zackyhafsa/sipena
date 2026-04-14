<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResult extends Model
{
    protected $fillable = [
        'user_id', 'exam_id', 'score', 'answers_log',
        'cheat_warning_count', 'started_at', 'finished_at',
    ];

    // Cast agar answers_log bisa kita akses sebagai array di PHP
    protected $casts = [
        'answers_log' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
