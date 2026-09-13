<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">Edit Aset</h2>
    </x-slot>

    <div class="py-12">
        <x-form-card>
            <form action="{{ route('assets.update', $asset) }}" class="space-y-4" method="POST">
                @csrf
                @method('PUT')

                <x-form-input :value="old('nama', $asset->nama)" label="Nama Aset" name="nama" required />

                <x-form-input :value="old('jenis', $asset->jenis)" label="Jenis" name="jenis" />

                <x-form-select label="Kondisi" name="kondisi" required>
                    <option {{ old('kondisi', $asset->kondisi) == 'baik' ? 'selected' : '' }} value="baik">Baik
                    </option>
                    <option {{ old('kondisi', $asset->kondisi) == 'rusak' ? 'selected' : '' }} value="rusak">Rusak
                    </option>
                    <option {{ old('kondisi', $asset->kondisi) == 'perlu_servis' ? 'selected' : '' }}
                        value="perlu_servis">Perlu Servis</option>
                </x-form-select>

                <p class="text-xs text-ink-muted">Harga beli dan alokasi lahan tidak bisa diubah di sini — hapus dan
                    buat ulang aset kalau perlu koreksi besar.</p>

                <div class="flex justify-end space-x-2 pt-2">
                    <a class="rounded-lg bg-bg px-4 py-2 text-sm text-ink hover:bg-line"
                        href="{{ route('assets.index') }}">Batal</a>
                    <button class="rounded-lg bg-primary px-4 py-2 text-sm text-white hover:bg-primary/90"
                        type="submit">Update</button>
                </div>
            </form>
        </x-form-card>
    </div>
</x-app-layout>
