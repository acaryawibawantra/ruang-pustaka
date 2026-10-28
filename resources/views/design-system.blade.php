<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Design System — Ruang Pustaka</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface font-sans text-ink antialiased">
    <header class="border-b border-line bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-5 py-5 sm:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-sm font-semibold tracking-tight text-brand-700 sm:text-base">
                <span class="grid h-10 w-10 place-items-center rounded-md bg-brand-700 text-white" aria-hidden="true"><svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 6.5C9.8 4.5 6.7 4 3.5 5v13c3.2-1 6.3-.5 8.5 1.5M12 6.5c2.2-2 5.3-2.5 8.5-1.5v13c-3.2-1-6.3-.5-8.5 1.5M12 6.5v13"/></svg></span>
                <span>Ruang Pustaka<span class="mt-0.5 block text-[10px] font-normal tracking-wide text-muted sm:text-xs">Perpustakaan kampus</span></span>
            </a>
            <span class="text-xs font-medium tracking-wide text-muted sm:text-sm">Panduan desain · v0.1</span>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-5 pb-24 sm:px-8">
        <section class="border-b border-line py-14 sm:py-20">
            <p class="text-xs font-semibold tracking-[0.16em] text-brand-700 uppercase">Fondasi visual</p>
            <div class="mt-5 grid gap-6 md:grid-cols-2 md:items-end">
                <div><h1 class="text-4xl font-semibold tracking-tight sm:text-5xl">Design system</h1><p class="mt-4 text-lg text-muted">Ruang Pustaka</p></div>
                <p class="max-w-lg leading-7 text-muted">Panduan awal untuk membangun pengalaman perpustakaan yang bersih, ramah, dan mudah digunakan. Disusun untuk berkembang bersama kebutuhan tim.</p>
            </div>
        </section>

        <section class="border-b border-line py-14" aria-labelledby="warna">
            <div class="mb-8"><p class="text-xs font-semibold tracking-[0.14em] text-brand-700 uppercase">01 / Fondasi</p><h2 id="warna" class="mt-3 text-2xl font-semibold tracking-tight">Warna</h2><p class="mt-2 text-sm leading-6 text-muted">Biru langit sebagai identitas, dengan warna netral untuk menjaga konten tetap mudah dibaca.</p></div>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                @foreach ([['Brand 700','#075985','bg-brand-700'],['Brand 600','#0284C7','bg-brand-600'],['Brand 500','#0EA5E9','bg-brand-500'],['Brand 100','#E0F2FE','bg-brand-100'],['Brand 50','#F0F9FF','bg-brand-50'],['Ink','#17212B','bg-ink'],['Muted','#52616D','bg-muted'],['Line','#E2E8F0','bg-line'],['Surface','#F8FAFC','bg-surface'],['White','#FFFFFF','bg-white']] as [$name, $hex, $class])
                    <div class="overflow-hidden rounded-lg border border-line bg-white"><div class="h-20 {{ $class }}"></div><div class="px-4 py-3"><p class="text-sm font-semibold">{{ $name }}</p><p class="mt-1 text-xs text-muted">{{ $hex }}</p></div></div>
                @endforeach
            </div>
            <p class="mt-5 text-sm leading-6 text-muted">Gunakan Brand 700 untuk teks dan tombol utama putih agar kontras tetap kuat; Brand 500–600 cocok sebagai aksen.</p>
        </section>

        <section class="grid gap-10 border-b border-line py-14 md:grid-cols-3" aria-labelledby="tipografi">
            <div><p class="text-xs font-semibold tracking-[0.14em] text-brand-700 uppercase">02 / Fondasi</p><h2 id="tipografi" class="mt-3 text-2xl font-semibold tracking-tight">Tipografi</h2><p class="mt-2 text-sm leading-6 text-muted">Work Sans menghadirkan karakter yang jelas dan hangat, dari judul hingga teks pendukung.</p></div>
            <div class="space-y-7 md:col-span-2">
                <div><p class="text-xs text-muted">Hero / 48–56 px / Semibold</p><p class="mt-2 text-4xl font-semibold tracking-tight sm:text-5xl">Ruang untuk menemukan.</p></div>
                <div><p class="text-xs text-muted">Judul bagian / 30–36 px / Semibold</p><p class="mt-2 text-3xl font-semibold tracking-tight">Jelajahi koleksi</p></div>
                <div><p class="text-xs text-muted">Isi / 16 px / Regular</p><p class="mt-2 max-w-xl leading-7">Temukan bacaan yang mendukung perjalanan belajar dan rasa ingin tahu Anda.</p></div>
                <div><p class="text-xs text-muted">Pendukung / 14 px / Regular</p><p class="mt-2 text-sm text-muted">Informasi yang jelas, pada setiap langkah.</p></div>
            </div>
        </section>

        <section class="border-b border-line py-14" aria-labelledby="komponen">
            <div class="mb-8"><p class="text-xs font-semibold tracking-[0.14em] text-brand-700 uppercase">03 / Komponen</p><h2 id="komponen" class="mt-3 text-2xl font-semibold tracking-tight">Komponen dasar</h2><p class="mt-2 text-sm leading-6 text-muted">Elemen awal yang akan disesuaikan saat alur aplikasi dibangun.</p></div>
            <div class="grid gap-5 lg:grid-cols-2">
                <div class="rounded-lg border border-line bg-white p-6 sm:p-8"><h3 class="font-semibold">Tombol</h3><p class="mt-1 text-sm text-muted">Aksi utama, sekunder, dan keadaan nonaktif.</p><div class="mt-6 flex flex-wrap gap-3"><x-button>Jelajahi katalog</x-button><x-button variant="secondary">Selengkapnya</x-button><x-button disabled>Belum tersedia</x-button></div></div>
                <div class="rounded-lg border border-line bg-white p-6 sm:p-8"><h3 class="font-semibold">Kolom pencarian</h3><p class="mt-1 text-sm text-muted">Label tetap terlihat dan fokus mudah dikenali.</p><label for="example-search" class="mt-6 block text-sm font-medium">Cari buku</label><x-input id="example-search" type="search" placeholder="Judul atau penulis" class="mt-2" /><p class="mt-2 text-xs text-muted">Contoh tampilan; pencarian belum terhubung.</p></div>
                <div class="rounded-lg border border-line bg-white p-6 sm:p-8"><h3 class="font-semibold">Status ketersediaan</h3><p class="mt-1 text-sm text-muted">Status selalu disertai teks, bukan warna saja.</p><div class="mt-6 flex flex-wrap gap-3"><x-badge>Tersedia</x-badge><x-badge variant="unavailable">Tidak tersedia</x-badge></div></div>
                <div class="rounded-lg border border-line bg-white p-6 sm:p-8"><h3 class="font-semibold">Navigasi</h3><p class="mt-1 text-sm text-muted">Sederhana dan mudah dipindai.</p><div class="mt-6 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm"><span class="font-semibold text-brand-700 underline decoration-2 underline-offset-8">Beranda</span><span class="text-muted">Katalog</span><span class="text-muted">Tentang</span><span class="font-semibold text-brand-700">Masuk →</span></div></div>
            </div>
        </section>
        <section class="border-b border-line py-14" aria-labelledby="admin-components">
            <div class="mb-8"><p class="text-xs font-semibold tracking-[0.14em] text-brand-700 uppercase">04 / Administrasi</p><h2 id="admin-components" class="mt-3 text-2xl font-semibold tracking-tight">Komponen pengelolaan</h2><p class="mt-2 text-sm leading-6 text-muted">Form, tabel, status tindakan, dan kondisi kosong untuk halaman administrasi.</p></div>
            @include('partials.admin-component-examples')
            @if (app()->environment(['local', 'testing']))<a href="{{ route('design-system.admin') }}" class="mt-8 inline-flex min-h-11 items-center gap-3 text-sm font-semibold text-brand-700 hover:underline">Lihat pratinjau layout admin <span aria-hidden="true">→</span></a>@endif
        </section>
        <section class="grid gap-8 py-14 md:grid-cols-3" aria-labelledby="prinsip"><div><p class="text-xs font-semibold tracking-[0.14em] text-brand-700 uppercase">05 / Penggunaan</p><h2 id="prinsip" class="mt-3 text-2xl font-semibold tracking-tight">Prinsip desain</h2></div><div class="grid gap-6 sm:grid-cols-2 md:col-span-2"><div><h3 class="font-semibold">Jelas dan fungsional</h3><p class="mt-2 text-sm leading-6 text-muted">Utamakan informasi, label yang mudah dipahami, dan tindakan yang terlihat.</p></div><div><h3 class="font-semibold">Rapi di setiap layar</h3><p class="mt-2 text-sm leading-6 text-muted">Gunakan jarak konsisten, area sentuh minimal 44 px, dan tata letak yang beradaptasi.</p></div></div></section>
    </main>
    <footer class="border-t border-line bg-white"><div class="mx-auto flex max-w-6xl flex-col gap-2 px-5 py-6 text-xs text-muted sm:flex-row sm:justify-between sm:px-8"><span>Ruang Pustaka · Panduan desain awal</span><span>Prototipe mahasiswa untuk lingkungan ITS</span></div></footer>
</body>
</html>
