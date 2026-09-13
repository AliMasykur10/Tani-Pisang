<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">Catat Panen Baru</h2>
    </x-slot>

    <div class="py-12">
        <x-form-card>
            <form action="{{ route('panen-cycles.store') }}" class="space-y-6" method="POST" x-data="{ adaAnakan: false }">
                @csrf

                <div>
                    <h3 class="mb-3 font-medium text-ink">Data Panen</h3>
                    <div class="space-y-4">
                        <x-form-select label="Lahan" name="lahan_id" required>
                            @foreach ($lahans as $lahan)
                                <option {{ old('lahan_id') == $lahan->id ? 'selected' : '' }}
                                    value="{{ $lahan->id }}">
                                    {{ $lahan->nama }}
                                </option>
                            @endforeach
                        </x-form-select>

                        <div class="grid grid-cols-2 gap-4">
                            <x-form-input :value="old('nomor_siklus', 1)" label="Nomor Siklus" min="1" name="nomor_siklus"
                                required type="number" />
                            <x-form-input :value="old('tanggal_panen', date('Y-m-d'))" label="Tanggal Panen" name="tanggal_panen" required
                                type="date" />
                        </div>

                        <x-form-input :value="old('jumlah_pohon_produktif')" label="Jumlah Pohon Produktif Siklus Ini" min="0"
                            name="jumlah_pohon_produktif" required type="number" />

                        <div class="grid grid-cols-2 gap-4">
                            <x-form-input :value="old('total_hasil_kg')" label="Total Hasil (kg)" name="total_hasil_kg" required
                                step="0.1" type="number" />
                            <x-form-input :value="old('harga_per_kg')" label="Harga per Kg (Rp)" name="harga_per_kg" required
                                step="0.01" type="number" />
                        </div>
                        <p class="text-xs text-ink-muted">Total pemasukan dihitung otomatis (hasil kg × harga per kg)
                            dan langsung tercatat sebagai transaksi.</p>
                    </div>
                </div>

                <div class="border-t border-line pt-6">
                    <label class="mb-3 flex items-center">
                        <input class="mr-2 rounded border-line text-primary focus:ring-primary" type="checkbox"
                            x-model="adaAnakan">
                        <span class="font-medium text-ink">Ada data anakan untuk siklus ini</span>
                    </label>

                    <div class="space-y-4 border-l-2 border-primary-tint pl-3" x-show="adaAnakan">
                        <div class="grid grid-cols-2 gap-4">
                            <x-form-input :value="old('jumlah_muncul')" label="Jumlah Anakan Muncul" min="0"
                                name="jumlah_muncul" type="number" />
                            <x-form-input :value="old('jumlah_disisakan')" label="Disisakan (siklus berikutnya)" min="0"
                                name="jumlah_disisakan" type="number" />
                        </div>

                        <div class="space-y-2 rounded-lg bg-bg p-3">
                            <p class="text-sm font-medium text-ink">Dijual sebagai bibit</p>
                            <div class="grid grid-cols-2 gap-4">
                                <x-form-input :value="old('jumlah_dijual')" label="Jumlah Dijual" min="0"
                                    name="jumlah_dijual" type="number" />
                                <x-form-input :value="old('harga_jual_per_batang')" label="Harga per Batang (Rp)"
                                    name="harga_jual_per_batang" step="0.01" type="number" />
                            </div>
                        </div>

                        <div class="space-y-2 rounded-lg bg-bg p-3">
                            <p class="text-sm font-medium text-ink">Dipindah ke lahan lain</p>
                            <div class="grid grid-cols-2 gap-4">
                                <x-form-input :value="old('jumlah_dipindah_lahan_lain')" label="Jumlah Dipindah" min="0"
                                    name="jumlah_dipindah_lahan_lain" type="number" />
                                <x-form-input :value="old('nilai_estimasi_per_batang')" label="Nilai Estimasi per Batang (Rp)"
                                    name="nilai_estimasi_per_batang" step="0.01" type="number" />
                            </div>
                            <x-form-select label="Lahan Tujuan" name="lahan_tujuan_id"
                                placeholder="- Pilih lahan tujuan -">
                                @foreach ($lahans as $lahan)
                                    <option {{ old('lahan_tujuan_id') == $lahan->id ? 'selected' : '' }}
                                        value="{{ $lahan->id }}">
                                        {{ $lahan->nama }}
                                    </option>
                                @endforeach
                            </x-form-select>
                            <p class="text-xs text-ink-muted">Ini akan tercatat sebagai transaksi non-kas (nilai
                                estimasi) di lahan tujuan.</p>
                        </div>

                        <x-form-input :value="old('jumlah_dibuang')" label="Jumlah Dibuang / Jadi Pupuk" min="0"
                            name="jumlah_dibuang" type="number" />
                    </div>
                </div>

                <div class="flex justify-end space-x-2 border-t border-line pt-6">
                    <a class="rounded-lg bg-bg px-4 py-2 text-sm text-ink hover:bg-line"
                        href="{{ route('panen-cycles.index') }}">Batal</a>
                    <button class="rounded-lg bg-primary px-4 py-2 text-sm text-white hover:bg-primary/90"
                        type="submit">Simpan</button>
                </div>
            </form>
        </x-form-card>
    </div>
</x-app-layout>
