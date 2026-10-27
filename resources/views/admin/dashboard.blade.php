<x-layouts.admin title="Dashboard" subtitle="Pantau koleksi dan peminjaman perpustakaan hari ini." :preview="$preview ?? false">
    <x-slot:actions><a href="{{ route('home') }}" class="inline-flex min-h-11 items-center gap-3 rounded-md border border-line bg-white px-4 text-sm font-medium text-brand-700 hover:bg-brand-50">Lihat website <span aria-hidden="true">↗</span></a></x-slot:actions>
    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card label="Judul aktif" :value="$metrics['titles'] ?? null" description="Jumlah judul buku yang aktif dalam koleksi." />
        <x-stat-card label="Total eksemplar" :value="$metrics['copies'] ?? null" :description="isset($metrics) ? number_format($metrics['available'], 0, ',', '.').' eksemplar tersedia dari koleksi aktif.' : 'Stok total dan tersedia untuk buku aktif.'" />
        <x-stat-card label="Pinjaman aktif" :value="$metrics['active'] ?? null" description="Belum dikembalikan, termasuk yang terlambat." />
        <x-stat-card label="Terlambat" :value="$metrics['overdue'] ?? null" :attention="($metrics['overdue'] ?? 0) > 0" description="Belum kembali dan jatuh tempo sebelum hari ini." />
    </div>
    @isset($asOf)<p class="mt-4 text-xs leading-6 text-muted">Ringkasan saat halaman dibuka · {{ $asOf->format('d/m/Y H:i') }} ({{ config('app.timezone') }}). Muat ulang untuk melihat perubahan terbaru.</p>@endisset

    <section class="mt-10" aria-labelledby="overdue-title">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3"><div><h2 id="overdue-title" class="text-lg font-semibold tracking-tight">Perlu ditindaklanjuti</h2><p class="mt-2 text-sm text-muted">Lima pinjaman dengan jatuh tempo paling lama terlewati.</p></div>@if (Route::has('admin.loans.index'))<a href="{{ route('admin.loans.index') }}" class="inline-flex min-h-11 items-center text-sm font-semibold text-brand-700 hover:underline">Semua peminjaman →</a>@endif</div>
        @if ($overdueLoans->isNotEmpty())
            <x-table :headers="['Anggota', 'Buku', 'Jatuh tempo', 'Keterlambatan']" caption="Pinjaman terlambat yang perlu ditindaklanjuti">
                @foreach ($overdueLoans as $loan)
                    <tr><td><span class="block font-medium">{{ $loan->user->name }}</span><span class="mt-1 block text-xs text-muted">{{ $loan->user->nrp_nim }}</span></td><td class="max-w-xs"><span class="font-medium">{{ $loan->book->title }}</span></td><td class="whitespace-nowrap">{{ $loan->due_at->format('d/m/Y') }}</td><td class="whitespace-nowrap font-medium text-amber-800">{{ (int) $loan->due_at->diffInDays(today()) }} hari</td></tr>
                @endforeach
            </x-table>
            <p class="mt-3 text-xs leading-6 text-muted">Menampilkan {{ $overdueLoans->count() }} dari {{ $metrics['overdue'] }} pinjaman terlambat. Pinjaman yang belum kembali tetap tercatat meskipun anggota atau bukunya dinonaktifkan.</p>
        @else
            <x-empty-state :title="($preview ?? false) ? 'Pratinjau daftar keterlambatan' : 'Tidak ada pinjaman terlambat'" :description="($preview ?? false) ? 'Daftar ini akan menampilkan pinjaman yang membutuhkan tindak lanjut. Buka area admin untuk melihat data perpustakaan.' : 'Pinjaman yang melewati tanggal jatuh tempo dan belum dikembalikan akan muncul di sini.'" />
        @endif
    </section>

    <section class="mt-10" aria-labelledby="management-title">
        <h2 id="management-title" class="text-lg font-semibold tracking-tight">Pengelolaan perpustakaan</h2>
        <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([['Buku', 'admin.books.index', 'Kelola koleksi dan ketersediaan buku.'], ['Kategori', 'admin.categories.index', 'Atur pengelompokan koleksi.'], ['Anggota', 'admin.members.index', 'Lihat dan perbarui data anggota.'], ['Peminjaman', 'admin.loans.index', 'Catat peminjaman dan pengembalian.']] as [$label, $route, $description])
                <div class="rounded-lg border border-line bg-white p-5"><h3 class="font-medium">{{ $label }}</h3><p class="mt-2 text-xs leading-6 text-muted">{{ $description }}</p>@if (Route::has($route))<a href="{{ route($route) }}" class="mt-4 inline-flex min-h-11 items-center text-sm font-semibold text-brand-700 hover:underline">Buka {{ strtolower($label) }} →</a>@else<span class="mt-4 inline-flex min-h-11 items-center text-xs text-slate-500">Belum tersedia</span>@endif</div>
            @endforeach
        </div>
    </section>
    @if ($preview ?? false)<section class="mt-10" aria-labelledby="component-examples"><h2 id="component-examples" class="mb-5 text-lg font-semibold">Contoh komponen pengelolaan</h2>@include('partials.admin-component-examples')</section>@endif
</x-layouts.admin>
