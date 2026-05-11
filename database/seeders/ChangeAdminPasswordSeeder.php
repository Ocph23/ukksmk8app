<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ChangeAdminPasswordSeeder extends Seeder
{
    /**
     * Update password admin, atau buat user-nya jika belum ada.
     */
    public function run(): void
    {
        $email = env('SEED_ADMIN_EMAIL', 'admin@ukk.smkn8tikjayapura.sch.id');
        $password = env('SEED_ADMIN_PASSWORD', 'smk8sangatbisa');

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->update([
                'password' => Hash::make($password),
            ]);

            return;
        }

        User::create([
            'name' => $email,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
    }
}
