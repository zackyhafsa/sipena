@php
    $activeName = $activeSchoolId
        ? ($schools->firstWhere('id', (int) $activeSchoolId)?->name ?? 'Sekolah Tidak Ditemukan')
        : 'Semua Sekolah';
@endphp

<div class="px-3 pb-3 pt-1">
    <p class="mb-1.5 text-xs font-semibold uppercase tracking-widest text-gray-400 dark:text-gray-500">
        Konteks Sekolah
    </p>

    <div class="relative">
        <select id="school-switcher" wire:model.live="activeSchoolId" class="w-full appearance-none rounded-lg border py-2 pl-3 pr-8 text-sm font-medium shadow-sm transition-all
                   border-gray-300 bg-white text-gray-900
                   hover:border-gray-400
                   focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20
                   dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100
                   dark:[color-scheme:dark]
                   dark:hover:border-gray-500 dark:hover:bg-gray-750
                   dark:focus:border-indigo-400 dark:focus:ring-indigo-400/20">
            <option value="">Semua Sekolah</option>
            @foreach($schools as $school)
                <option value="{{ $school->id }}">{{ $school->name }}</option>
            @endforeach
        </select>

        <div
            class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2.5 text-gray-400 dark:text-gray-500">
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                    d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                    clip-rule="evenodd" />
            </svg>
        </div>
    </div>

    <div class="mt-2 flex items-center gap-1.5">
        <span class="inline-block h-1.5 w-1.5 rounded-full transition-colors
                     {{ $activeSchoolId ? 'bg-indigo-500' : 'bg-gray-300 dark:bg-gray-600' }}">
        </span>
        <p class="text-[11px] text-gray-400 dark:text-gray-500">
            Aktif:
            <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $activeName }}</span>
        </p>
    </div>
</div>