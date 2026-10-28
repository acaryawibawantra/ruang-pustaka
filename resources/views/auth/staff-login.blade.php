<x-layouts.auth title="Masuk petugas" heading="Masuk sebagai petugas" description="Halaman ini khusus untuk penjaga perpustakaan yang mengelola koleksi dan peminjaman.">
    <form method="POST" action="{{ route('login.staff.store') }}" class="space-y-5">
        @csrf
        <x-form-field name="email" label="Email petugas" :required="true">
            <x-input name="email" type="email" :value="old('email')" autocomplete="email" autofocus required />
        </x-form-field>
        <x-form-field name="password" label="Kata sandi" :required="true">
            <x-input name="password" type="password" autocomplete="current-password" required />
        </x-form-field>
        <label class="flex items-center gap-2 text-sm"><input name="remember" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300">Ingat saya</label>
        <x-button type="submit" class="w-full">Masuk ke area petugas</x-button>
    </form>
    <p class="border-t border-line pt-5 text-sm text-muted">Mahasiswa? <a href="{{ route('login') }}" class="font-medium text-brand-700 hover:underline">Masuk sebagai mahasiswa</a></p>
</x-layouts.auth>
