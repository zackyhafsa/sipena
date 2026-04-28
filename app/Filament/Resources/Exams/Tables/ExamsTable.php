<?php

namespace App\Filament\Resources\Exams\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ExamsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Ujian')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('duration')
                    ->label('Durasi')
                    ->suffix(' Menit')
                    ->sortable(),
                TextColumn::make('active_status')
                    ->label('Status')
                    ->badge()
                    ->state(function ($record) {
                        $schoolId = \App\Helpers\SchoolContext::getActiveSchoolId();
                        if (!$schoolId) return '-';
                        $pivot = $record->schools()->where('school_id', $schoolId)->first()?->pivot;
                        return $pivot && $pivot->is_active ? 'Aktif' : 'Nonaktif';
                    })
                    ->color(fn ($state) => $state === 'Aktif' ? 'success' : ($state === 'Nonaktif' ? 'danger' : 'gray')),
                TextColumn::make('school_token')
                    ->label('Token')
                    ->copyable()
                    ->badge()
                    ->color('warning')
                    ->state(function ($record) {
                        $schoolId = \App\Helpers\SchoolContext::getActiveSchoolId();
                        if (!$schoolId) return '-';
                        return $record->schools()->where('school_id', $schoolId)->first()?->pivot->token ?? 'Tanpa Token';
                    }),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter bisa ditambahkan nanti
            ])
            ->actions([
                \Filament\Actions\Action::make('toggle_active')
                    ->label(function ($record) {
                        $schoolId = \App\Helpers\SchoolContext::getActiveSchoolId();
                        if (!$schoolId) return '-';
                        $pivot = $record->schools()->where('school_id', $schoolId)->first()?->pivot;
                        return ($pivot && $pivot->is_active) ? 'Nonaktifkan' : 'Aktifkan';
                    })
                    ->icon(function ($record) {
                        $schoolId = \App\Helpers\SchoolContext::getActiveSchoolId();
                        if (!$schoolId) return 'heroicon-o-check-circle';
                        $pivot = $record->schools()->where('school_id', $schoolId)->first()?->pivot;
                        return ($pivot && $pivot->is_active) ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle';
                    })
                    ->color(function ($record) {
                        $schoolId = \App\Helpers\SchoolContext::getActiveSchoolId();
                        if (!$schoolId) return 'gray';
                        $pivot = $record->schools()->where('school_id', $schoolId)->first()?->pivot;
                        return ($pivot && $pivot->is_active) ? 'danger' : 'success';
                    })
                    ->visible(fn () => \App\Helpers\SchoolContext::hasActiveSchool())
                    ->form(function ($record) {
                        $schoolId = \App\Helpers\SchoolContext::getActiveSchoolId();
                        $pivot = $record->schools()->where('school_id', $schoolId)->first()?->pivot;
                        if ($pivot && $pivot->is_active) {
                            return []; // No form when deactivating
                        }
                        return [
                            \Filament\Forms\Components\TextInput::make('token')
                                ->label('Token Ujian (Opsional)')
                                ->maxLength(255)
                        ];
                    })
                    ->action(function ($record, array $data) {
                        $schoolId = \App\Helpers\SchoolContext::getActiveSchoolId();
                        if (!$schoolId) return;
                        $pivot = $record->schools()->where('school_id', $schoolId)->first()?->pivot;
                        
                        if ($pivot && $pivot->is_active) {
                            $record->schools()->updateExistingPivot($schoolId, ['is_active' => false]);
                        } else {
                            $token = $data['token'] ?? null;
                            if ($record->schools()->where('school_id', $schoolId)->exists()) {
                                $record->schools()->updateExistingPivot($schoolId, ['is_active' => true, 'token' => $token]);
                            } else {
                                $record->schools()->attach($schoolId, ['is_active' => true, 'token' => $token]);
                            }
                        }
                    }),
                EditAction::make()->visible(fn () => auth()->user()->role === 'superadmin'),
                DeleteAction::make()->visible(fn () => auth()->user()->role === 'superadmin'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
