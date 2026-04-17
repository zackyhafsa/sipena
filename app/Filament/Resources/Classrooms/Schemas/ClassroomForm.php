<?php

namespace App\Filament\Resources\Classrooms\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClassroomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Kelas')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Kelas')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Select::make('level')
                            ->label('Tingkatan Kelas')
                            ->options([
                                '10' => 'Kelas 10',
                                '11' => 'Kelas 11',
                                '12' => 'Kelas 12',
                            ])
                            ->required(),
                        TextInput::make('rombel')
                            ->label('Rombongan Belajar (Rombel)')
                            ->placeholder('Contoh: MIPA 1, IPS 2, dll')
                            ->required()
                            ->maxLength(255),
                    ])->columns(1)
            ]);
    }
}
