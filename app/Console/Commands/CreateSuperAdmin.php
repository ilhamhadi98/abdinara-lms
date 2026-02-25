<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateSuperAdmin extends Command
{
    protected $signature = 'make:super-admin
                            {--name=     : Nama super admin}
                            {--email=    : Email super admin}
                            {--password= : Password super admin}';

    protected $description = 'Buat akun super-admin baru dengan cepat';

    public function handle(): int
    {
        $name = $this->option('name') ?: $this->ask('Nama', 'Super Admin');
        $email = $this->option('email') ?: $this->ask('Email', 'superadmin@abdinara.id');
        $password = $this->option('password') ?: $this->secret('Password') ?: 'password';

        // Cek apakah email sudah ada
        if (User::where('email', $email)->exists()) {
            $user = User::where('email', $email)->first();
            $user->password = Hash::make($password);
            $user->save();
            $user->syncRoles(['super-admin']);
            $this->info("✓ User [{$email}] sudah ada. Role super-admin dan Password berhasil diperbarui.");
            return self::SUCCESS;
        }

        $user = User::create([
            'name'              => $name,
            'email'             => $email,
            'password'          => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        $user->assignRole('super-admin');

        $this->newLine();
        $this->info('✓ Super admin berhasil dibuat!');
        $this->table(
            ['Field', 'Value'],
            [
                ['Nama',     $name],
                ['Email',    $email],
                ['Password', $password],
                ['Role',     'super-admin'],
            ]
        );
        $this->newLine();
        $this->comment('Login di: /login');

        return self::SUCCESS;
    }
}
