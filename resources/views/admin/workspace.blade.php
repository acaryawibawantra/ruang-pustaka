<x-layouts.admin title="Dashboard" subtitle="Kelola koleksi dan layanan peminjaman dari satu tempat." :preview="$preview ?? false">
    <x-slot:actions><a href="{{ route('home') }}" class="inline-flex min-h-11 items-center gap-3 rounded-md border border-line bg-white px-4 text-sm font-medium text-brand-700 hover:bg-brand-50">Lihat website <span aria-hidden="true">↗</span></a></x-slot:actions>
    <x-empty-state title="Area administrasi siap dikembangkan" description="Ringkasan koleksi dan peminjaman akan tersedia setelah dashboard terhubung ke data perpustakaan." />
    @if ($preview ?? false)
        <section class="mt-10" aria-labelledby="component-examples"><h2 id="component-examples" class="mb-5 text-lg font-semibold">Contoh komponen pengelolaan</h2>@include('partials.admin-component-examples')</section>
    @endif
</x-layouts.admin>
