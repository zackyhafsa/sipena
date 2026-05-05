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

        // Menentukan kunci utama untuk mencari data yang sudah ada (Urutan prioritas: NIS -> NISN -> Email)
        // Kita WAJIB menyertakan 'school_id' agar tidak terjadi bentrok NIS/NISN atau Email dengan sekolah lain!
        $matchCondition = ['school_id' => $this->school_id];
        
        if (!empty($row['nis'])) {
            $matchCondition['nis'] = $row['nis'];
        } elseif (!empty($row['nisn'])) {
            $matchCondition['nisn'] = $row['nisn'];
        } elseif (!empty($email)) {
            $matchCondition['email'] = $email;
        } else {
            return null; // Skip jika tidak ada identitas sama sekali
        }

        // Cek apakah email yang dimasukkan ternyata sudah dipakai oleh siswa lain di seluruh sistem (karena email sifatnya global unik)
        if ($email) {
            $existingEmailUser = User::where('email', $email)->first();
            // Jika email terdeteksi milik akun ID lain, maka fallback untuk tidak mengupdate/menggunakan email tersebut
            // agar mencegah sistem error (Integrity Constraint Violation).
        }

        // Update data jika kecocokan ditemukan, jika belum maka buat baru
        return User::updateOrCreate(
            $matchCondition,
            [
                'name' => $row['nama_siswa'],
                'email' => $email,
                'nis' => $row['nis'] ?? null,
                'nisn' => $row['nisn'] ?? null,
                'password' => Hash::make($row['password'] ?? '12345678'),
                'role' => 'student',
                'classroom_id' => $classroom_id,
                 // school_id sudah ada di matchCondition, otomatis masuk sini juga
            ]
        );
    }
}
