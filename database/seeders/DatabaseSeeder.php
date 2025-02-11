<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Organization;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Organization::factory(10)->create();
        Department::factory(50)->create();
        User::factory(10)->create();
    }
}
