<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\IncubatorRound;
use App\Models\Project;
use App\Models\Sponsor;
use App\Models\User;
use Illuminate\Http\Request;

class IncubatorLifeCycleController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['q']);
        $tab = $request->input('tab', 'rounds');

        $roundsQuery = IncubatorRound::with(['sponsors'])
            ->withCount(['projects', 'sponsors'])
            ->latest();

        if ($request->filled('q')) {
            $term = trim((string) $request->input('q'));
            $roundsQuery->where(function ($q) use ($term) {
                $q->where('name', 'like', '%'.$term.'%')
                    ->orWhere('description', 'like', '%'.$term.'%');
            });
        }

        $rounds = $roundsQuery->paginate(10)->withQueryString();
        $allRounds = IncubatorRound::orderByDesc('start_date')->get(['id', 'name']);
        $roundIds = IncubatorRound::pluck('id');
        $totalProjectsInRounds = Project::whereIn('incubator_round_id', $roundIds)->count();
        $selectedRoundId = $request->filled('round_id') ? (int) $request->input('round_id') : null;
        $openAddProject = $request->boolean('open_add_project');
        $selectedRound = $selectedRoundId ? IncubatorRound::with('sponsors')->find($selectedRoundId) : null;

        $roundProjects = Project::with(['mentor', 'entrepreneur', 'round'])
            ->when($selectedRoundId, function ($q) use ($selectedRoundId) {
                $q->where('incubator_round_id', $selectedRoundId);
            }, function ($q) {
                $q->whereNull('id');
            })
            ->latest()
            ->paginate(10, ['*'], 'projects_page')
            ->withQueryString();

        $stats = [
            'rounds' => IncubatorRound::count(),
            'active_rounds' => IncubatorRound::whereDate('start_date', '<=', now()->toDateString())
                ->whereDate('end_date', '>=', now()->toDateString())
                ->count(),
            'sponsors' => Sponsor::count(),
            'projects' => $totalProjectsInRounds,
        ];

        $mentors = User::whereHas('roles', function ($q) {
            $q->where('name', 'Mentor');
        })->orderBy('name')->get();
        $entrepreneurs = User::whereHas('roles', function ($q) {
            $q->where('name', 'Entrepreneur');
        })->orderBy('name')->get();

        return view('admin.lifecycle.index', compact(
            'rounds',
            'stats',
            'filters',
            'tab',
            'allRounds',
            'openAddProject',
            'selectedRound',
            'roundProjects',
            'mentors',
            'entrepreneurs'
        ));
    }

    public function storeRound(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
            'sponsors' => ['nullable', 'array'],
            'sponsors.*.name' => ['nullable', 'string', 'max:255'],
            'sponsors.*.logo_path' => ['nullable', 'url', 'max:500'],
        ]);

        $round = IncubatorRound::create([
            'name' => $data['name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'description' => $data['description'] ?? null,
        ]);

        $this->syncSponsors($round, $data['sponsors'] ?? []);

        return redirect()->route('admin.lifecycle.index')
            ->with('status', app()->getLocale() === 'ar' ? 'تم إنشاء الجولة.' : 'Round created.');
    }

    public function updateRound(Request $request, IncubatorRound $round)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'description' => ['nullable', 'string'],
            'sponsors' => ['nullable', 'array'],
            'sponsors.*.name' => ['nullable', 'string', 'max:255'],
            'sponsors.*.logo_path' => ['nullable', 'url', 'max:500'],
        ]);

        $round->update([
            'name' => $data['name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'description' => $data['description'] ?? null,
        ]);

        $this->syncSponsors($round, $data['sponsors'] ?? []);

        return redirect()->route('admin.lifecycle.index')
            ->with('status', app()->getLocale() === 'ar' ? 'تم تحديث الجولة.' : 'Round updated.');
    }

    public function destroyRound(IncubatorRound $round)
    {
        Project::where('incubator_round_id', $round->id)->update(['incubator_round_id' => null]);
        $round->delete();

        return redirect()->route('admin.lifecycle.index')
            ->with('status', app()->getLocale() === 'ar' ? 'تم حذف الجولة.' : 'Round deleted.');
    }

    public function storeProject(Request $request, IncubatorRound $round)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', 'in:pending,accepted,rejected,in_progress,completed'],
            'mentor_id' => ['nullable', 'exists:users,id'],
            'entrepreneur_id' => ['required', 'exists:users,id'],
        ]);

        Project::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => $data['status'],
            'mentor_id' => $data['mentor_id'] ?? null,
            'entrepreneur_id' => $data['entrepreneur_id'],
            'incubator_round_id' => $round->id,
            'submitted_at' => now(),
            'started_at' => $data['status'] === 'in_progress' ? now() : null,
            'category' => app()->getLocale() === 'ar' ? 'جولة احتضان' : 'Incubator Round',
        ]);

        return redirect()->route('admin.lifecycle.index', ['tab' => 'projects', 'round_id' => $round->id])
            ->with('status', app()->getLocale() === 'ar' ? 'تمت إضافة المشروع للجولة.' : 'Project added to round.');
    }

    public function destroyProject(IncubatorRound $round, Project $project)
    {
        if ((int) $project->incubator_round_id !== (int) $round->id) {
            abort(404);
        }

        $project->delete();

        return redirect()->route('admin.lifecycle.index', ['tab' => 'projects', 'round_id' => $round->id])
            ->with('status', app()->getLocale() === 'ar' ? 'تم حذف المشروع.' : 'Project deleted.');
    }

    private function syncSponsors(IncubatorRound $round, array $sponsors)
    {
        $sponsorIds = [];
        foreach ($sponsors as $entry) {
            $name = isset($entry['name']) ? trim((string) $entry['name']) : '';
            if ($name === '') {
                continue;
            }
            $logoPath = isset($entry['logo_path']) ? trim((string) $entry['logo_path']) : null;
            $sponsor = Sponsor::firstOrCreate(
                ['name' => $name],
                ['logo_path' => $logoPath ?: null]
            );
            if ($logoPath && $sponsor->logo_path !== $logoPath) {
                $sponsor->update(['logo_path' => $logoPath]);
            }
            $sponsorIds[] = $sponsor->id;
        }

        $round->sponsors()->sync($sponsorIds);
    }
}

