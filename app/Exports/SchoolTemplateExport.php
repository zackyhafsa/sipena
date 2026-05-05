<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SchoolTemplateExport implements FromArray, WithHeadings
{
    public function array(): array
    {
        return [
            [
                'Sekolah Contoh 1', 'Kabupaten Contoh', 'Jalan Contoh No 1', '08123456789', 'contoh1@sekolah.com'
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Sekolah',
            'Kabupaten',
            'Alamat',
            'Telepon',
            'Email',
        ];
    }
}
