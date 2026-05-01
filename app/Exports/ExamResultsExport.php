<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExamResultsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Collection $records) {}

    public function collection(): Collection
    {
        return $this->records;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'Kelas',
            'Ujian',
            'Nilai PG',
            'Nilai Esai',
            'Nilai Akhir',
            'Pelanggaran',
            'Waktu Selesai',
        ];
    }

    public function map($record): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $record->user->name ?? '-',
            $record->user->classroom->name ?? '-',
            $record->exam->title ?? '-',
            $record->score_pg ?? 0,
            $record->score_essay ?? ($record->is_scored_manually ? 'Proses' : 0),
            $record->score ?? 'Menunggu Koreksi',
            $record->cheat_warning_count ?? 0,
            $record->finished_at ? Carbon::parse($record->finished_at)->format('d M Y - H:i') : '-',
        ];
    }
}
