<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class PassRateChart extends ChartWidget
{
    protected ?string $heading = 'Tingkat Kelulusan Ujian';

    protected function getData(): array
    {
        $queryPassed = \App\Models\ExamResult::whereNotNull('score')->where('score', '>=', 70);
        $queryFailed = \App\Models\ExamResult::whereNotNull('score')->where('score', '<', 70);

        if (auth()->check() && auth()->user()->role === 'admin') {
            $classroomId = auth()->user()->classroom_id;

            $queryPassed->whereHas('user', function ($q) use ($classroomId) {
                $q->where('classroom_id', $classroomId);
            });

            $queryFailed->whereHas('user', function ($q) use ($classroomId) {
                $q->where('classroom_id', $classroomId);
            });
        }

        $passedCount = $queryPassed->count();
        $failedCount = $queryFailed->count();

        return [
            'datasets' => [
                [
                    'label' => 'Siswa',
                    'data' => [$passedCount, $failedCount],
                    'backgroundColor' => ['#10b981', '#f43f5e'],
                ],
            ],
            'labels' => ['Lulus (>= 70)', 'Belum Lulus (< 70)'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
