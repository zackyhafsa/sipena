<?php

namespace App\Livewire;

use App\Models\School;
use Filament\Forms\Components\Select;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Livewire\Component;

class SchoolSwitcher extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $this->data['activeSchoolId'] = session('active_school_id');
    }

    public function schema(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('activeSchoolId')
                    ->hiddenLabel()
                    ->placeholder('🏫 Semua Sekolah')
                    ->options(School::pluck('name', 'id'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function ($state) {
                        if (empty($state)) {
                            session()->forget('active_school_id');
                        } else {
                            session()->put('active_school_id', (int) $state);
                        }
                        
                        return redirect(request()->header('Referer', route('filament.admin.pages.dashboard')));
                    }),
            ])
            ->statePath('data');
    }

    public function render()
    {
        return view('livewire.school-switcher');
    }
}
