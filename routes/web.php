<?php

use App\Livewire\StudentExam;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ujian/{exam_id}', StudentExam::class)->name('student.exam');
