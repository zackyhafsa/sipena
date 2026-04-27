<?php

namespace App\Filament\Resources\Exams\Schemas;

use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

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
                        TextInput::make('token')
                            ->label('Token Ujian')
                            ->helperText('Token yang harus dimasukkan siswa sebelum mengerjakan ujian. Kosongkan jika tidak diperlukan.')
                            ->maxLength(20)
                            ->suffixAction(
                                Action::make('generateToken')
                                    ->icon('heroicon-o-arrow-path')
                                    ->action(function ($set) {
                                        $set('token', Str::upper(Str::random(6)));
                                    })
                            ),
                        Toggle::make('randomize_questions')
                            ->label('Acak Urutan Soal')
                            ->helperText('Jika dinyalakan, setiap siswa akan mendapatkan urutan soal yang berbeda.')
                            ->default(false),
                        Toggle::make('randomize_answers')
                            ->label('Acak Pilihan Ganda')
                            ->helperText('Jika dinyalakan, opsi A-E pada soal pilihan ganda akan diacak posisinya.')
                            ->default(false),
                    ])
                    ->columns(2),
            ]);
    }
}

