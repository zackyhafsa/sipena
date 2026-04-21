import re

with open("resources/views/livewire/student-exam.blade.php", "r") as f:
    text = f.read()

text = re.sub(r'class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity"\s*aria-hidden="true"\s*@click="showWarning = false"></div>', 
              r'class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity"\n                    aria-hidden="true"></div>', text)

with open("resources/views/livewire/student-exam.blade.php", "w") as f:
    f.write(text)
