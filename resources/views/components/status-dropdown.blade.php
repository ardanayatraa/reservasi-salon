<div>
    <select wire:change="$emit('changeStatus', {{ $id }}, $event.target.value)"
        class="border-gray-300 rounded-md text-sm shadow-sm">
        @foreach ($statuses as $key => $label)
            <option value="{{ $key }}" @selected($key == $current)>{{ $label }}</option>
        @endforeach
    </select>
</div>
