<div class="relative inline-block w-48">
    <select wire:change="$emit('changeStatus', {{ $id }}, $event.target.value)"
        class="w-full appearance-none bg-white border border-gray-300 text-gray-700 text-sm rounded-lg px-4 py-2 shadow focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out">
        @foreach ($statuses as $key => $label)
            <option value="{{ $key }}" @selected($key == $current)>{{ $label }}</option>
        @endforeach
    </select>

    <!-- Chevron Icon -->
    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
        </svg>
    </div>
</div>
