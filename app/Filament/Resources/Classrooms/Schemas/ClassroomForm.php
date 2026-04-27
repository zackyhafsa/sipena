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
                        Select::make('school_id')
                            ->relationship('school', 'name')
                            ->label('Asal Sekolah')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('name')
                            ->label('Nama Kelas')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Select::make('level')
                            ->label('Tingkatan Kelas')
                            ->options([
                                '1' => 'Kelas 1',
                                '2' => 'Kelas 2',
                                '3' => 'Kelas 3',
                                '4' => 'Kelas 4',
                                '5' => 'Kelas 5',
                                '6' => 'Kelas 6',
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
