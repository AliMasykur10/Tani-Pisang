<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">Edit Partner</h2>
    </x-slot>

    <div class="py-12">
        <x-form-card>
            <form action="{{ route('partners.update', $partner) }}" class="space-y-4" method="POST">
                @csrf
                @method('PUT')

                <x-form-select label="Tipe Partner" name="tipe" required>
                    <option {{ old('tipe', $partner->tipe) == 'penyedia_pembeli' ? 'selected' : '' }}
                        value="penyedia_pembeli">Penyedia Bibit & Pembeli</option>
                    <option {{ old('tipe', $partner->tipe) == 'pemilik_lahan' ? 'selected' : '' }} value="pemilik_lahan">
                        Pemilik Lahan</option>
                </x-form-select>

                <x-form-input :value="old('nama', $partner->nama)" label="Nama" name="nama" required />

                <x-form-input :value="old('kontak', $partner->kontak)" label="Kontak" name="kontak" />

                <x-form-textarea :value="old('catatan', $partner->catatan)" label="Catatan" name="catatan" />

                <div class="flex justify-end space-x-2 pt-2">
                    <a class="rounded-lg bg-bg px-4 py-2 text-sm text-ink hover:bg-line"
                        href="{{ route('partners.index') }}">Batal</a>
                    <button class="rounded-lg bg-primary px-4 py-2 text-sm text-white hover:bg-primary/90"
                        type="submit">Update</button>
                </div>
            </form>
        </x-form-card>
    </div>
</x-app-layout>
