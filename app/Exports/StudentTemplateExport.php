<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentTemplateExport implements FromArray, WithColumnWidths, WithHeadings, WithStyles
{
    public function headings(): array
    {
        return [
            'nis',
            'nisn',
            'nama_siswa',
            'email_atau_username',
            'password',
            'nama_kelas',
        ];
    }

    public function array(): array
    {
        return [
            [
                '112233',
                '0011223344',
                'Siswa Dummy 1',
                'siswa1@sekolah.com',
                '12345678',
                'Kelas 7A',
            ],
            [
                '223344',
                '0022334455',
                'Siswa Dummy 2',
                'siswa2@sekolah.com',
                '12345678',
                'Kelas 7A',
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15, // nama_siswa
            'B' => 20, // email_atau_username
            'C' => 30, // nis
            'D' => 30, // nisn
            'E' => 20, // password
            'F' => 20, // nama_kelas
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'],
                ],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
