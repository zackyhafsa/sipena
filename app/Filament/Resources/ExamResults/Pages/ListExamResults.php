<?php

namespace App\Filament\Resources\ExamResults\Pages;

use App\Filament\Resources\ExamResults\ExamResultResource;
use App\Helpers\SchoolContext;
use App\Models\School;
use App\Exports\ExamResultsExport;
use Filament\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Resources\Pages\ListRecords;

class ListExamResults extends ListRecords
{
    protected static string $resource = ExamResultResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->label('Input Nilai Manual')
                ->icon('heroicon-o-plus'),
            Action::make('export_pdf')
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

                    $schoolId = SchoolContext::getActiveSchoolId();
                    $school = $schoolId ? School::find($schoolId) : null;

                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.exam-results-pdf', [
                        'records' => $records,
                        'date' => now()->format('d M Y H:i'),
                        'school' => $school,
                    ])->setPaper('a4', 'landscape');

                    return response()->streamDownload(fn () => print($pdf->output()), 'rekap-hasil-ujian.pdf');
                }),
            Action::make('export_excel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function ($livewire) {
                    $records = $livewire->getFilteredTableQuery()->with(['user.classroom', 'exam'])->get();

                    if ($records->isEmpty()) {
                        \Filament\Notifications\Notification::make()
                            ->title('Tidak ada data untuk diexport.')
                            ->warning()
                            ->send();
                        return;
                    }

                    return Excel::download(new ExamResultsExport($records), 'rekap-hasil-ujian.xlsx');
                }),
        ];
    }
}
