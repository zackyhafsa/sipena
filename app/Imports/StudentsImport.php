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
        if (!isset($row['nama_siswa']) || !isset($row['email'])) {
            return null; // Skip if missing essential data
        }

        // Resolve Classroom by ID or Name
        $classroom_id = null;
        if (isset($row['id_kelas']) && !empty($row['id_kelas'])) {
            $classroom = Classroom::find($row['id_kelas']);
            $classroom_id = $classroom ? $classroom->id : null;
        }

        return new User([
            'name' => $row['nama_siswa'],
            'email' => $row['email'],
            'password' => Hash::make($row['password'] ?? '12345678'),
            'role' => 'student',
            'classroom_id' => $classroom_id,
            'school_id' => $this->school_id, // Link school based on who is importing
        ]);
    }
}
