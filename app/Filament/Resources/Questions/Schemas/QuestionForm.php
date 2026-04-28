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
                                'essay' => 'Esai',
                            ])
                            ->default('multiple_choice')
                            ->reactive()
                            ->required(),
                    ])->columns(3),

                Section::make('Detail Pertanyaan & Jawaban')
                    ->schema([
                        \Filament\Forms\Components\FileUpload::make('image_path')
                            ->label('Gambar/Foto Soal (Opsional)')
                            ->image()
                            ->disk('public')
                            ->directory('question-images')
                            ->visibility('public')
                            ->imagePreviewHeight('150')
                            ->maxSize(1024)
                            ->columnSpanFull(),

                        Textarea::make('question_text')
                            ->label('Pertanyaan')
                            ->required()
                            ->columnSpanFull()
                            ->rows(4),

                        Grid::make(1)
                            ->schema([
                                \Filament\Schemas\Components\Fieldset::make('Opsi A')
                                    ->schema([
                                        TextInput::make('option_a')
                                            ->label('Teks Opsi A')
                                            ->required(fn ($get) => $get('type') === 'multiple_choice' && empty($get('option_a_image'))),
                                        \Filament\Forms\Components\FileUpload::make('option_a_image')
                                            ->label('Gambar Opsi A (Opsional)')
                                            ->image()
                                            ->disk('public')
                                            ->directory('question-options'),
                                    ])->columns(2),

                                \Filament\Schemas\Components\Fieldset::make('Opsi B')
                                    ->schema([
                                        TextInput::make('option_b')
                                            ->label('Teks Opsi B')
                                            ->required(fn ($get) => $get('type') === 'multiple_choice' && empty($get('option_b_image'))),
                                        \Filament\Forms\Components\FileUpload::make('option_b_image')
                                            ->label('Gambar Opsi B (Opsional)')
                                            ->image()
                                            ->disk('public')
                                            ->directory('question-options'),
                                    ])->columns(2),

                                \Filament\Schemas\Components\Fieldset::make('Opsi C')
                                    ->schema([
                                        TextInput::make('option_c')
                                            ->label('Teks Opsi C')
                                            ->required(fn ($get) => $get('type') === 'multiple_choice' && empty($get('option_c_image'))),
                                        \Filament\Forms\Components\FileUpload::make('option_c_image')
                                            ->label('Gambar Opsi C (Opsional)')
                                            ->image()
                                            ->disk('public')
                                            ->directory('question-options'),
                                    ])->columns(2),

                                \Filament\Schemas\Components\Fieldset::make('Opsi D')
                                    ->schema([
                                        TextInput::make('option_d')
                                            ->label('Teks Opsi D')
                                            ->required(fn ($get) => $get('type') === 'multiple_choice' && empty($get('option_d_image'))),
                                        \Filament\Forms\Components\FileUpload::make('option_d_image')
                                            ->label('Gambar Opsi D (Opsional)')
                                            ->image()
                                            ->disk('public')
                                            ->directory('question-options'),
                                    ])->columns(2),

                                \Filament\Schemas\Components\Fieldset::make('Opsi E (Opsional)')
                                    ->schema([
                                        TextInput::make('option_e')
                                            ->label('Teks Opsi E'),
                                        \Filament\Forms\Components\FileUpload::make('option_e_image')
                                            ->label('Gambar Opsi E (Opsional)')
                                            ->image()
                                            ->disk('public')
                                            ->directory('question-options'),
                                    ])->columns(2),
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
                            ->helperText('Diperuntukkan untuk referensi guru dalam memberikan nilai ujian esai siswa (Opsional)')
                            ->visible(fn ($get) => $get('type') === 'essay')
                            ->columnSpanFull()
                            ->rows(4),
                    ]),
            ]);
    }
}
