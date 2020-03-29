<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        \App\User::create([
            'name' => "EtchFoda",
            'username' => "etchfoda",
            'email' => 'etchfoda@gmail.com',
            'phone' => '01014539022',
            'password' => bcrypt('260793'),
        ]);
        \App\CoAccount::create([
            'full_name' => "Hisham Account",
            'phone' => "01066869615",
            'password' => "01066869615",
        ]);

        \App\CoAccountSubscription::create([
            'account_id' => 1,
            'max_devices' => 1,
            'start_at' => now()->addDays(-1),
            'expire_at' => now(),
        ]);
    }
}
