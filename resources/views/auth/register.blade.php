<x-layouts.auth title="Daftar anggota" heading="Daftar anggota" description="Isi data diri Anda untuk membuat akun Ruang Pustaka.">
    <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
        @csrf
        <x-form-field name="name" label="Nama lengkap" :required="true">
            <x-input name="name" :value="old('name')" autocomplete="name" required />
        </x-form-field>
        <x-form-field name="nrp_nim" label="NRP/NIM" :required="true" hint="Nomor induk mahasiswa, tanpa spasi.">
            <x-input name="nrp_nim" :value="old('nrp_nim')" autocomplete="off" required />
        </x-form-field>
        <x-form-field name="email" label="Email" :required="true">
            <x-input name="email" type="email" :value="old('email')" autocomplete="email" required />
        </x-form-field>
        <x-form-field name="password" label="Kata sandi" :required="true" hint="Minimal 8 karakter.">
            <x-input name="password" type="password" autocomplete="new-password" minlength="8" required />
        </x-form-field>
        <x-form-field name="password_confirmation" label="Konfirmasi kata sandi" :required="true">
            <x-input name="password_confirmation" type="password" autocomplete="new-password" required />
        </x-form-field>
        <x-button type="submit" class="w-full">Daftar</x-button>
    </form>
    <p class="border-t border-line pt-5 text-sm text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="font-medium text-brand-700 hover:underline">Masuk di sini</a></p>
</x-layouts.auth>
