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
        Organization::factory(5)->create();
        //get all organizations
        $organizations = Organization::all();
        $organizations->each(function ($organization) {
            Department::factory(5)->create(['organization_id' => $organization->id]);
        });
        User::factory(50)->create();
    }
}
