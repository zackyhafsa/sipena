<div class="flex items-center gap-2">
    <select
        wire:model.live="activeSchoolId"
        class="fi-input rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm shadow-sm transition duration-75 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
    >
        <option value="">🏫 Semua Sekolah</option>
        @foreach($schools as $school)
            <option value="{{ $school->id }}">{{ $school->name }}</option>
        @endforeach
    </select>
</div>
