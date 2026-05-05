<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\School;

class UserTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        $schoolId = School::first()->id ?? 1;

        return [
            [
                'Guru Matematika', 'guru1@sekolah.com', 'password123', 'admin', $schoolId
            ],
            [
                'Siswa Pintar', 'siswa1@sekolah.com', 'password123', 'student', $schoolId
            ],
            [
                'Super Admin Utama', 'admin@cbt.com', 'password123', 'superadmin', ''
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'Email',
            'Password',
            'Peran',
            'Sistem ID Sekolah',
        ];
    }
}
