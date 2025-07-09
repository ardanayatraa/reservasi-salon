@if ($foto)
    <img src="{{ asset('storage/perawatan/' . $foto) }}" alt="Foto Perawatan" class="h-16 w-16 object-cover rounded-md">
@else
    <span class="text-gray-400 italic">Tidak ada foto</span>
@endif
