<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">Edit Lahan: {{ $lahan->nama }}</h2>
    </x-slot>

    <div class="py-12">
        <x-form-card>
            <form action="{{ route('lahans.update', $lahan) }}" class="space-y-4" method="POST">
                @csrf
                @method('PUT')

                <x-form-input :value="old('nama', $lahan->nama)" label="Nama Lahan" name="nama" required />

                <div class="grid grid-cols-2 gap-4">
                    <x-form-input :value="old('luas_panjang_m', $lahan->luas_panjang_m)" label="Panjang (m)" name="luas_panjang_m" step="0.01"
                        type="number" />
                    <x-form-input :value="old('luas_lebar_m', $lahan->luas_lebar_m)" label="Lebar (m)" name="luas_lebar_m" step="0.01"
                        type="number" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <x-form-input :value="old('jarak_tanam_m', $lahan->jarak_tanam_m)" label="Jarak Tanam (m)" name="jarak_tanam_m" step="0.01"
                        type="number" />
                    <x-form-input :value="old('jarak_pagar_m', $lahan->jarak_pagar_m)" label="Jarak dari Pagar (m)" name="jarak_pagar_m" step="0.01"
                        type="number" />
                </div>

                <x-form-input :value="old('estimasi_jumlah_pohon', $lahan->estimasi_jumlah_pohon)" label="Estimasi Jumlah Pohon" name="estimasi_jumlah_pohon"
                    type="number" />

                <div class="flex justify-end space-x-2 pt-2">
                    <a class="rounded-lg bg-bg px-4 py-2 text-sm text-ink hover:bg-line"
                        href="{{ route('lahans.index') }}">Batal</a>
                    <button class="rounded-lg bg-primary px-4 py-2 text-sm text-white hover:bg-primary/90"
                        type="submit">Update</button>
                </div>
            </form>
        </x-form-card>
    </div>
</x-app-layout>
