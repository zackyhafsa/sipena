<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class ExamScoresChart extends ChartWidget
{
    protected ?string $heading = 'Grafik Peserta Ujian (7 Hari Terakhir)';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('d M');

            $query = \App\Models\ExamResult::whereDate('created_at', $date->toDateString());

            if (auth()->check() && auth()->user()->role === 'admin') {
                $classroomId = auth()->user()->classroom_id;
                $query->whereHas('user', function ($q) use ($classroomId) {
                    $q->where('classroom_id', $classroomId);
                });
            }

            $data[] = $query->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Siswa Mengerjakan Ujian',
                    'data' => $data,
                    'fill' => 'start',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
