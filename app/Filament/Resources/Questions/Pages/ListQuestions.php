<?php

namespace App\Filament\Resources\Questions\Pages;

use App\Filament\Resources\Questions\QuestionResource;
use App\Imports\QuestionsImport;
// Namespace yang benar
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListQuestions extends ListRecords
{
    protected static string $resource = QuestionResource::class;

    protected function getHeaderActions(): array
    {
        if (auth()->user()?->role !== 'superadmin') {
            return [];
        }

        return [
            Action::make('downloadTemplate')
                ->label('Download Template Soal')
                ->color('info')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    return Excel::download(new \App\Exports\QuestionTemplateExport, 'template-soal.xlsx');
                }),

            Action::make('importExcel')
                ->label('Import Soal (Excel)')
                ->color('success')
                ->icon('heroicon-o-document-arrow-up')
                ->form([
                    Select::make('exam_id')
                        ->relationship('exam', 'title')
                        ->label('Masukkan ke Ujian:')
                        ->required(),
                    FileUpload::make('file')
                        ->label('Upload File Excel')
                        ->disk('local')
                        ->directory('imports')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $filePath = storage_path('app/private/'.$data['file']);

                    Excel::import(new QuestionsImport($data['exam_id']), $filePath);

                    Notification::make()
                        ->title('Soal berhasil di-import!')
                        ->success()
                        ->send();
                }),

            CreateAction::make(),
        ];
    }
}
