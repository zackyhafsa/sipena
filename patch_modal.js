const fs = require('fs');

let content = fs.readFileSync('resources/views/livewire/student-exam.blade.php', 'utf8');

// Confirmation Modal: Remove `@click="showConfirmModal = false"` from backdrop
content = content.replace(/<div x-show="showConfirmModal" x-transition.opacity[\s\S]*?class="fixed inset-0[^>]*?aria-hidden="true"[\s]*@click="showConfirmModal = false"><\/div>/m, (match) => {
    return match.replace(/[\s]*@click="showConfirmModal = false"/, '');
});

// Warning Modal: Remove `@click="showWarning = false"` from backdrop
content = content.replace(/<div x-show="showWarning" x-transition.opacity[\s\S]*?class="fixed inset-0[^>]*?aria-hidden="true"[\s]*@click="showWarning = false"><\/div>/m, (match) => {
    return match.replace(/[\s]*@click="showWarning = false"/, '');
});


fs.writeFileSync('resources/views/livewire/student-exam.blade.php', content);
