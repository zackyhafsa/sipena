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
                    ->label('Pelanggaran (Pindah Tab)')
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
                Action::make('grade')
                    ->label('Koreksi')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => $record->is_scored_manually)
                    ->form(function ($record) {
                        $schemas = [];

                        $pgScore = 0;
                        if (is_array($record->answers_log) && isset($record->answers_log['pg_score'])) {
                            $pgScore = $record->answers_log['pg_score'];
                        }

                        $schemas[] = Placeholder::make('info')
                            ->label('Informasi Pengerjaan')
                            ->content(new HtmlString("<strong>Nilai Pilihan Ganda: {$pgScore}</strong>"));

                        if (is_array($record->answers_log) && isset($record->answers_log['details'])) {
                            foreach ($record->answers_log['details'] as $index => $detail) {
                                if (($detail['type'] ?? '') === 'essay') {
                                    $questionText = strip_tags($detail['question'] ?? 'Pertanyaan');
                                    $correctAnswer = strip_tags($detail['correct_answer_essay'] ?? '-');
                                    $userAnswer = strip_tags($detail['user_answer'] ?? '-');

                                    $schemas[] = Placeholder::make("essay_{$index}")
                                        ->label("Soal: {$questionText}")
                                        ->content(new HtmlString("
                                            <div class='mt-2 space-y-2'>
                                                <div><span class='font-bold text-gray-700'>Kunci Jawaban:</span> <br> <span class='text-gray-600'>{$correctAnswer}</span></div>
                                                <div><span class='font-bold text-gray-700'>Jawaban Siswa:</span> <br> <span class='text-blue-600'>{$userAnswer}</span></div>
                                            </div>
                                        "));
                                }
                            }
                        }

                        $schemas[] = TextInput::make('manual_score')
                            ->label('Total Nilai Keseluruhan')
                            ->numeric()
                            ->required()
                            ->helperText('Masukkan total nilai akhir yang akan disimpan (Gabungan PG dan Esai).');

                        $schemas[] = Textarea::make('teacher_notes')
                            ->label('Catatan Guru')
                            ->nullable();

                        return $schemas;
                    })
                    ->mountUsing(function (Action $action, $record) {
                        $action->fillForm([
                            'manual_score' => $record->score ?? 0,
                            'teacher_notes' => $record->teacher_notes,
                        ]);
                    })
                    ->action(function (array $data, $record): void {
                        $record->update([
                            'score' => $data['manual_score'],
                            'teacher_notes' => $data['teacher_notes'],
                        ]);
                    }),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
