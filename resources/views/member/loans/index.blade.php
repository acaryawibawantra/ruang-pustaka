<x-layouts.public title="Riwayat pinjaman">
    <div class="mx-auto max-w-4xl">
        <p class="text-sm font-semibold uppercase tracking-wide text-brand-700">Area anggota</p>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight">Riwayat pinjaman</h1>
        <p class="mt-3 text-muted">Daftar pinjaman yang terkait dengan akun Anda.</p>

        @if ($loans->isEmpty())
            <x-empty-state class="mt-8" title="Belum ada riwayat pinjaman" description="Riwayat peminjaman Anda akan tampil di halaman ini." />
        @else
            <div class="mt-8 overflow-x-auto rounded-lg border border-line">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-surface text-muted"><tr><th class="px-4 py-3 font-semibold">Buku</th><th class="px-4 py-3 font-semibold">Dipinjam</th><th class="px-4 py-3 font-semibold">Status</th><th class="px-4 py-3"><span class="sr-only">Detail</span></th></tr></thead>
                    <tbody class="divide-y divide-line">
                        @foreach ($loans as $loan)
                            <tr><td class="px-4 py-4 font-medium">{{ $loan->book->title }}</td><td class="px-4 py-4">{{ $loan->borrowed_at->format('d/m/Y') }}</td><td class="px-4 py-4">{{ $loan->status === \App\Models\Loan::STATUS_RETURNED ? 'Dikembalikan' : 'Dipinjam' }}</td><td class="px-4 py-4 text-right"><a class="font-semibold text-brand-700 hover:underline" href="{{ route('member.loans.show', $loan) }}">Detail</a></td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">{{ $loans->links() }}</div>
        @endif
    </div>
</x-layouts.public>
