<?php

namespace App\Imports;

use App\Models\School;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SchoolsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        if (empty($row['nama_sekolah'])) {
            return null;
        }

        return School::updateOrCreate(
            ['name' => $row['nama_sekolah']],
            [
                'regency' => $row['kabupaten'] ?? null,
                'address' => $row['alamat'] ?? null,
                'phone' => $row['telepon'] ?? null,
                'email' => $row['email'] ?? null,
            ]
        );
    }
}
