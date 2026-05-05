<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('template')
                ->label('Download Template')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->visible(fn () => auth()->user()->role === 'superadmin')
                ->action(function () {
                    return Excel::download(new \App\Exports\UserTemplateExport, 'template_pengguna.xlsx');
                }),

            Action::make('import')
                ->label('Import Pengguna')
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
                        Excel::import(new \App\Imports\UsersImport, $filePath);
                        Notification::make()
                            ->title('Berhasil')
                            ->body('Data pengguna berhasil diimport.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal')
                            ->body('Terjadi kesalahan saat mengimport data pengguna: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua'),
            'superadmin' => Tab::make('Super Admin')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('role', 'superadmin')),
            'admin' => Tab::make('Guru')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('role', 'admin')),
            'student' => Tab::make('Siswa')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('role', 'student')),
        ];
    }
}
