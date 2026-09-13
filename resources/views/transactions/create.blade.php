<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">Catat Transaksi Baru</h2>
    </x-slot>

    <div class="py-12">
        <x-form-card>
            <form action="{{ route('transactions.store') }}" class="space-y-4" method="POST">
                @csrf

                <x-form-select label="Lahan" name="lahan_id" required>
                    @foreach ($lahans as $lahan)
                        <option {{ old('lahan_id') == $lahan->id ? 'selected' : '' }} value="{{ $lahan->id }}">
                            {{ $lahan->nama }}
                        </option>
                    @endforeach
                </x-form-select>

                <x-form-select label="Jenis" name="jenis" required>
                    <option {{ old('jenis') == 'pengeluaran' ? 'selected' : '' }} value="pengeluaran">Pengeluaran
                    </option>
                    <option {{ old('jenis') == 'pemasukan' ? 'selected' : '' }} value="pemasukan">Pemasukan</option>
                </x-form-select>

                <x-form-input :value="old('kategori')" label="Kategori" name="kategori"
                    placeholder="mis. pupuk, tenaga kerja, sewa lahan, penjualan panen" required />

                <x-form-input :value="old('jumlah')" label="Jumlah (Rp)" name="jumlah" required step="0.01"
                    type="number" />

                <x-form-input :value="old('tanggal', date('Y-m-d'))" label="Tanggal" name="tanggal" required type="date" />

                <div class="flex items-center">
                    <input name="is_cash" type="hidden" value="0">
                    <input {{ old('is_cash', '1') == '1' ? 'checked' : '' }}
                        class="rounded border-line text-primary focus:ring-primary" id="is_cash" name="is_cash"
                        type="checkbox" value="1">
                    <label class="ml-2 text-sm text-ink" for="is_cash">Transaksi kas (uang benar-benar
                        keluar/masuk)</label>
                </div>
                <p class="-mt-2 text-xs text-ink-muted">Uncheck kalau ini transaksi non-kas, misal bibit hibah atau
                    anakan yang dipindah ke lahan lain dengan nilai estimasi.</p>

                <x-form-textarea :value="old('keterangan')" label="Keterangan" name="keterangan" />

                <div class="flex justify-end space-x-2 pt-2">
                    <a class="rounded-lg bg-bg px-4 py-2 text-sm text-ink hover:bg-line"
                        href="{{ route('transactions.index') }}">Batal</a>
                    <button class="rounded-lg bg-primary px-4 py-2 text-sm text-white hover:bg-primary/90"
                        type="submit">Simpan</button>
                </div>
            </form>
        </x-form-card>
    </div>
</x-app-layout>
