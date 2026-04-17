<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageStudents extends ManageRecords
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['role'] = 'student';
                    if (auth()->user()->role === 'admin' && auth()->user()->school_id) {
                        $data['school_id'] = auth()->user()->school_id;
                    }
                    return $data;
                }),
        ];
    }
}
