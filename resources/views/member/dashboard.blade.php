<x-layouts.public title="Dashboard anggota">
    <div class="mx-auto max-w-4xl">
        <p class="text-xs font-semibold tracking-[0.14em] text-brand-700 uppercase">Area anggota</p>
        <h1 class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">Halo, {{ auth()->user()->name }}</h1>
        <p class="mt-3 text-sm leading-7 text-muted">Pantau buku yang sedang Anda pinjam dan tenggat pengembaliannya.</p>

        <div class="mt-8 grid gap-4 sm:grid-cols-3">
            <x-stat-card label="Buku dipinjam" :value="$metrics['active']" description="Belum dikembalikan, termasuk yang terlambat." />
            <x-stat-card label="Perlu perhatian" :value="$metrics['overdue'] + $metrics['approaching']" :attention="($metrics['overdue'] + $metrics['approaching']) > 0" description="Terlambat atau jatuh tempo dalam 3 hari ke depan." />
            <x-stat-card label="Riwayat selesai" :value="$metrics['returned_total']" description="Total pinjaman yang sudah dikembalikan." />
        </div>

        <section class="mt-10" aria-labelledby="pinjaman-aktif">
            <div class="mb-5">
                <h2 id="pinjaman-aktif" class="text-lg font-semibold tracking-tight">Pinjaman aktif</h2>
                <p class="mt-2 text-sm leading-6 text-muted">Diurutkan dari tenggat paling dekat. Kembalikan buku sebelum tanggal jatuh tempo untuk menghindari keterlambatan.</p>
            </div>

            @if ($activeLoans->isEmpty())
                <x-empty-state title="Tidak ada pinjaman aktif" description="Saat ini tidak ada buku yang sedang Anda pinjam.">
                    <a href="{{ route('catalog.index') }}" class="inline-flex min-h-11 items-center rounded-md bg-brand-700 px-5 text-sm font-semibold text-white hover:bg-brand-600">Jelajahi katalog</a>
                </x-empty-state>
            @else
                <ul class="space-y-4">
                    @foreach ($activeLoans as $loan)
                        <li class="rounded-lg border border-line bg-white p-5 sm:p-6">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="font-semibold leading-snug">{{ $loan->book->title }}</h3>
                                    <p class="mt-1 text-sm text-muted">{{ $loan->book->author }}</p>
                                    @if ($loan->book->category)<p class="mt-1 text-xs text-brand-700">{{ $loan->book->category->name }}</p>@endif
                                </div>
                                <x-badge :variant="$loan->urgency_status">{{ $loan->urgency_label }}</x-badge>
                            </div>
                            <dl class="mt-5 grid gap-4 border-t border-line pt-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-xs text-muted">Jatuh tempo</dt>
                                    <dd class="mt-1 font-medium tabular-nums">{{ $loan->due_at->format('d/m/Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs text-muted">Tanggal pinjam</dt>
                                    <dd class="mt-1 font-medium tabular-nums">{{ $loan->borrowed_at->format('d/m/Y') }}</dd>
                                </div>
                            </dl>
                            <a href="{{ route('member.loans.show', $loan) }}" class="mt-4 inline-flex min-h-11 items-center text-sm font-semibold text-brand-700 hover:underline">Detail pinjaman →</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        <section class="mt-10" aria-labelledby="riwayat-terbaru">
            <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
                <div>
                    <h2 id="riwayat-terbaru" class="text-lg font-semibold tracking-tight">Baru dikembalikan</h2>
                    <p class="mt-2 text-sm leading-6 text-muted">Lima pengembalian terakhir Anda.</p>
                </div>
                <a href="{{ route('member.loans.index') }}" class="inline-flex min-h-11 items-center text-sm font-semibold text-brand-700 hover:underline">Semua pinjaman →</a>
            </div>

            @if ($recentReturnedLoans->isEmpty())
                <x-empty-state title="Belum ada buku yang dikembalikan" description="Riwayat pengembalian akan tampil di sini setelah Anda mengembalikan buku." />
            @else
                <x-table :headers="['Buku', 'Dipinjam', 'Dikembalikan']" caption="Lima pinjaman yang baru dikembalikan">
                    @foreach ($recentReturnedLoans as $loan)
                        <tr>
                            <td><span class="block font-medium">{{ $loan->book->title }}</span><span class="mt-1 block text-xs text-muted">{{ $loan->book->author }}</span></td>
                            <td class="whitespace-nowrap tabular-nums">{{ $loan->borrowed_at->format('d/m/Y') }}</td>
                            <td class="whitespace-nowrap"><x-badge variant="returned">Dikembalikan</x-badge> <span class="tabular-nums text-muted">{{ $loan->returned_at->format('d/m/Y') }}</span></td>
                        </tr>
                    @endforeach
                </x-table>
            @endif
        </section>
    </div>
</x-layouts.public>
