<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StudentStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $totalSiswa = \App\Models\User::where('role', 'student');
        $ujianSelesai = \App\Models\ExamResult::whereNotNull('score');
        $avgScore = \App\Models\ExamResult::whereNotNull('score');
        $totalPelanggaran = \App\Models\ExamResult::where('violations_count', '>', 0);

        if (auth()->check() && auth()->user()->role === 'admin') {
            $classroomId = auth()->user()->classroom_id;

            $totalSiswa->where('classroom_id', $classroomId);
            $ujianSelesai->whereHas('user', fn ($q) => $q->where('classroom_id', $classroomId));
            $avgScore->whereHas('user', fn ($q) => $q->where('classroom_id', $classroomId));
            $totalPelanggaran->whereHas('user', fn ($q) => $q->where('classroom_id', $classroomId));
        }

        return [
            Stat::make('Total Siswa', $totalSiswa->count())
                ->icon('heroicon-o-users')
                ->color('primary'),
            Stat::make('Ujian Selesai', $ujianSelesai->count())
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Rata-rata Nilai', number_format($avgScore->average('score') ?? 0, 1))
                ->icon('heroicon-o-chart-bar')
                ->color('info'),
            Stat::make('Siswa Melanggar', $totalPelanggaran->count())
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
