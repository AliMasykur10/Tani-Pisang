<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-ink">Edit Nominal Kesepakatan</h2>
    </x-slot>

    <div class="py-12">
        <x-form-card>
            <div class="mb-4 text-sm text-ink-muted">
                <p>Lahan: {{ $partnerAgreement->lahan->nama }}</p>
                <p>Partner: {{ $partnerAgreement->partner->nama }}</p>
                <p>Skema: {{ $partnerAgreement->skema === 'sewa' ? 'Sewa' : 'Bagi Hasil' }}</p>
            </div>

            <form action="{{ route('partner-agreements.update', $partnerAgreement) }}" class="space-y-4" method="POST">
                @csrf
                @method('PUT')

                @if ($partnerAgreement->skema === 'sewa')
                    <x-form-input :value="old('nominal_sewa', $partnerAgreement->nominal_sewa)" label="Nominal Sewa (Rp)" name="nominal_sewa" step="0.01"
                        type="number" />
                @else
                    <x-form-input :value="old('persentase_bagi_hasil', $partnerAgreement->persentase_bagi_hasil)" label="Persentase Bagi Hasil (%)" name="persentase_bagi_hasil"
                        step="0.01" type="number" />
                @endif

                <p class="text-xs text-ink-muted">Kalau skema kesepakatan berubah total, buat kesepakatan baru lewat
                    halaman Partner, bukan edit di sini — supaya riwayat kesepakatan lama tetap tersimpan.</p>

                <div class="flex justify-end space-x-2 pt-2">
                    <a class="rounded-lg bg-bg px-4 py-2 text-sm text-ink hover:bg-line"
                        href="{{ route('partners.show', $partnerAgreement->partner) }}">Batal</a>
                    <button class="rounded-lg bg-primary px-4 py-2 text-sm text-white hover:bg-primary/90"
                        type="submit">Update</button>
                </div>
            </form>
        </x-form-card>
    </div>
</x-app-layout>
