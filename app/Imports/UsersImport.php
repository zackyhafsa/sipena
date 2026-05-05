<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['email'])) {
            return null;
        }

        $userData = [
            'name' => $row['nama_lengkap'] ?? null,
            'role' => $row['peran'] ?? 'student',
            'school_id' => $row['sistem_id_sekolah'] ?? null,
        ];

        if (!empty($row['password'])) {
            $userData['password'] = Hash::make($row['password']);
        } else {
            $userData['password'] = Hash::make('password'); // Default password if empty
        }

        return User::updateOrCreate(
            ['email' => $row['email']],
            $userData
        );
    }
}
