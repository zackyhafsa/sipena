<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StudentStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $cacheKey = 'dashboard_stats_' . (auth()->user()->classroom_id ?? 'all');
        
        $data = cache()->remember($cacheKey, 60, function() {
            $totalSiswa = \App\Models\User::where('role', 'student');
            $ujianSelesai = \App\Models\ExamResult::whereNotNull('score');
            $avgScore = \App\Models\ExamResult::whereNotNull('score');
            $totalPelanggaran = \App\Models\ExamResult::where('cheat_warning_count', '>', 0);

            if (auth()->check() && auth()->user()->role === 'admin') {
                $classroomId = auth()->user()->classroom_id;

                $totalSiswa->where('classroom_id', $classroomId);
                $ujianSelesai->whereHas('user', fn ($q) => $q->where('classroom_id', $classroomId));
                $avgScore->whereHas('user', fn ($q) => $q->where('classroom_id', $classroomId));
                $totalPelanggaran->whereHas('user', fn ($q) => $q->where('classroom_id', $classroomId));
            }

            return [
                'totalSiswa' => $totalSiswa->count(),
                'ujianSelesai' => $ujianSelesai->count(),
                'avgScore' => number_format($avgScore->average('score') ?? 0, 1),
                'totalPelanggaran' => $totalPelanggaran->count(),
            ];
        });

        return [
            Stat::make('Total Siswa', $data['totalSiswa'])
                ->icon('heroicon-o-users')
                ->color('primary'),
            Stat::make('Ujian Selesai', $data['ujianSelesai'])
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Rata-rata Nilai', $data['avgScore'])
                ->icon('heroicon-o-chart-bar')
                ->color('info'),
            Stat::make('Siswa Melanggar', $data['totalPelanggaran'])
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
