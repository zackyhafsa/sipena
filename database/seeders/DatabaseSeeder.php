<?php

namespace Database\Seeders;

use App\Models\Classroom;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $school = School::firstOrCreate([
            'name' => 'SD Negeri 1 Contoh',
        ], [
            'regency' => 'Kota Contoh',
        ]);

        $classroom = Classroom::firstOrCreate([
            'name' => 'Kelas 1A',
            'school_id' => $school->id,
        ]);

        User::firstOrCreate([
            'email' => 'superadmin@sipena.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);

        User::firstOrCreate([
            'email' => 'admin@sipena.com',
        ], [
            'name' => 'Admin Sekolah',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'school_id' => $school->id,
        ]);

        User::firstOrCreate([
            'email' => 'siswa@sipena.com',
        ], [
            'name' => 'Siswa Contoh',
            'password' => Hash::make('password'),
            'role' => 'student',
            'school_id' => $school->id,
            'classroom_id' => $classroom->id,
        ]);
    }
}
