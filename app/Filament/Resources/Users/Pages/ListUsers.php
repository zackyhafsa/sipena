<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'semua' => Tab::make('Semua'),
            'superadmin' => Tab::make('Super Admin')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('role', 'superadmin')),
            'admin' => Tab::make('Guru')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('role', 'admin')),
            'student' => Tab::make('Siswa')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('role', 'student')),
        ];
    }
}
