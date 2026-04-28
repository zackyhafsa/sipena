<?php

namespace App\Filament\Resources\ExamResults\Pages;

use App\Filament\Resources\ExamResults\ExamResultResource;
use Filament\Resources\Pages\CreateRecord;

class CreateExamResult extends CreateRecord
{
    protected static string $resource = ExamResultResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['started_at'] = now();
        $data['finished_at'] = now();
        $data['is_scored_manually'] = true;
        
        // Remove classroom_id because it's not in the database table
        unset($data['classroom_id']);

        return $data;
    }
}
