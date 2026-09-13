<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">Tambah Kesepakatan</h2>
    </x-slot>

    <div class="py-12">
        <x-form-card>
            <form action="{{ route('partner-agreements.store') }}" class="space-y-4" method="POST" x-data="{ skema: 'sewa' }">
                @csrf

                <x-form-select label="Partner" name="partner_id" required>
                    @foreach ($partners as $partner)
                        <option {{ old('partner_id', request('partner_id')) == $partner->id ? 'selected' : '' }}
                            value="{{ $partner->id }}">
                            {{ $partner->nama }}
                        </option>
                    @endforeach
                </x-form-select>

                <x-form-select label="Lahan" name="lahan_id" required>
                    @foreach ($lahans as $lahan)
                        <option {{ old('lahan_id') == $lahan->id ? 'selected' : '' }} value="{{ $lahan->id }}">
                            {{ $lahan->nama }}
                        </option>
                    @endforeach
                </x-form-select>

                <div>
                    <label class="mb-1 block text-sm font-medium text-ink">Skema</label>
                    <select
                        class="block w-full rounded-lg border-line bg-surface text-sm text-ink focus:border-primary focus:ring-primary"
                        name="skema" x-model="skema">
                        <option value="sewa">Sewa</option>
                        <option value="bagi_hasil">Bagi Hasil</option>
                    </select>
                    @error('skema')
                        <p class="mt-1 text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div x-show="skema === 'sewa'">
                    <x-form-input :value="old('nominal_sewa')" label="Nominal Sewa (Rp)" name="nominal_sewa" step="0.01"
                        type="number" />
                </div>

                <div x-show="skema === 'bagi_hasil'">
                    <x-form-input :value="old('persentase_bagi_hasil')" label="Persentase Bagi Hasil (%)" name="persentase_bagi_hasil"
                        step="0.01" type="number" />
                </div>

                <x-form-input :value="old('tanggal_mulai', date('Y-m-d'))" label="Tanggal Mulai" name="tanggal_mulai" required type="date" />

                <p class="text-xs text-ink-muted">Catatan: kalau lahan yang dipilih sudah punya kesepakatan aktif
                    sebelumnya, kesepakatan lama akan otomatis ditandai berakhir dan digantikan yang baru ini.</p>

                <div class="flex justify-end space-x-2 pt-2">
                    <a class="rounded-lg bg-bg px-4 py-2 text-sm text-ink hover:bg-line"
                        href="{{ url()->previous() }}">Batal</a>
                    <button class="rounded-lg bg-primary px-4 py-2 text-sm text-white hover:bg-primary/90"
                        type="submit">Simpan</button>
                </div>
            </form>
        </x-form-card>
    </div>
</x-app-layout>
