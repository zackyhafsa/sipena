<?php

namespace App\Filament\Resources\Schools;

use App\Filament\Resources\Schools\Pages\ManageSchools;
use App\Models\School;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

class SchoolResource extends Resource
{
    protected static ?string $model = School::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Data Sekolah';

    protected static ?string $modelLabel = 'Sekolah';

    protected static ?string $pluralModelLabel = 'Data Sekolah';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Sekolah')
                    ->required()
                    ->maxLength(255),
                TextInput::make('regency')
                    ->label('Kabupaten/Kota')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('logo')
                    ->label('Logo Sekolah')
                    ->image()
                    ->directory('school-logos')
                    ->maxSize(2048)
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->circular(),
                TextColumn::make('name')
                    ->label('Nama Sekolah')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('regency')
                    ->label('Kabupaten/Kota')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y')
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make()->visible(fn () => auth()->user()->role === 'superadmin'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->visible(fn () => auth()->user()->role === 'superadmin'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSchools::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()->role, ['superadmin', 'admin']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->role === 'superadmin';
    }

    public static function canEdit($record): bool
    {
        return in_array(auth()->user()->role, ['superadmin', 'admin']);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()->role === 'superadmin';
    }
}