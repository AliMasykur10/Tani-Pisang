<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">Edit Laporan</h2>
    </x-slot>

    <div class="py-12">
        <x-form-card>
            <form action="{{ route('trouble-reports.update', $troubleReport) }}" class="space-y-4" method="POST">
                @csrf
                @method('PUT')

                <x-form-select label="Lahan" name="lahan_id" required>
                    @foreach ($lahans as $lahan)
                        <option {{ old('lahan_id', $troubleReport->lahan_id) == $lahan->id ? 'selected' : '' }}
                            value="{{ $lahan->id }}">
                            {{ $lahan->nama }}
                        </option>
                    @endforeach
                </x-form-select>

                <x-form-input :value="old('judul', $troubleReport->judul)" label="Judul Masalah" name="judul" required />

                <x-form-select label="Tingkat Urgensi" name="urgensi" required>
                    <option {{ old('urgensi', $troubleReport->urgensi) == 'rendah' ? 'selected' : '' }} value="rendah">
                        Rendah</option>
                    <option {{ old('urgensi', $troubleReport->urgensi) == 'sedang' ? 'selected' : '' }} value="sedang">
                        Sedang</option>
                    <option {{ old('urgensi', $troubleReport->urgensi) == 'tinggi' ? 'selected' : '' }} value="tinggi">
                        Tinggi</option>
                </x-form-select>

                <x-form-textarea :rows="4" :value="old('deskripsi', $troubleReport->deskripsi)" label="Deskripsi" name="deskripsi" />

                <div class="flex justify-end space-x-2 pt-2">
                    <a class="rounded-lg bg-bg px-4 py-2 text-sm text-ink hover:bg-line"
                        href="{{ route('trouble-reports.index') }}">Batal</a>
                    <button class="rounded-lg bg-primary px-4 py-2 text-sm text-white hover:bg-primary/90"
                        type="submit">Update</button>
                </div>
            </form>
        </x-form-card>
    </div>
</x-app-layout>
