<?php

namespace App\Filament\Resources\Classrooms\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction as TableEditAction;
use Filament\Actions\DeleteAction as TableDeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ClassroomsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama Kelas')->searchable()->sortable(),
                TextColumn::make('level')
                    ->label('Tingkatan')
                    ->sortable(),
                TextColumn::make('rombel')
                    ->label('Rombel')
                    ->searchable(),
                TextColumn::make('users_count')->counts('users')->label('Jumlah Siswa/Guru'),
                TextColumn::make('created_at')->dateTime('d M Y')->label('Dibuat Pada'),
            ])
            ->filters([
                //
            ])
            ->actions([
                TableEditAction::make(),
                TableDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
