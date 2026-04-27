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
                    $headers = [
                        'Content-Type' => 'text/csv; charset=UTF-8',
                        'Content-Disposition' => 'attachment; filename="template-soal.csv"',
                    ];

                    $callback = function () {
                        $file = fopen('php://output', 'w');

                        // BOM for UTF-8 Excel compatibility
                        fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

                        // Header
                        fputcsv($file, [
                            'type',
                            'question_text',
                            'option_a',
                            'option_b',
                            'option_c',
                            'option_d',
                            'option_e',
                            'correct_answer',
                            'correct_answer_essay',
                            'score_weight',
                        ]);

                        // Contoh soal PG
                        fputcsv($file, [
                            'multiple_choice',
                            'Berapakah hasil dari 2 + 2?',
                            '3',
                            '4',
                            '5',
                            '6',
                            '',
                            'B',
                            '',
                            '1',
                        ]);

                        // Contoh soal PG 2
                        fputcsv($file, [
                            'multiple_choice',
                            'Siapakah nabi terakhir dalam Islam?',
                            'Nabi Isa AS',
                            'Nabi Muhammad SAW',
                            'Nabi Musa AS',
                            'Nabi Ibrahim AS',
                            'Nabi Yusuf AS',
                            'B',
                            '',
                            '1',
                        ]);

                        // Contoh soal essai
                        fputcsv($file, [
                            'essay',
                            'Jelaskan pengertian sholat menurut bahasa dan istilah!',
                            '',
                            '',
                            '',
                            '',
                            '',
                            '',
                            'Sholat menurut bahasa berarti doa, sedangkan menurut istilah adalah ibadah yang dimulai dari takbiratul ihram dan diakhiri dengan salam.',
                            '2',
                        ]);

                        fclose($file);
                    };

                    return response()->stream($callback, 200, $headers);
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
