<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Masuk - {{ config('app.name') }}</title>
        <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-slate-100 px-4 py-12 text-slate-900">
        <div class="mx-auto mb-6 flex max-w-md justify-center">
            <a href="{{ route('home') }}" class="flex items-center gap-3 text-sm font-semibold tracking-tight text-brand-700 sm:text-base">
                <span class="grid h-10 w-10 place-items-center rounded-md bg-brand-700 text-white" aria-hidden="true"><svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 6.5C9.8 4.5 6.7 4 3.5 5v13c3.2-1 6.3-.5 8.5 1.5M12 6.5c2.2-2 5.3-2.5 8.5-1.5v13c-3.2-1-6.3-.5-8.5 1.5M12 6.5v13"/></svg></span>
                <span>Ruang Pustaka<span class="mt-0.5 block text-[10px] font-normal tracking-wide text-muted sm:text-xs">Perpustakaan kampus</span></span>
            </a>
        </div>
        <main class="mx-auto max-w-md rounded-xl bg-white p-8 shadow">
            <h1 class="text-2xl font-semibold">Masuk ke Ruang Pustaka</h1>
            <p class="mt-2 text-sm text-slate-600">Gunakan email dan password akun Anda.</p>

            @if ($errors->any())
                <div class="mt-5 rounded-lg bg-red-50 p-3 text-sm text-red-700" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required
                        autocomplete="email" autofocus
                        class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium">Password</label>
                    <input id="password" name="password" type="password" required
                        autocomplete="current-password"
                        class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2">
                </div>
                <label class="flex items-center gap-2 text-sm">
                    <input name="remember" type="checkbox" value="1" class="rounded border-slate-300">
                    Ingat saya
                </label>
                <button type="submit"
                    class="w-full rounded-lg bg-blue-900 px-4 py-2 font-medium text-white hover:bg-blue-800">
                    Masuk
                </button>
            </form>

            <p class="mt-6 text-sm text-slate-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-medium text-blue-900 underline">Daftar sebagai anggota</a>
            </p>
        </main>
    </body>
</html>
