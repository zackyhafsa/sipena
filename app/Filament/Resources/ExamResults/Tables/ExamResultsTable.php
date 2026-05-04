<?php

namespace App\Filament\Resources\ExamResults\Tables;

use App\Helpers\SchoolContext;
use App\Models\School;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
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

                        // ── Info skor PG ──────────────────────────────────────────────
                        $schemas[] = Placeholder::make('info')
                            ->label('Informasi Nilai Otomatis')
                            ->content(new HtmlString("
                                <div class='flex items-center gap-2'>
                                    <span class='text-lg font-bold text-gray-800 dark:text-gray-100'>
                                        Skor Pilihan Ganda (Skala 100):
                                    </span>
                                    <span class='px-3 py-1 rounded-full bg-blue-100 text-blue-700 font-bold
                                                dark:bg-blue-900 dark:text-blue-200'>
                                        {$pgScore}
                                    </span>
                                </div>
                            "));

                        $questions = $record->exam->questions;

                        foreach ($questions as $index => $question) {
                            $num = $index + 1;
                            $userAnswer = $answers[$question->id] ?? '-';

                            // ── Gambar soal (shared) ──────────────────────────────────
                            $imageHtml = '';
                            if ($question->image_path) {
                                $imageUrl = asset('storage/'.$question->image_path);
                                $imageHtml = "
                                    <div class='mb-3'>
                                        <img src='{$imageUrl}' alt='Gambar Soal'
                                             class='max-w-xs max-h-32 object-contain rounded-lg border border-gray-200 dark:border-gray-600'>
                                    </div>";
                            }

                            if ($question->type === 'multiple_choice') {
                                // ── Pilihan Ganda ─────────────────────────────────────
                                $correctAnswer = $question->correct_answer ?? '-';
                                $isCorrect = $userAnswer === $correctAnswer;

                                $statusBadge = $isCorrect
                                    ? "<span class='px-2 py-1 rounded-md bg-green-100 text-green-700 text-xs font-bold
                                                dark:bg-green-500 dark:text-gray-900'>Benar</span>"
                                    : "<span class='px-2 py-1 rounded-md bg-red-100 text-red-700 text-xs font-bold
                                                dark:bg-red-500 dark:text-gray-900'>Salah</span>";

                                $answerColor = $isCorrect
                                    ? 'text-green-600 dark:text-green-400'
                                    : 'text-red-600   dark:text-red-400';

                                $schemas[] = Placeholder::make("pg_{$question->id}")
                                    ->label(new HtmlString("
                                        <span class='text-gray-700 font-semibold dark:text-gray-100'>
                                            {$num}. Pilihan Ganda
                                        </span>
                                        {$statusBadge}
                                    "))
                                    ->content(new HtmlString("
                                        <div class='mt-2 p-4 rounded-xl bg-gray-50 dark:bg-gray-800'>

                                            <div class='mb-3 font-medium
                                                        text-gray-800 dark:text-gray-100'>
                                                {$question->question_text}
                                            </div>

                                            {$imageHtml}

                                            <div class='grid grid-cols-2 gap-4'>
                                                <div>
                                                    <span class='text-xs font-bold uppercase tracking-wider
                                                                 text-gray-500 dark:text-gray-100'>
                                                        Jawaban Siswa
                                                    </span>
                                                    <div class='mt-1 text-lg font-bold {$answerColor}'>
                                                        {$userAnswer}
                                                    </div>
                                                </div>
                                                <div>
                                                    <span class='text-xs font-bold uppercase tracking-wider
                                                                 text-gray-500 dark:text-gray-100'>
                                                        Kunci Jawaban
                                                    </span>
                                                    <div class='mt-1 text-lg font-bold
                                                                text-gray-800 dark:text-white'>
                                                        {$correctAnswer}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    "));

                            } else {
                                // ── Esai ──────────────────────────────────────────────
                                $correctAnswerEssay = $question->correct_answer_essay
                                    ?: '<em>Tidak ada acuan khusus</em>';

                                $jawabanSiswa = ($userAnswer === '-')
                                    ? '<em class="text-gray-400">Tidak dijawab</em>'
                                    : $userAnswer;

                                $schemas[] = Placeholder::make("essay_{$question->id}")
                                    ->label(new HtmlString("
                                        <span class='font-semibold text-indigo-600 dark:text-gray-100'>
                                            {$num}. Esai
                                        </span>
                                    "))
                                    ->content(new HtmlString("
                                        <div class='mt-2 p-4 rounded-xl bg-gray-50 dark:bg-gray-800'>

                                            <div class='mb-3 font-medium
                                                        text-gray-800 dark:text-gray-100'>
                                                {$question->question_text}
                                            </div>

                                            {$imageHtml}

                                            <div class='space-y-4'>
                                                <div>
                                                    <span class='text-xs font-bold uppercase tracking-wider
                                                                 text-gray-500 dark:text-gray-100'>
                                                        Jawaban Siswa
                                                    </span>
                                                    <div class='mt-1 p-3 rounded-lg whitespace-pre-wrap
                                                                bg-white text-gray-700
                                                                dark:bg-gray-900 dark:text-gray-200'>
                                                        {$jawabanSiswa}
                                                    </div>
                                                </div>
                                                <div>
                                                    <span class='text-xs font-bold uppercase tracking-wider
                                                                 text-gray-500 dark:text-gray-100'>
                                                        Acuan Jawaban (Guru)
                                                    </span>
                                                    <div class='mt-1 p-3 rounded-lg whitespace-pre-wrap
                                                                bg-indigo-50 text-indigo-700
                                                                dark:bg-indigo-950/40 dark:text-indigo-200'>
                                                        {$correctAnswerEssay}
                                                    </div>
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

                        $finalScore = ($pgScore * $pgWeight / 100) + ($essayScore * $essayWeight / 100);

                        $record->update([
                            'score_essay' => $essayScore,
                            'score' => $finalScore,
                            'teacher_notes' => $data['teacher_notes'],
                        ]);
                    })
                    ->modalWidth('4xl')
                    ->modalHeading(fn ($record) => 'Lembar Jawaban: '.$record->user->name),

                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('export_pdf')
                        ->label('Cetak Rekap (PDF)')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('danger')
                        ->action(function (Collection $records) {
                            $records->load(['user.classroom', 'exam']);

                            $schoolId = SchoolContext::getActiveSchoolId();
                            $school = $schoolId ? School::find($schoolId) : null;

                            $pdf = Pdf::loadView('exports.exam-results-pdf', [
                                'records' => $records,
                                'date' => now()->format('d M Y H:i'),
                                'school' => $school,
                            ])->setPaper('a4', 'landscape');

                            return response()->streamDownload(
                                fn () => print ($pdf->output()),
                                'rekap-hasil-ujian.pdf'
                            );
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
