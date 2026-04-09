<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('Mentor')) {
            return redirect()->route('mentor.dashboard');
        }

        return redirect()->route('entrepreneur.dashboard');
    }

    public function admin()
    {
        $statusCounts = Project::query()
            ->select('status', DB::raw('count(*) as c'))
            ->groupBy('status')
            ->pluck('c', 'status')
            ->toArray();

        $categoryRows = Project::query()
            ->select('category', DB::raw('count(*) as c'))
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->groupBy('category')
            ->orderByDesc('c')
            ->limit(8)
            ->get();

        $tasksByStatus = Task::query()
            ->select('status', DB::raw('count(*) as c'))
            ->groupBy('status')
            ->pluck('c', 'status')
            ->toArray();

        $projectsLast7 = Project::query()
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->select(DB::raw('DATE(created_at) as d'), DB::raw('count(*) as c'))
            ->groupBy('d')
            ->orderBy('d')
            ->pluck('c', 'd')
            ->toArray();

        $dayLabels = [];
        $daySeries = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = now()->subDays($i)->format('Y-m-d');
            $dayLabels[] = now()->subDays($i)->format('M j');
            $daySeries[] = (int) ($projectsLast7[$d] ?? 0);
        }

        $stats = [
            'users' => User::count(),
            'mentors' => User::whereHas('roles', function ($q) {
                $q->where('name', 'Mentor');
            })->count(),
            'entrepreneurs' => User::whereHas('roles', function ($q) {
                $q->where('name', 'Entrepreneur');
            })->count(),
            'projects' => Project::count(),
            'pending_projects' => Project::where('status', 'pending')->count(),
            'in_progress_projects' => Project::where('status', 'in_progress')->count(),
            'completed_projects' => Project::where('status', 'completed')->count(),
            'accepted_projects' => Project::where('status', 'accepted')->count(),
            'rejected_projects' => Project::where('status', 'rejected')->count(),
            'tasks_total' => Task::count(),
            'submissions_pending' => TaskSubmission::where('status', 'submitted')->count(),
            'submissions_approved' => TaskSubmission::where('status', 'approved')->count(),
        ];

        return view('dashboards.admin', compact(
            'stats',
            'statusCounts',
            'categoryRows',
            'tasksByStatus',
            'dayLabels',
            'daySeries'
        ));
    }

    public function mentor(Request $request)
    {
        $stats = [
            'assigned_projects' => Project::where('mentor_id', $request->user()->id)->count(),
            'pending_submissions' => TaskSubmission::whereHas('task.stage.project', function ($q) use ($request) {
                $q->where('mentor_id', $request->user()->id);
            })->where('status', 'submitted')->count(),
        ];

        return view('dashboards.mentor', compact('stats'));
    }

    public function entrepreneur(Request $request)
    {
        $stats = [
            'my_projects' => Project::where('entrepreneur_id', $request->user()->id)->count(),
            'completed_projects' => Project::where('entrepreneur_id', $request->user()->id)->where('status', 'completed')->count(),
        ];

        return view('dashboards.entrepreneur', compact('stats'));
    }
}

