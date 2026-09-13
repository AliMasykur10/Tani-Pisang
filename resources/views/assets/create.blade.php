<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">Tambah Aset</h2>
    </x-slot>

    <div class="py-12">
        <x-form-card>
            <form action="{{ route('assets.store') }}" class="space-y-4" method="POST">
                @csrf

                <x-form-input :value="old('nama')" label="Nama Aset" name="nama" placeholder="mis. Pompa Sibel" required />

                <x-form-input :value="old('jenis')" label="Jenis" name="jenis"
                    placeholder="mis. Pengairan, Alat Semprot" />

                <x-form-input :value="old('tanggal_beli', date('Y-m-d'))" label="Tanggal Beli" name="tanggal_beli" type="date" />

                <div>
                    <x-form-input :value="old('harga_beli')" label="Harga Beli (Rp)" name="harga_beli" step="0.01"
                        type="number" />
                    <p class="mt-1 text-xs text-ink-muted">Kalau diisi, otomatis tercatat sebagai transaksi pengeluaran
                        kategori "Aset" di lahan pertama yang dipilih di bawah.</p>
                </div>

                <x-form-select label="Kondisi" name="kondisi" required>
                    <option {{ old('kondisi', 'baik') == 'baik' ? 'selected' : '' }} value="baik">Baik</option>
                    <option {{ old('kondisi') == 'rusak' ? 'selected' : '' }} value="rusak">Rusak</option>
                    <option {{ old('kondisi') == 'perlu_servis' ? 'selected' : '' }} value="perlu_servis">Perlu Servis
                    </option>
                </x-form-select>

                <div>
                    <label class="mb-2 block text-sm font-medium text-ink">Digunakan di Lahan</label>
                    <div class="space-y-1.5">
                        @foreach ($lahans as $lahan)
                            <label class="flex items-center text-sm text-ink">
                                <input {{ in_array($lahan->id, old('lahan_ids', [])) ? 'checked' : '' }}
                                    class="mr-2 rounded border-line text-primary focus:ring-primary" name="lahan_ids[]"
                                    type="checkbox" value="{{ $lahan->id }}">
                                {{ $lahan->nama }}
                            </label>
                        @endforeach
                    </div>
                    <p class="mt-1 text-xs text-ink-muted">Kalau dipilih lebih dari satu, porsi pemakaian otomatis
                        dibagi rata.</p>
                    @error('lahan_ids')
                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <a class="rounded-lg bg-bg px-4 py-2 text-sm text-ink hover:bg-line"
                        href="{{ route('assets.index') }}">Batal</a>
                    <button class="rounded-lg bg-primary px-4 py-2 text-sm text-white hover:bg-primary/90"
                        type="submit">Simpan</button>
                </div>
            </form>
        </x-form-card>
    </div>
</x-app-layout>
