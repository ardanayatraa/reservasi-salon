<div>
    <label for="toggle-{{ $id }}" class="flex items-center cursor-pointer relative">
        <input type="checkbox" id="toggle-{{ $id }}" class="sr-only"
            {{ $status === 'available' ? 'checked' : '' }} wire:change="toggleAvailability({{ $id }})">
        <div
            class="toggle-bg bg-gray-200 border-2 border-gray-200 h-6 w-11 rounded-full transition-colors duration-200 ease-in-out {{ $status === 'available' ? 'bg-green-400 border-green-400' : 'bg-red-400 border-red-400' }}">
        </div>
        <span class="ml-2 text-sm font-medium text-gray-900">
            {{ $status === 'available' ? 'Tersedia' : 'Tidak Tersedia' }}
        </span>
    </label>

    <style>
        .toggle-bg:after {
            content: '';
            @apply absolute top-0.5 left-0.5 bg-white border border-gray-300 rounded-full h-5 w-5 transition-transform duration-200 ease-in-out;
        }

        input:checked+.toggle-bg:after {
            transform: translateX(100%);
        }
    </style>
</div>
