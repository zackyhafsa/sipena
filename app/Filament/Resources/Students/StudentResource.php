<?php

namespace App\Filament\Resources\Students;

use App\Exports\StudentTemplateExport;
use App\Filament\Resources\Students\Pages\ManageStudents;
use App\Helpers\SchoolContext;
use App\Imports\StudentsImport;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class StudentResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = 'students';

    protected static ?string $modelLabel = 'Data Siswa';

    protected static ?string $pluralModelLabel = 'Data Siswa';

    protected static string|\UnitEnum|null $navigationGroup = 'Manajemen Sekolah';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    // Limit scope to only 'student' role
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->where('role', 'student');
        $schoolId = SchoolContext::getActiveSchoolId();

        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nis')
                    ->label('NIS')
                    ->unique(ignoreRecord: true)
                    ->numeric()
                    ->required()
                    ->maxLength(50),
                TextInput::make('nisn')
                    ->label('NISN')
                    ->unique(ignoreRecord: true)
                    ->numeric()
                    ->required()
                    ->maxLength(50),
                TextInput::make('name')
                    ->label('Nama Siswa')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('Email / Username')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('password')
                    ->password()
                    ->label('Password (Abaikan jika tidak ingin diubah)')
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                Select::make('classroom_id')
                    ->label('Kelas')
                    ->relationship('classroom', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('school_id')
                    ->label('Sekolah')
                    ->relationship('school', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn () => auth()->user()->role === 'superadmin'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                TextColumn::make('nis')->label('NIS')->searchable()->toggleable(),
                TextColumn::make('nisn')->label('NISN')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('classroom.name')
                    ->label('Kelas')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('school.name')
                    ->label('Sekolah')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->dateTime('d M Y')->label('Didaftarkan Pada')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->headerActions([
                Action::make('download_template')
                    ->label('Download Template')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function () {
                        return Excel::download(new StudentTemplateExport, 'template-import-siswa.xlsx');
                    }),
                Action::make('import_excel')
                    ->label('Import Excel')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('warning')
                    ->form([
                        FileUpload::make('file')
                            ->label('File Excel/CSV')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $file = Storage::path($data['file']);
                        $school_id = auth()->user()->school_id;
                        Excel::import(new StudentsImport($school_id), $file);
                        Notification::make()
                            ->title('Data siswa berhasil diimport.')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageStudents::route('/'),
        ];
    }
}
