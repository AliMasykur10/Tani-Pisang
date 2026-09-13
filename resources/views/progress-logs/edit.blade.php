<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">Edit Log Perkembangan</h2>
    </x-slot>

    <div class="py-12">
        <x-form-card>
            <form action="{{ route('progress-logs.update', $progressLog) }}" class="space-y-4"
                enctype="multipart/form-data" method="POST">
                @csrf
                @method('PUT')

                <x-form-select label="Lahan" name="lahan_id" required>
                    @foreach ($lahans as $lahan)
                        <option {{ old('lahan_id', $progressLog->lahan_id) == $lahan->id ? 'selected' : '' }}
                            value="{{ $lahan->id }}">
                            {{ $lahan->nama }}
                        </option>
                    @endforeach
                </x-form-select>

                <x-form-input :value="old('tanggal', $progressLog->tanggal->format('Y-m-d'))" label="Tanggal" name="tanggal" required type="date" />

                <x-form-textarea :rows="4" :value="old('keterangan', $progressLog->keterangan)" label="Keterangan" name="keterangan" />

                @if (!empty($progressLog->foto_urls))
                    <div>
                        <label class="mb-1 block text-sm font-medium text-ink">Foto Saat Ini</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($progressLog->foto_urls as $foto)
                                <img class="h-16 w-16 rounded-lg border border-line object-cover"
                                    src="{{ Storage::url($foto) }}">
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <label class="mb-1 block text-sm font-medium text-ink">Tambah Foto Baru (opsional)</label>
                    <input accept="image/*"
                        class="block w-full text-sm text-ink-muted file:mr-4 file:rounded-lg file:border-0 file:bg-primary-tint file:px-4 file:py-2 file:text-sm file:text-primary"
                        multiple name="foto[]" type="file">
                    <p class="mt-1 text-xs text-ink-muted">Foto baru akan ditambahkan, foto lama tetap tersimpan.</p>
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <a class="rounded-lg bg-bg px-4 py-2 text-sm text-ink hover:bg-line"
                        href="{{ route('progress-logs.index') }}">Batal</a>
                    <button class="rounded-lg bg-primary px-4 py-2 text-sm text-white hover:bg-primary/90"
                        type="submit">Update</button>
                </div>
            </form>
        </x-form-card>
    </div>
</x-app-layout>
