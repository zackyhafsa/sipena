<?php

namespace App\Filament\Resources\Questions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
// Menggunakan namespace sesuai dengan instruksi sebelumnya
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('exam.title')
                    ->label('Ujian')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('question_text')
                    ->label('Pertanyaan')
                    ->limit(50) // Membatasi teks agar tabel tidak terlalu lebar
                    ->searchable(),
                TextColumn::make('correct_answer')
                    ->label('Kunci')
                    ->badge()
                    ->color('success'),
                TextColumn::make('score_weight')
                    ->label('Bobot')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('exam_id')
                    ->relationship('exam', 'title')
                    ->label('Filter Berdasarkan Ujian'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
