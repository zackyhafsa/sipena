<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Classroom;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    private $school_id;

    public function __construct($school_id = null)
    {
        $this->school_id = $school_id;
    }

    public function model(array $row)
    {
        // Mendukung kedua header ("email" atau "email_atau_username")
        $email = $row['email_atau_username'] ?? $row['email'] ?? null;
        
        if (!isset($row['nama_siswa']) || !$email) {
            return null; // Skip if missing essential data
        }

        // Cari atau buat Kelas berdasarkan namanya
        $classroom_id = null;
        if (isset($row['nama_kelas']) && !empty($row['nama_kelas'])) {
            $classroom = Classroom::firstOrCreate([
                'name' => $row['nama_kelas'],
                'school_id' => $this->school_id
            ]);
            $classroom_id = $classroom->id;
        } elseif (isset($row['id_kelas']) && !empty($row['id_kelas'])) {
            $classroom = Classroom::find($row['id_kelas']);
            $classroom_id = $classroom ? $classroom->id : null;
        }

        // Update data jika email sudah ada, jika belum maka buat baru
        return User::updateOrCreate(
            ['email' => $email], // Gunakan email sebagai pencarian unik
            [
                'name' => $row['nama_siswa'],
                'nis' => $row['nis'] ?? null,
                'nisn' => $row['nisn'] ?? null,
                'password' => Hash::make($row['password'] ?? '12345678'),
                'role' => 'student',
                'classroom_id' => $classroom_id,
                'school_id' => $this->school_id, 
            ]
        );
    }
}
