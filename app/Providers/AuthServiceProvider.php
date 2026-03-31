<?php

namespace App\Providers;

use App\Models\Evaluation;
use App\Models\Project;
use App\Models\Stage;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Policies\EvaluationPolicy;
use App\Policies\ProjectPolicy;
use App\Policies\StagePolicy;
use App\Policies\TaskPolicy;
use App\Policies\TaskSubmissionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Project::class => ProjectPolicy::class,
        Stage::class => StagePolicy::class,
        Task::class => TaskPolicy::class,
        TaskSubmission::class => TaskSubmissionPolicy::class,
        Evaluation::class => EvaluationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
