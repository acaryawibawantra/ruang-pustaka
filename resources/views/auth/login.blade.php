<x-layouts.auth title="Masuk" heading="Masuk sebagai mahasiswa" description="Gunakan email dan kata sandi akun anggota Anda untuk melanjutkan.">
    <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
        @csrf
        <x-form-field name="email" label="Email" :required="true">
            <x-input name="email" type="email" :value="old('email')" autocomplete="email" autofocus required />
        </x-form-field>
        <x-form-field name="password" label="Kata sandi" :required="true">
            <x-input name="password" type="password" autocomplete="current-password" required />
        </x-form-field>
        <div class="flex items-center justify-between gap-4">
            <label class="flex items-center gap-2 text-sm text-muted"><input name="remember" type="checkbox" value="1" class="h-4 w-4 rounded border-slate-300 accent-brand-700">Ingat saya</label>
            <a href="{{ route('register') }}" class="text-sm font-medium text-brand-700 hover:underline">Daftar anggota</a>
        </div>
        <x-button type="submit" class="w-full">Masuk</x-button>
    </form>
    <p class="border-t border-line pt-5 text-sm text-muted">Petugas perpustakaan? <a href="{{ route('login.staff') }}" class="font-medium text-brand-700 hover:underline">Masuk lewat halaman petugas</a></p>
</x-layouts.auth>
