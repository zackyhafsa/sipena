<?php

namespace App\Filament\Resources\Questions\Schemas;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Ujian')
                    ->schema([
                        Select::make('exam_id')
                            ->relationship('exam', 'title') // Otomatis mengambil data dari tabel exams
                            ->label('Pilih Ujian')
                            ->required()
                            ->searchable()
                            ->preload(),
                        TextInput::make('score_weight')
                            ->label('Bobot Nilai per Soal')
                            ->numeric()
                            ->default(1)
                            ->required(),
                        Select::make('type')
                            ->label('Jenis Soal')
                            ->options([
                                'multiple_choice' => 'Pilihan Ganda',
                                'essay' => 'Esai'
                            ])
                            ->default('multiple_choice')
                            ->reactive()
                            ->required(),
                    ])->columns(3),

                Section::make('Detail Pertanyaan & Jawaban')
                    ->schema([
                        Textarea::make('question_text')
                            ->label('Pertanyaan')
                            ->required()
                            ->columnSpanFull()
                            ->rows(4),

                        Grid::make(2)
                            ->schema([
                                TextInput::make('option_a')->label('Opsi A')
                                    ->required(fn ($get) => $get('type') === 'multiple_choice'),
                                TextInput::make('option_b')->label('Opsi B')
                                    ->required(fn ($get) => $get('type') === 'multiple_choice'),
                                TextInput::make('option_c')->label('Opsi C')
                                    ->required(fn ($get) => $get('type') === 'multiple_choice'),
                                TextInput::make('option_d')->label('Opsi D')
                                    ->required(fn ($get) => $get('type') === 'multiple_choice'),
                                TextInput::make('option_e')->label('Opsi E (Opsional)'),
                            ])
                            ->visible(fn ($get) => $get('type') === 'multiple_choice'),

                        Radio::make('correct_answer')
                            ->label('Kunci Jawaban Benar (Ganda)')
                            ->options([
                                'A' => 'A',
                                'B' => 'B',
                                'C' => 'C',
                                'D' => 'D',
                                'E' => 'E',
                            ])
                            ->inline()
                            ->required(fn ($get) => $get('type') === 'multiple_choice')
                            ->visible(fn ($get) => $get('type') === 'multiple_choice')
                            ->columnSpanFull(),
                            
                        Textarea::make('correct_answer_essay')
                            ->label('Acuan Jawaban Benar (Esai)')
                            ->helperText('Diperuntukkan untuk referensi guru dalam memberikan nilai ujian esai siswa')
                            ->required(fn ($get) => $get('type') === 'essay')
                            ->visible(fn ($get) => $get('type') === 'essay')
                            ->columnSpanFull()
                            ->rows(4),
                    ]),
            ]);
    }
}
