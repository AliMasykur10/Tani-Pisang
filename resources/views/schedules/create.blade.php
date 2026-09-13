<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">Tambah Jadwal</h2>
    </x-slot>

    <div class="py-12">
        <x-form-card>
            <form action="{{ route('schedules.store') }}" class="space-y-4" method="POST">
                @csrf

                <x-form-select label="Lahan" name="lahan_id" required>
                    @foreach ($lahans as $lahan)
                        <option {{ old('lahan_id') == $lahan->id ? 'selected' : '' }} value="{{ $lahan->id }}">
                            {{ $lahan->nama }}
                        </option>
                    @endforeach
                </x-form-select>

                <x-form-input :value="old('jenis')" label="Jenis Kegiatan" name="jenis"
                    placeholder="mis. Pemupukan, Semprot Obat, Cek Rutin" required />

                <x-form-select label="Pola Berulang (opsional)" name="recurring_pattern"
                    placeholder="Tidak berulang (sekali saja)">
                    <option {{ old('recurring_pattern') == 'harian' ? 'selected' : '' }} value="harian">Harian</option>
                    <option {{ old('recurring_pattern') == 'mingguan' ? 'selected' : '' }} value="mingguan">Mingguan
                    </option>
                    <option {{ old('recurring_pattern') == 'dua_mingguan' ? 'selected' : '' }} value="dua_mingguan">2
                        Mingguan</option>
                    <option {{ old('recurring_pattern') == 'bulanan' ? 'selected' : '' }} value="bulanan">Bulanan
                    </option>
                </x-form-select>

                <x-form-input :value="old('next_date', date('Y-m-d'))" label="Tanggal Jadwal Berikutnya" name="next_date" required
                    type="date" />

                <div class="flex justify-end space-x-2 pt-2">
                    <a class="rounded-lg bg-bg px-4 py-2 text-sm text-ink hover:bg-line"
                        href="{{ route('schedules.index') }}">Batal</a>
                    <button class="rounded-lg bg-primary px-4 py-2 text-sm text-white hover:bg-primary/90"
                        type="submit">Simpan</button>
                </div>
            </form>
        </x-form-card>
    </div>
</x-app-layout>
