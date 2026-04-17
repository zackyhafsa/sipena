<?php

use App\Livewire\StudentDashboard;
use App\Livewire\StudentExam;
use App\Livewire\StudentLogin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', StudentLogin::class)->name('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', StudentDashboard::class)->name('student.dashboard');
    Route::get('/ujian/{exam_id}', StudentExam::class)->name('student.exam');

    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});
