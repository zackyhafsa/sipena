<?php

namespace App\Filament\Resources\ExamResults\Pages;

use App\Filament\Resources\ExamResults\ExamResultResource;
use Filament\Resources\Pages\ListRecords;

class ListExamResults extends ListRecords
{
    protected static string $resource = ExamResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
            \Filament\Actions\Action::make('export_pdf')
                ->label('Cetak Rekap (PDF)')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->with(['user.classroom', 'exam'])->get();

                    if ($records->isEmpty()) {
                        \Filament\Notifications\Notification::make()
                            ->title('Tidak ada data untuk dicetak.')
                            ->warning()
                            ->send();
                        return;
                    }

                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.exam-results-pdf', [
                        'records' => $records,
                        'date' => now()->format('d M Y H:i'),
                    ])->setPaper('a4', 'landscape');

                    return response()->streamDownload(fn () => print($pdf->output()), 'rekap-hasil-ujian.pdf');
                }),
        ];
    }
}
