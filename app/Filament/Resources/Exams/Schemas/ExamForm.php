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
                Section::make('Pengaturan Penilaian & Keamanan')
                    ->schema([
                        TextInput::make('pg_weight')
                            ->label('Bobot Nilai PG (%)')
                            ->numeric()
                            ->default(70)
                            ->required()
                            ->minValue(0)
                            ->maxValue(100),
                        TextInput::make('essay_weight')
                            ->label('Bobot Nilai Essai (%)')
                            ->numeric()
                            ->default(30)
                            ->required()
                            ->minValue(0)
                            ->maxValue(100),
                        TextInput::make('max_violations')
                            ->label('Batas Toleransi Pelanggaran (Keluar Layar Penuh)')
                            ->numeric()
                            ->default(3)
                            ->required()
                            ->minValue(0)
                            ->helperText('Siswa akan otomatis di-submit jika keluar dari layar penuh melebihi batas ini. Isi 0 jika tidak ada batas.'),
                    ])
                    ->columns(3),
            ]);
    }
}

