<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Hasil Ujian</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; margin: 20px; }
        h2 { text-align: center; margin-bottom: 5px; color: #1f2937; }
        .subtitle { text-align: center; font-size: 10px; color: #6b7280; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #4f46e5; color: white; padding: 8px 6px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        td { padding: 7px 6px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .badge { padding: 2px 8px; border-radius: 10px; font-size: 9px; font-weight: bold; }
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
        .badge-danger { background-color: #fee2e2; color: #991b1b; }
        .footer { margin-top: 20px; text-align: right; font-size: 9px; color: #9ca3af; }
        .kop-surat { width: 100%; border-bottom: 3px solid #000; margin-bottom: 20px; padding-bottom: 10px; display: table; }
        .kop-surat > div { display: table-cell; vertical-align: middle; }
        .logo { width: 15%; text-align: center; }
        .logo img { max-height: 80px; max-width: 80px; }
        .school-info { width: 70%; text-align: center; }
        .school-info h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .school-info p { margin: 2px 0; font-size: 10px; }
        .title-report { text-align: center; margin-bottom: 20px; }
        .title-report h2 { margin: 0; font-size: 14px; text-transform: uppercase; }
        .title-report p { margin: 5px 0 0 0; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    @if(isset($school) && $school)
    <div class="kop-surat">
        <div class="logo">
            @if($school->logo_kabupaten)
                <img src="{{ public_path('storage/' . $school->logo_kabupaten) }}" alt="Logo Kabupaten">
            @endif
        </div>
        <div class="school-info">
            <h1>{{ $school->name }}</h1>
            @if($school->regency)<p>Pemerintah Daerah {{ $school->regency }}</p>@endif
            @if($school->address)<p>{{ $school->address }}</p>@endif
            <p>
                @if($school->phone)Telp: {{ $school->phone }} @endif 
                @if($school->phone && $school->email) | @endif 
                @if($school->email)Email: {{ $school->email }}@endif
            </p>
        </div>
        <div class="logo">
            @if($school->logo)
                <img src="{{ public_path('storage/' . $school->logo) }}" alt="Logo Sekolah">
            @endif
        </div>
    </div>
    @endif

    <div class="title-report">
        <h2>Rekapitulasi Hasil Ujian</h2>
        <p>Dicetak pada: {{ $date }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Ujian</th>
                <th>Nilai PG</th>
                <th>Nilai Esai</th>
                <th>Nilai Akhir</th>
                <th>Pelanggaran</th>
                <th>Waktu Selesai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($records as $i => $record)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $record->user->name ?? '-' }}</td>
                    <td>{{ $record->user->classroom->name ?? '-' }}</td>
                    <td>{{ $record->exam->title ?? '-' }}</td>
                    <td>{{ $record->score_pg ?? '0' }}</td>
                    <td>{{ $record->score_essay ?? ($record->is_scored_manually ? 'Proses' : '0') }}</td>
                    <td>
                        @if ($record->score !== null)
                            <span class="badge {{ $record->score >= 80 ? 'badge-success' : ($record->score >= 60 ? 'badge-warning' : 'badge-danger') }}">
                                {{ $record->score }}
                            </span>
                        @else
                            <span class="badge badge-warning">Menunggu Koreksi</span>
                        @endif
                    </td>
                    <td>{{ $record->cheat_warning_count ?? 0 }}</td>
                    <td>{{ $record->finished_at ? \Carbon\Carbon::parse($record->finished_at)->format('d M Y - H:i') : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        SIPENA - Sistem Ujian CBT &copy; {{ date('Y') }}
    </div>
</body>
</html>
