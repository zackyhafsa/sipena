<?php

namespace App\Filament\Resources\ExamResults\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
// Menggunakan namespace Action yang benar sesuai koreksimu
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ExamResultsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user_id')
                    ->label('ID Siswa')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('exam.title')
                    ->label('Nama Ujian')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('score')
                    ->label('Nilai Akhir')
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        $state >= 80 => 'success', // Hijau jika >= 80
                        $state >= 60 => 'warning', // Kuning jika >= 60
                        default => 'danger',       // Merah jika di bawah 60
                    })
                    ->sortable(),

                TextColumn::make('cheat_warning_count')
                    ->label('Pelanggaran (Pindah Tab)')
                    ->badge()
                    ->color(fn (string $state): string => $state > 0 ? 'danger' : 'success')
                    ->sortable(),

                TextColumn::make('finished_at')
                    ->label('Waktu Selesai')
                    ->dateTime('d M Y - H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('exam_id')
                    ->relationship('exam', 'title')
                    ->label('Filter Ujian'),
            ])
            ->actions([
                // Kita hanya pasang Delete, tanpa EditAction karena nilai tidak boleh diubah
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
