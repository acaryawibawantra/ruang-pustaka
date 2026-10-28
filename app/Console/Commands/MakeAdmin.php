<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakeAdmin extends Command
{
    protected $signature = 'app:make-admin {email : Email akun yang akan dijadikan admin}';

    protected $description = 'Buat atau promosikan akun admin tanpa menyimpan password di repository';

    public function handle(): int
    {
        $email = strtolower(trim($this->argument('email')));
        $user = User::query()->where('email', $email)->first();

        if ($user === null) {
            $user = new User([
                'name' => $this->ask('Nama admin'),
                'nrp_nim' => $this->ask('NRP/NIM admin'),
                'email' => $email,
            ]);
            $user->password = Hash::make($this->secret('Password admin'));
        }

        $user->role = User::ROLE_ADMIN;
        $user->is_active = true;
        $user->save();

        $this->info("Akun {$user->email} sekarang menjadi admin aktif.");

        return self::SUCCESS;
    }
}
