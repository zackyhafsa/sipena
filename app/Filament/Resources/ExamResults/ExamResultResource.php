<?php

namespace App\Filament\Resources\ExamResults;

use App\Filament\Resources\ExamResults\Pages\ListExamResults;
use App\Filament\Resources\ExamResults\Tables\ExamResultsTable;
use App\Models\ExamResult;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExamResultResource extends Resource
{
    protected static ?string $model = ExamResult::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Hasil Ujian';

    protected static ?string $modelLabel = 'Hasil Ujian';

    protected static ?string $pluralModelLabel = 'Daftar Hasil Ujian';

    public static function canCreate(): bool
    {
        return false;
    }
    
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->check() && auth()->user()->role === 'admin') {
            // Guru admin hanya melihat hasil ujian siswa dari kelasnya
            $query->whereHas('user', function (Builder $q) {
                $q->where('classroom_id', auth()->user()->classroom_id);
            });
        }

        return $query;
    }

    public static function table(Table $table): Table
    {
        return ExamResultsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExamResults::route('/'),
        ];
    }
}
