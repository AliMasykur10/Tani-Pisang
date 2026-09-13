<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">Tambah Partner</h2>
    </x-slot>

    <div class="py-12">
        <x-form-card>
            <form action="{{ route('partners.store') }}" method="POST" class="space-y-4">
                @csrf

                <x-form-select label="Tipe Partner" name="tipe" required>
                    <option value="penyedia_pembeli" {{ old('tipe') == 'penyedia_pembeli' ? 'selected' : '' }}>Penyedia Bibit & Pembeli</option>
                    <option value="pemilik_lahan" {{ old('tipe') == 'pemilik_lahan' ? 'selected' : '' }}>Pemilik Lahan</option>
                </x-form-select>

                <x-form-input label="Nama" name="nama" :value="old('nama')" required />

                <x-form-input label="Kontak" name="kontak" :value="old('kontak')" placeholder="No. HP / email" />

                <x-form-textarea label="Catatan" name="catatan" :value="old('catatan')" />

                <div class="flex justify-end space-x-2 pt-2">
                    <a href="{{ route('partners.index') }}" class="px-4 py-2 bg-bg text-ink rounded-lg text-sm hover:bg-line">Batal</a>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg text-sm hover:bg-primary/90">Simpan</button>
                </div>
            </form>
        </x-form-card>
    </div>
</x-app-layout>