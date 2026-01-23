<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run all seeders in order
        $this->call([
            RolePermissionSeeder::class,
            TicketCategorySeeder::class,
            TicketStatusSeeder::class,
            TicketPrioritySeeder::class,
            UserSeeder::class,
            TicketSeeder::class,
        ]);

        // these are test users with roles
    }
}
