@props(['title' => 'Dashboard', 'subtitle' => null, 'preview' => false])
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — Admin Ruang Pustaka</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface font-sans text-ink antialiased">
    <a href="#admin-content" class="skip-link">Lewati ke konten utama</a>
    <div class="min-h-svh lg:grid lg:grid-cols-[248px_1fr]">
        <aside class="hidden border-r border-line bg-white lg:block">
            <div class="sticky top-0 flex min-h-svh flex-col p-5">
                <a href="{{ route('home') }}" class="flex min-h-14 items-center gap-3 font-semibold tracking-tight text-brand-700"><span aria-hidden="true" class="grid h-9 w-9 place-items-center rounded-md bg-brand-700 text-lg text-white">▤</span>Ruang Pustaka</a>
                <p class="mb-4 mt-8 px-3 text-[10px] font-semibold tracking-[0.16em] text-muted uppercase">Administrasi</p>
                <x-admin.navigation :preview="$preview" />
                <div class="mt-auto border-t border-line pt-5"><a href="{{ route('home') }}" class="inline-flex min-h-11 items-center gap-3 px-3 text-sm text-muted hover:text-brand-700"><span aria-hidden="true">↗</span> Lihat website</a><p class="mt-3 px-3 text-[10px] leading-5 text-muted">Ruang Pustaka<br>Prototipe mahasiswa · ITS</p></div>
            </div>
        </aside>
        <div class="min-w-0">
            <header class="border-b border-line bg-white px-5 sm:px-8">
                <div class="flex min-h-20 items-center justify-between gap-4">
                    <a href="{{ route('home') }}" class="font-semibold text-brand-700 lg:hidden">Ruang Pustaka</a>
                    <span class="hidden text-sm text-muted lg:block">Area administrasi</span>
                    <div class="flex items-center gap-3 sm:gap-5">
                        <span class="max-w-36 truncate text-sm font-medium sm:max-w-64">{{ $preview ? 'Akun contoh' : auth()->user()->name }}</span>
                        @if (! $preview)
                            <form method="POST" action="{{ route('logout') }}">@csrf <x-button type="submit" variant="secondary">Keluar</x-button></form>
                        @else
                            <span class="rounded-md bg-brand-50 px-3 py-1.5 text-xs font-medium text-brand-700">Pratinjau</span>
                        @endif
                    </div>
                </div>
                <details class="border-t border-line py-3 lg:hidden">
                    <summary class="min-h-11 cursor-pointer py-3 text-sm font-medium text-brand-700">Menu administrasi</summary>
                    <x-admin.navigation :preview="$preview" class="pb-2" />
                    <a href="{{ route('home') }}" class="inline-flex min-h-11 items-center px-3 text-sm text-muted">Lihat website ↗</a>
                </details>
            </header>
            <main id="admin-content" tabindex="-1" class="mx-auto max-w-7xl px-5 py-8 sm:px-8 sm:py-10">
                @if ($preview)<x-alert variant="info" class="mb-6">Pratinjau kerangka admin. Data dan tindakan pada contoh komponen tidak tersimpan.</x-alert>@endif
                <div class="mb-8 flex flex-wrap items-center justify-between gap-5">
                    <div><p class="text-xs text-muted">Administrasi / {{ $title }}</p><h1 class="mt-3 text-2xl font-semibold tracking-tight sm:text-3xl">{{ $title }}</h1>@if ($subtitle)<p class="mt-3 max-w-2xl text-sm leading-6 text-muted">{{ $subtitle }}</p>@endif</div>
                    @isset($actions)<div class="flex flex-wrap gap-3">{{ $actions }}</div>@endisset
                </div>
                <x-flash-messages />
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
