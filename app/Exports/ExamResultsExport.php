<?php

namespace App\Exports;

use App\Helpers\SchoolContext;
use App\Models\School;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExamResultsExport implements FromView, ShouldAutoSize, WithStyles
{
    public function __construct(private Collection $records) {}

    public function view(): View
    {
        $schoolId = SchoolContext::getActiveSchoolId();
        $school = $schoolId ? School::find($schoolId) : null;

        return view('exports.exam-results-excel', [
            'records' => $this->records,
            'school' => $school,
            'date' => now()->format('d M Y H:i'),
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as the main letterhead (if school is present)
            1    => ['font' => ['bold' => true, 'size' => 14]],
        ];
    }
}
