<?php

namespace App\Filament\Resources\Exams\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ExamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Detail Ujian')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Ujian')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('duration')
                            ->label('Durasi (Menit)')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Contoh: 90 untuk 1.5 jam'),
                        Textarea::make('description')
                            ->label('Deskripsi/Instruksi Ujian')
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->helperText('Nyalakan jika ujian sudah bisa diakses siswa')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }
}
