<?php

namespace App\Filament\Resources\ExamResults\Schemas;

use App\Models\Exam;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Unique;

class ExamResultForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Siswa & Ujian')
                    ->schema([
                        Select::make('exam_id')
                            ->label('Pilih Ujian')
                            ->relationship('exam', 'title', function (Builder $query) {
                                $schoolId = \App\Helpers\SchoolContext::getActiveSchoolId();
                                if ($schoolId) {
                                    $query->whereHas('schools', function ($q) use ($schoolId) {
                                        $q->where('school_id', $schoolId);
                                    });
                                }
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live(),

                        Select::make('classroom_id')
                            ->label('Filter Kelas')
                            ->options(function () {
                                $schoolId = \App\Helpers\SchoolContext::getActiveSchoolId();
                                $query = \App\Models\Classroom::query();
                                if ($schoolId) {
                                    $query->where('school_id', $schoolId);
                                }
                                return $query->pluck('name', 'id');
                            })
                            ->searchable()
                            ->dehydrated(false) // Tidak disimpan ke tabel exam_results
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('user_id', null)),

                        Select::make('user_id')
                            ->label('Pilih Siswa')
                            ->options(function (Get $get) {
                                $schoolId = \App\Helpers\SchoolContext::getActiveSchoolId();
                                $query = User::where('role', 'student');
                                
                                if ($schoolId) {
                                    $query->where('school_id', $schoolId);
                                }

                                if ($classroomId = $get('classroom_id')) {
                                    $query->where('classroom_id', $classroomId);
                                }

                                return $query->pluck('name', 'id');
                            })
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->unique(
                                table: 'exam_results',
                                column: 'user_id',
                                modifyRuleUsing: function (Unique $rule, Get $get) {
                                    return $rule->where('exam_id', $get('exam_id'));
                                },
                                ignoreRecord: true
                            )
                            ->validationMessages([
                                'unique' => 'Siswa ini sudah memiliki nilai untuk ujian yang dipilih.',
                            ]),
                    ])->columns(3),

                Section::make('Input Nilai')
                    ->schema([
                        TextInput::make('score_pg')
                            ->label('Nilai Pilihan Ganda')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->minValue(0)
                            ->maxValue(100)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateTotalScore($get, $set)),

                        TextInput::make('score_essay')
                            ->label('Nilai Esai')
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->minValue(0)
                            ->maxValue(100)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::calculateTotalScore($get, $set)),

                        TextInput::make('score')
                            ->label('Total Nilai Akhir')
                            ->numeric()
                            ->required()
                            ->helperText('Dihitung otomatis jika bobot tersedia, namun bisa Anda sesuaikan manual.')
                            ->minValue(0)
                            ->maxValue(100),
                    ])->columns(3),
            ]);
    }

    protected static function calculateTotalScore(Get $get, Set $set)
    {
        $examId = $get('exam_id');
        if (! $examId) {
            return;
        }

        $exam = Exam::find($examId);
        if (! $exam) {
            return;
        }

        $scorePg = (float) ($get('score_pg') ?? 0);
        $scoreEssay = (float) ($get('score_essay') ?? 0);

        $pgWeight = $exam->pg_weight ?? 70;
        $essayWeight = $exam->essay_weight ?? 30;

        $totalScore = round((($scorePg * $pgWeight) / 100) + (($scoreEssay * $essayWeight) / 100), 2);

        $set('score', $totalScore);
    }
}
