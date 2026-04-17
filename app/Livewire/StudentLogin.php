<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class StudentLogin extends Component
{
    public $email = '';
    public $password = '';
    
    // Default title for guest
    public $title = 'Log In Siswa - Ujian CBT';

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            // Check role -> admin/superadmin should use /admin
            $user = auth()->user();
            if (in_array($user->role, ['admin', 'superadmin'])) {
                return redirect()->intended('/admin');
            }

            // Student role logic
            return redirect()->intended('/dashboard');
        }

        $this->addError('email', 'Kredensial tidak sesuai / salah.');
    }

    public function render()
    {
        return view('livewire.student-login');
    }
}
