<x-layouts.public title="Detail pinjaman">
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('member.loans.index') }}" class="text-sm font-semibold text-brand-700 hover:underline">← Kembali ke riwayat</a>
        <h1 class="mt-6 text-3xl font-semibold tracking-tight">Detail pinjaman</h1>
        <dl class="mt-8 divide-y divide-line rounded-lg border border-line">
            <div class="flex justify-between gap-4 px-5 py-4"><dt class="text-muted">Buku</dt><dd class="text-right font-medium">{{ $loan->book->title }}</dd></div>
            <div class="flex justify-between gap-4 px-5 py-4"><dt class="text-muted">Tanggal pinjam</dt><dd class="text-right">{{ $loan->borrowed_at->format('d/m/Y') }}</dd></div>
            <div class="flex justify-between gap-4 px-5 py-4"><dt class="text-muted">Jatuh tempo</dt><dd class="text-right">{{ $loan->due_at->format('d/m/Y') }}</dd></div>
            <div class="flex justify-between gap-4 px-5 py-4"><dt class="text-muted">Status</dt><dd class="text-right">{{ $loan->status === \App\Models\Loan::STATUS_RETURNED ? 'Dikembalikan' : ($loan->is_overdue ? 'Terlambat' : 'Dipinjam') }}</dd></div>
            @if ($loan->returned_at)<div class="flex justify-between gap-4 px-5 py-4"><dt class="text-muted">Tanggal kembali</dt><dd class="text-right">{{ $loan->returned_at->format('d/m/Y') }}</dd></div>@endif
        </dl>
    </div>
</x-layouts.public>
