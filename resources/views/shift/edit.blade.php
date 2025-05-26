<x-app-layout>

    <div class="px-4 py-6 sm:px-6 mb-4 lg:px-8 bg-white shadow sm:rounded-lg flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Shift
        </h2>

    </div>


    <div class="py-6">
        <div class="w-full mx-auto bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('shift.update', $shift) }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <x-label for="nama_shift" value="Nama Shift" />
                        <x-input id="nama_shift" name="nama_shift" type="text" class="mt-1 block w-full"
                            :value="old('nama_shift', $shift->nama_shift)" required />
                        <x-input-error for="nama_shift" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="start_time" value="Waktu Mulai" />
                        <x-input id="start_time" name="start_time" type="time" class="mt-1 block w-full"
                            :value="old('start_time', $shift->start_time)" required />
                        <x-input-error for="start_time" class="mt-2" />
                    </div>
                    <div>
                        <x-label for="end_time" value="Waktu Selesai" />
                        <x-input id="end_time" name="end_time" type="time" class="mt-1 block w-full"
                            :value="old('end_time', $shift->end_time)" required />
                        <x-input-error for="end_time" class="mt-2" />
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-button>Update</x-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
