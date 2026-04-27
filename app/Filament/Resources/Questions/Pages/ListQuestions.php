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
                        ->disk('local') // Menyimpan sementara di folder storage/app
                        ->directory('imports')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    // Mengambil path file yang baru diupload
                    $filePath = storage_path('app/private/'.$data['file']);
                    // Jika path private error, ganti menjadi: storage_path('app/' . $data['file']);

                    // Menjalankan proses import
                    Excel::import(new QuestionsImport($data['exam_id']), $filePath);

                    // Menampilkan notifikasi sukses
                    Notification::make()
                        ->title('Soal berhasil di-import!')
                        ->success()
                        ->send();
                }),

            CreateAction::make(), // Tombol "New Soal" bawaan
        ];
    }
}
