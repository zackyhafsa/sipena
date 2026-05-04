<table>
    @if(isset($school) && $school)
    <tr>
        <th colspan="9" style="text-align: center; font-size: 16px; font-weight: bold;">
            {{ $school->name }}
        </th>
    </tr>
    @if($school->regency)
    <tr>
        <th colspan="9" style="text-align: center; font-size: 12px;">
            Pemerintah Daerah {{ $school->regency }}
        </th>
    </tr>
    @endif
    @if($school->address)
    <tr>
        <th colspan="9" style="text-align: center; font-size: 12px;">
            {{ $school->address }}
        </th>
    </tr>
    @endif
    <tr>
        <th colspan="9" style="text-align: center; font-size: 12px; border-bottom: 2px solid #000000;">
            @if($school->phone)Telp: {{ $school->phone }} @endif 
            @if($school->phone && $school->email) | @endif 
            @if($school->email)Email: {{ $school->email }}@endif
        </th>
    </tr>
    <tr>
        <th colspan="9" style="height: 10px;"></th>
    </tr>
    @endif

    <tr>
        <th colspan="9" style="text-align: center; font-size: 14px; font-weight: bold;">
            REKAPITULASI HASIL UJIAN
        </th>
    </tr>
    <tr>
        <th colspan="9" style="text-align: center; font-size: 11px;">
            Dicetak pada: {{ $date }}
        </th>
    </tr>
    <tr>
        <th colspan="9" style="height: 10px;"></th>
    </tr>

    <thead>
        <tr>
            <th style="font-weight: bold; background-color: #4f46e5; color: #ffffff; border: 1px solid #000000;">No</th>
            <th style="font-weight: bold; background-color: #4f46e5; color: #ffffff; border: 1px solid #000000;">Nama Siswa</th>
            <th style="font-weight: bold; background-color: #4f46e5; color: #ffffff; border: 1px solid #000000;">Kelas</th>
            <th style="font-weight: bold; background-color: #4f46e5; color: #ffffff; border: 1px solid #000000;">Ujian</th>
            <th style="font-weight: bold; background-color: #4f46e5; color: #ffffff; border: 1px solid #000000;">Nilai PG</th>
            <th style="font-weight: bold; background-color: #4f46e5; color: #ffffff; border: 1px solid #000000;">Nilai Esai</th>
            <th style="font-weight: bold; background-color: #4f46e5; color: #ffffff; border: 1px solid #000000;">Nilai Akhir</th>
            <th style="font-weight: bold; background-color: #4f46e5; color: #ffffff; border: 1px solid #000000;">Pelanggaran</th>
            <th style="font-weight: bold; background-color: #4f46e5; color: #ffffff; border: 1px solid #000000;">Waktu Selesai</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($records as $i => $record)
            <tr>
                <td style="border: 1px solid #000000;">{{ $i + 1 }}</td>
                <td style="border: 1px solid #000000;">{{ $record->user->name ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $record->user->classroom->name ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $record->exam->title ?? '-' }}</td>
                <td style="border: 1px solid #000000;">{{ $record->score_pg ?? '0' }}</td>
                <td style="border: 1px solid #000000;">{{ $record->score_essay ?? ($record->is_scored_manually ? 'Proses' : '0') }}</td>
                <td style="border: 1px solid #000000;">{{ $record->score !== null ? $record->score : 'Menunggu Koreksi' }}</td>
                <td style="border: 1px solid #000000;">{{ $record->cheat_warning_count ?? 0 }}</td>
                <td style="border: 1px solid #000000;">{{ $record->finished_at ? \Carbon\Carbon::parse($record->finished_at)->format('d M Y - H:i') : '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>