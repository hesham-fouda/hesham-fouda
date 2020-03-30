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

        \App\CoAccount::query()->insert([
            ['full_name' => "Hisham Account", 'phone' => "01066869615", 'password' => "01066869615",],
            ['full_name' => "Mohamed Fouda", 'phone' => "01060474040", 'password' => "1797",],
            ['full_name' => "test1", 'phone' => "01000000001", 'password' => "1235",],
            ['full_name' => "test2", 'phone' => "01000000002", 'password' => "1234",],
            ['full_name' => "test3", 'phone' => "01000000003", 'password' => "1258",],
            ['full_name' => "test4", 'phone' => "01000000004", 'password' => "1369",],
            ['full_name' => "test5", 'phone' => "01000000005", 'password' => "1478",],
            ['full_name' => "test6", 'phone' => "01000000006", 'password' => "1259",],
            ['full_name' => "test7", 'phone' => "01000000007", 'password' => "8521",],
            ['full_name' => "test8", 'phone' => "01000000008", 'password' => "9514",],
            ['full_name' => "test9", 'phone' => "01000000009", 'password' => "3654",],
            ['full_name' => "test10", 'phone' => "01000000010", 'password' => "5896",],
        ]);

        \App\CoAccountSubscription::query()->insert([
            ['account_id' => 1, 'max_devices' => 100],
            ['account_id' => 2, 'max_devices' => 100],
        ]);

        \App\CoAccountSubscription::query()->insert([
            ['account_id' => 3, 'max_devices' => 1, 'start_at' => now(), 'expire_at' => now()->addDays(1)],
            ['account_id' => 4, 'max_devices' => 1, 'start_at' => now(), 'expire_at' => now()->addDays(1)],
            ['account_id' => 5, 'max_devices' => 1, 'start_at' => now(), 'expire_at' => now()->addDays(1)],
            ['account_id' => 6, 'max_devices' => 1, 'start_at' => now(), 'expire_at' => now()->addDays(1)],
            ['account_id' => 7, 'max_devices' => 1, 'start_at' => now(), 'expire_at' => now()->addDays(1)],
            ['account_id' => 8, 'max_devices' => 1, 'start_at' => now(), 'expire_at' => now()->addDays(1)],
            ['account_id' => 9, 'max_devices' => 1, 'start_at' => now(), 'expire_at' => now()->addDays(1)],
            ['account_id' => 10, 'max_devices' => 1, 'start_at' => now(), 'expire_at' => now()->addDays(1)],
            ['account_id' => 11, 'max_devices' => 1, 'start_at' => now(), 'expire_at' => now()->addDays(1)],
            ['account_id' => 12, 'max_devices' => 1, 'start_at' => now(), 'expire_at' => now()->addDays(1)],
        ]);
    }
}
