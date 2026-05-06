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
        
        if (!isset($row['nama_siswa']) || empty($row['nisn'])) {
            return null; // Skip jika tidak ada data esensial atau NISN kosong
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

        // Menentukan kunci utama untuk mencari data yang sudah ada (Menggunakan NISN)
        $matchCondition = [
            'school_id' => $this->school_id,
            'nisn' => $row['nisn']
        ];
        
        // Cek target pembaruan atau pembuatan user
        $existingUser = User::where($matchCondition)->first();

        // Cek apakah email yang dimasukkan ternyata sudah diregistrasikan sebelumnya
        // (Karena database melarang 1 email dipakai mendaftar 2 kali di sistem meskipun beda sekolah)
        if ($email) {
            $emailCheck = User::where('email', $email);
            if ($existingUser) {
                // Kecualikan pengecekan dari user yang sedang diupdate ini sendiri
                $emailCheck->where('id', '!=', $existingUser->id);
            }
            
            if ($emailCheck->exists()) {
                throw new \Exception("Data email '{$email}' atau NISN '{$row['nisn']}' tersebut sudah terdaftar di sistem! Mohon pastikan data valid dan tidak digunakan oleh pengguna lain.");
            }
        }

        // Data yang akan di update / create
        $userData = [
            'name' => $row['nama_siswa'],
            'nis' => $row['nis'] ?? null,
            'nisn' => $row['nisn'],
            'role' => 'student',
            'classroom_id' => $classroom_id,
        ];

        if ($email) {
            $userData['email'] = $email;
        }

        if (!$existingUser) {
            // Jika user baru, beri password default atau sesuai excel
            $userData['password'] = Hash::make($row['password'] ?? '12345678');
        } elseif (!empty($row['password'])) {
            // Jika user sudah ada dan admin memasukkan kolom password di excel, update passwordnya
            $userData['password'] = Hash::make($row['password']);
        }
        // Jika user sudah ada tapi kolom password kosong di excel, JANGAN ubah passwordnya agar tidak tergembok.

        return User::updateOrCreate(
            $matchCondition,
            $userData
        );
    }
}
