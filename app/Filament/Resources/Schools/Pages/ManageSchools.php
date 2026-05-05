<?php

namespace App\Filament\Resources\Schools\Pages;

use App\Filament\Resources\Schools\SchoolResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;

class ManageSchools extends ManageRecords
{
    protected static string $resource = SchoolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('template')
                ->label('Download Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->visible(fn () => auth()->user()->role === 'superadmin')
                ->action(function () {
                    return Excel::download(new \App\Exports\SchoolTemplateExport, 'template_sekolah.xlsx');
                }),

            Action::make('import')
                ->label('Import Sekolah')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning')
                ->visible(fn () => auth()->user()->role === 'superadmin')
                ->form([
                    FileUpload::make('file')
                        ->label('Pilih File Excel')
                        ->disk('local')
                        ->directory('imports')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                            'text/csv'
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $filePath = Storage::disk('local')->path($data['file']);
                    
                    try {
                        Excel::import(new \App\Imports\SchoolsImport, $filePath);
                        Notification::make()
                            ->title('Berhasil')
                            ->body('Data sekolah berhasil diimport.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal')
                            ->body('Terjadi kesalahan saat mengimport data sekolah: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            CreateAction::make()
                ->visible(fn () => auth()->user()->role === 'superadmin'),
        ];
    }
}
