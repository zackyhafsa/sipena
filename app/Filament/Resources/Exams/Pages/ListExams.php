<?php

namespace App\Filament\Resources\Exams\Pages;

use App\Filament\Resources\Exams\ExamResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListExams extends ListRecords
{
    protected static string $resource = ExamResource::class;

    protected function getHeaderActions(): array
    {
        if (auth()->user()?->role !== 'superadmin') {
            return [];
        }

        return [
            CreateAction::make(),
        ];
    }
}
