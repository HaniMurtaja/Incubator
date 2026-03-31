<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Stage;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RolesAndDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $mentorRole = Role::firstOrCreate(['name' => 'Mentor']);
        $entrepreneurRole = Role::firstOrCreate(['name' => 'Entrepreneur']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@inculab.test'],
            ['name' => 'Admin', 'password' => Hash::make('password123')]
        );
        $admin->syncRoles([$adminRole]);

        $mentor = User::firstOrCreate(
            ['email' => 'mentor@inculab.test'],
            ['name' => 'Mentor', 'password' => Hash::make('password123')]
        );
        $mentor->syncRoles([$mentorRole]);

        $entrepreneur = User::firstOrCreate(
            ['email' => 'entrepreneur@inculab.test'],
            ['name' => 'Entrepreneur', 'password' => Hash::make('password123')]
        );
        $entrepreneur->syncRoles([$entrepreneurRole]);

        $project = Project::firstOrCreate(
            ['title' => 'Demo Startup Idea'],
            [
                'entrepreneur_id' => $entrepreneur->id,
                'mentor_id' => $mentor->id,
                'description' => 'A demo project to showcase the incubation workflow.',
                'category' => 'Demo',
                'status' => 'in_progress',
                'submitted_at' => now(),
                'decided_at' => now(),
                'started_at' => now(),
            ]
        );

        $stage1 = Stage::firstOrCreate(
            ['project_id' => $project->id, 'stage_order' => 1],
            ['name' => 'Idea Validation', 'status' => 'in_progress', 'started_at' => now()]
        );

        Task::firstOrCreate(
            ['stage_id' => $stage1->id, 'title' => 'Describe the problem'],
            ['created_by' => $mentor->id, 'description' => 'Write 1-2 pages describing the problem and target users.', 'due_date' => now()->addWeek(), 'status' => 'not_started']
        );
    }
}
