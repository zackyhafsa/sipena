<?php

namespace App\Livewire;

use App\Models\School;
use Livewire\Component;

class SchoolSwitcher extends Component
{
    public $activeSchoolId;

    public function mount()
    {
        $this->activeSchoolId = session('active_school_id');
    }

    public function updatedActiveSchoolId($value)
    {
        if (empty($value)) {
            session()->forget('active_school_id');
        } else {
            session()->put('active_school_id', (int) $value);
        }

        // Redirect to refresh current page with new school context
        return redirect(request()->header('Referer', route('filament.admin.pages.dashboard')));
    }

    public function render()
    {
        return view('livewire.school-switcher', [
            'schools' => School::orderBy('name')->get(),
        ]);
    }
}
