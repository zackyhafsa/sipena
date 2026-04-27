<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class QuestionTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function headings(): array
    {
        return [
            'type',
            'question_text',
            'option_a',
            'option_b',
            'option_c',
            'option_d',
            'option_e',
            'correct_answer',
            'correct_answer_essay',
            'score_weight',
        ];
    }

    public function array(): array
    {
        return [
            [
                'multiple_choice',
                'Berapakah hasil dari 2 + 2?',
                '3',
                '4',
                '5',
                '6',
                '',
                'B',
                '',
                '1',
            ],
            [
                'multiple_choice',
                'Siapakah nabi terakhir dalam Islam?',
                'Nabi Isa AS',
                'Nabi Muhammad SAW',
                'Nabi Musa AS',
                'Nabi Ibrahim AS',
                'Nabi Yusuf AS',
                'B',
                '',
                '1',
            ],
            [
                'essay',
                'Jelaskan pengertian sholat menurut bahasa dan istilah!',
                '',
                '',
                '',
                '',
                '',
                '',
                'Sholat menurut bahasa berarti doa, sedangkan menurut istilah adalah ibadah yang dimulai dari takbiratul ihram dan diakhiri dengan salam.',
                '2',
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 18,
            'B' => 45,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 20,
            'H' => 16,
            'I' => 40,
            'J' => 14,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'],
                ],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
