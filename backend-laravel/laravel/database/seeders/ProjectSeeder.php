<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::create([
            'name' => 'Website Redesign',
            'owner_id' => 1
        ]);

        Project::create([
            'name' => 'Mobile App',
            'owner_id' => 1
        ]);

        Project::create([
            'name' => 'Internal Tools',
            'owner_id' => 1
        ]);
    }
}
