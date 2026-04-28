<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Informasi Pengguna')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->password()
                            ->label('Password')
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn ($livewire) => $livewire instanceof \App\Filament\Resources\Users\Pages\CreateUser)
                            ->maxLength(255),
                        Select::make('role')
                            ->label('Peran (Role)')
                            ->options([
                                'student' => 'Siswa',
                                'admin' => 'Admin (Guru)',
                                'superadmin' => 'Super Admin',
                            ])
                            ->default('student')
                            ->required(),
                        Select::make('classroom_id')
                            ->label('Kelas')
                            ->relationship('classroom', 'name')
                            ->preload()
                            ->searchable()
                            ->helperText('Hanya wajib diisi jika role adalah Siswa.'),
                        Select::make('school_id')
                            ->label('Sekolah')
                            ->relationship('school', 'name')
                            ->preload()
                            ->searchable()
                            ->visible(fn () => auth()->user()->role === 'superadmin')
                            ->helperText('Wajib diisi jika role adalah Admin (Guru) atau Siswa.')
                    ])->columns(2)
            ]);
    }
}
