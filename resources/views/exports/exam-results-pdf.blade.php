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
    </style>
</head>
<body>
    <h2>📋 Rekap Hasil Ujian Siswa</h2>
    <p class="subtitle">Dicetak pada: {{ $date }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Ujian</th>
                <th>Nilai PG</th>
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
                    <td>
                        {{ is_array($record->answers_log) && isset($record->answers_log['pg_score']) ? $record->answers_log['pg_score'] : ($record->score ?? '0') }}
                    </td>
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
