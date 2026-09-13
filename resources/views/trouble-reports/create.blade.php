<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">Lapor Masalah</h2>
    </x-slot>

    <div class="py-12">
        <x-form-card>
            <form action="{{ route('trouble-reports.store') }}" class="space-y-4" enctype="multipart/form-data"
                method="POST">
                @csrf

                <x-form-select label="Lahan" name="lahan_id" required>
                    @foreach ($lahans as $lahan)
                        <option {{ old('lahan_id') == $lahan->id ? 'selected' : '' }} value="{{ $lahan->id }}">
                            {{ $lahan->nama }}
                        </option>
                    @endforeach
                </x-form-select>

                <x-form-input :value="old('judul')" label="Judul Masalah" name="judul"
                    placeholder="mis. Hama daun, pompa mati, tanaman layu" required />

                <x-form-select label="Tingkat Urgensi" name="urgensi" required>
                    <option {{ old('urgensi') == 'rendah' ? 'selected' : '' }} value="rendah">Rendah</option>
                    <option {{ old('urgensi', 'sedang') == 'sedang' ? 'selected' : '' }} value="sedang">Sedang</option>
                    <option {{ old('urgensi') == 'tinggi' ? 'selected' : '' }} value="tinggi">Tinggi</option>
                </x-form-select>

                <x-form-textarea :rows="4" :value="old('deskripsi')" label="Deskripsi" name="deskripsi" />

                <div>
                    <label class="mb-1 block text-sm font-medium text-ink">Foto (bisa lebih dari satu)</label>
                    <input accept="image/*"
                        class="block w-full text-sm text-ink-muted file:mr-4 file:rounded-lg file:border-0 file:bg-primary-tint file:px-4 file:py-2 file:text-sm file:text-primary"
                        multiple name="foto[]" type="file">
                    @error('foto.*')
                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <a class="rounded-lg bg-bg px-4 py-2 text-sm text-ink hover:bg-line"
                        href="{{ route('trouble-reports.index') }}">Batal</a>
                    <button class="rounded-lg bg-primary px-4 py-2 text-sm text-white hover:bg-primary/90"
                        type="submit">Kirim Laporan</button>
                </div>
            </form>
        </x-form-card>
    </div>
</x-app-layout>
