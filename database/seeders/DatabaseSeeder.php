<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function() {
            // create admin
            $admin = config('auth.admin');
            if (User::where('email', $admin['email'])->exists()) return;
            \App\Models\User::forceCreate(array_merge($admin, [
                'name' => 'Admin',
                'email_verified_at' => now(),
                'is_admin' => true,
            ]));
        });

        \App\Models\User::factory(10)->create();
    }
}
