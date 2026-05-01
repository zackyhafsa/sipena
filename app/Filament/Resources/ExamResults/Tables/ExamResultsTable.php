<?php

namespace App\Filament\Resources\ExamResults\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class ExamResultsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nama Siswa')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('user.classroom.name')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('exam.title')
                    ->label('Nama Ujian')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('score_pg')
                    ->label('Nilai PG (100)')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('score_essay')
                    ->label('Nilai Esai (100)')
                    ->badge()
                    ->color('primary')
                    ->formatStateUsing(fn (?string $state, $record): string => $state !== null ? $state : ($record->is_scored_manually ? 'Menunggu Koreksi' : '0'))
                    ->sortable(),

                TextColumn::make('score')
                    ->label('Nilai Akhir')
                    ->badge()
                    ->formatStateUsing(fn (?string $state, $record): string => $state !== null ? $state : ($record->is_scored_manually ? 'Menunggu Koreksi' : '0'))
                    ->color(fn (?string $state, $record): string => match (true) {
                        $state === null => 'warning',
                        $state >= 80 => 'success',
                        $state >= 60 => 'warning',
                        default => 'danger',
                    })
                    ->sortable(),

                IconColumn::make('is_scored_manually')
                    ->label('Ada Esai')
                    ->boolean(),

                TextColumn::make('cheat_warning_count')
                    ->label('Pelanggaran')
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
                SelectFilter::make('kelas_id')
                    ->relationship('user.classroom', 'name')
                    ->label('Filter Kelas')
                    ->visible(fn () => auth()->user()->role === 'superadmin'),
                Filter::make('needs_grading')
                    ->label('Perlu Dikoreksi')
                    ->query(fn (Builder $query): Builder => $query->where('is_scored_manually', true)->whereNull('score')),
            ])
            ->actions([
                Action::make('cek_jawaban')
                    ->label(fn ($record) => $record->is_scored_manually && $record->score === null ? 'Koreksi Esai' : 'Cek Jawaban')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color(fn ($record) => $record->is_scored_manually && $record->score === null ? 'warning' : 'info')
                    ->form(function ($record) {
                        $schemas = [];

                        $pgScore = $record->score_pg ?? 0;
                        $answers = is_array($record->answers_log) ? ($record->answers_log['answers'] ?? $record->answers_log) : [];

                        $schemas[] = Placeholder::make('info')
                            ->label('Informasi Nilai Otomatis')
                            ->content(new HtmlString("
                                <div class='flex items-center gap-2'>
                                    <span class='text-lg font-bold text-gray-800 dark:text-gray-100'>Skor Pilihan Ganda (Skala 100):</span>
                                    <span class='px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-bold dark:bg-blue-900/40 dark:text-blue-200'>{$pgScore}</span>
                                </div>
                            "));

                        // Load questions
                        $questions = $record->exam->questions;

                        foreach ($questions as $index => $question) {
                            $num = $index + 1;
                            $userAnswer = $answers[$question->id] ?? '-';

                            if ($question->type === 'multiple_choice') {
                                $correctAnswer = $question->correct_answer ?? '-';
                                $isCorrect = $userAnswer === $correctAnswer;

                                $statusBadge = $isCorrect
                                    ? "<span class='px-2 py-1 rounded-md bg-green-100 text-green-700 text-xs font-bold dark:bg-green-900/40 dark:text-green-200'>Benar</span>"
                                    : "<span class='px-2 py-1 rounded-md bg-red-100 text-red-700 text-xs font-bold dark:bg-red-900/40 dark:text-red-200'>Salah</span>";

                                $imageHtml = '';
                                if ($question->image_path) {
                                    $imageUrl = asset('storage/' . $question->image_path);
                                    $imageHtml = "<div class='mb-3'><img src='{$imageUrl}' alt='Gambar Soal' style='max-width:100%;max-height:200px;border-radius:8px;border:1px solid #e5e7eb;'></div>";
                                }

                                $schemas[] = Placeholder::make("pg_{$question->id}")
                                    ->label(new HtmlString("<span class='text-gray-700 font-semibold dark:text-gray-200'>{$num}. Pilihan Ganda</span> {$statusBadge}"))
                                    ->content(new HtmlString("
                                        <div class='mt-2 p-4 bg-gray-50 rounded-xl border border-gray-200 dark:bg-gray-900/60 dark:border-gray-700'>
                                            <div class='mb-3 text-gray-800 font-medium dark:text-gray-100'>{$question->question_text}</div>
                                            {$imageHtml}
                                            <div class='grid grid-cols-2 gap-4'>
                                                <div>
                                                    <span class='text-xs text-gray-500 font-bold uppercase tracking-wider dark:text-gray-400'>Jawaban Siswa</span>
                                                    <div class='mt-1 text-lg font-bold ".($isCorrect ? 'text-green-600 dark:text-green-300' : 'text-red-600 dark:text-red-300')."'>{$userAnswer}</div>
                                                </div>
                                                <div>
                                                    <span class='text-xs text-gray-500 font-bold uppercase tracking-wider dark:text-gray-400'>Kunci Jawaban</span>
                                                    <div class='mt-1 text-lg font-bold text-gray-800 dark:text-gray-100'>{$correctAnswer}</div>
                                                </div>
                                            </div>
                                        </div>
                                    "));
                            } else {
                                $correctAnswerEssay = $question->correct_answer_essay ?: '<em>Tidak ada acuan khusus</em>';

                                $imageHtml = '';
                                if ($question->image_path) {
                                    $imageUrl = asset('storage/' . $question->image_path);
                                    $imageHtml = "<div class='mb-3'><img src='{$imageUrl}' alt='Gambar Soal' style='max-width:100%;max-height:200px;border-radius:8px;border:1px solid #e5e7eb;'></div>";
                                }

                                $schemas[] = Placeholder::make("essay_{$question->id}")
                                    ->label(new HtmlString("<span class='text-indigo-700 font-semibold dark:text-indigo-200'>{$num}. Esai</span>"))
                                    ->content(new HtmlString("
                                        <div class='mt-2 p-4 bg-indigo-50/50 rounded-xl border border-indigo-100 dark:bg-indigo-900/20 dark:border-indigo-800/60'>
                                            <div class='mb-3 text-gray-800 font-medium dark:text-gray-100'>{$question->question_text}</div>
                                            {$imageHtml}
                                            <div class='space-y-4'>
                                                <div>
                                                    <span class='text-xs text-gray-500 font-bold uppercase tracking-wider dark:text-gray-400'>Jawaban Siswa</span>
                                                    <div class='mt-1 p-3 bg-white rounded-lg border border-gray-200 text-gray-800 whitespace-pre-wrap dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100'>".($userAnswer === '-' ? '<em>Tidak dijawab</em>' : $userAnswer)."</div>
                                                </div>
                                                <div>
                                                    <span class='text-xs text-gray-500 font-bold uppercase tracking-wider dark:text-gray-400'>Acuan Jawaban (Guru)</span>
                                                    <div class='mt-1 p-3 bg-indigo-50 rounded-lg text-indigo-900 border border-indigo-100 whitespace-pre-wrap dark:bg-indigo-900/40 dark:text-indigo-100 dark:border-indigo-800/60'>{$correctAnswerEssay}</div>
                                                </div>
                                            </div>
                                        </div>
                                    "));
                            }
                        }

                        $schemas[] = TextInput::make('essay_score')
                            ->label('Nilai Esai (Skala 100)')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(100)
                            ->helperText('Masukkan nilai esai dalam skala 100. Nilai Akhir akan dihitung otomatis sesuai bobot (Nilai PG + Nilai Esai).');

                        $schemas[] = Textarea::make('teacher_notes')
                            ->label('Catatan Guru (Opsional)')
                            ->nullable();

                        return $schemas;
                    })
                    ->mountUsing(function (Action $action, $record) {
                        $action->fillForm([
                            'essay_score' => $record->score_essay ?? 0,
                            'teacher_notes' => $record->teacher_notes,
                        ]);
                    })
                    ->action(function (array $data, $record): void {
                        $pgScore = $record->score_pg ?? 0;
                        $essayScore = $data['essay_score'];
                        
                        $pgWeight = $record->exam->pg_weight ?? 70;
                        $essayWeight = $record->exam->essay_weight ?? 30;
                        
                        $weightedPG = ($pgScore * $pgWeight) / 100;
                        $weightedEssay = ($essayScore * $essayWeight) / 100;
                        
                        $finalScore = $weightedPG + $weightedEssay;

                        $record->update([
                            'score_essay' => $essayScore,
                            'score' => $finalScore,
                            'teacher_notes' => $data['teacher_notes'],
                        ]);
                    })
                    ->modalWidth('4xl')
                    ->modalHeading(fn ($record) => 'Lembar Jawaban: '.$record->user->name),
                \Filament\Actions\EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    \Filament\Actions\BulkAction::make('export_pdf')
                        ->label('Cetak Rekap (PDF)')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('danger')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            $records->load(['user.classroom', 'exam']);

                            $schoolId = \App\Helpers\SchoolContext::getActiveSchoolId();
                            $school = $schoolId ? \App\Models\School::find($schoolId) : null;

                            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.exam-results-pdf', [
                                'records' => $records,
                                'date' => now()->format('d M Y H:i'),
                                'school' => $school,
                            ])->setPaper('a4', 'landscape');

                            return response()->streamDownload(fn () => print($pdf->output()), 'rekap-hasil-ujian.pdf');
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
