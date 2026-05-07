<?php

namespace App\Http\Controllers\Entrepreneur;

use App\Http\Controllers\Controller;
use App\Models\IncubatorRound;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class RoundController extends Controller
{
    public function index(Request $request)
    {
        $entrepreneurId = $request->user()->id;
        $rounds = IncubatorRound::with(['projects' => function ($q) use ($request) {
            $q->with(['entrepreneur', 'mentor'])->where('entrepreneur_id', $request->user()->id)->latest();
        }])->whereHas('projects', function ($q) use ($request) {
            $q->where('entrepreneur_id', $request->user()->id);
        })->orderByDesc('start_date')->paginate(10);

        $stats = [
            'current_rounds' => IncubatorRound::whereHas('projects', function ($q) use ($entrepreneurId) {
                $q->where('entrepreneur_id', $entrepreneurId);
            })->count(),
            'projects' => Project::where('entrepreneur_id', $entrepreneurId)->count(),
            'tasks_submitted' => Task::whereHas('stage.project', function ($q) use ($entrepreneurId) {
                $q->where('entrepreneur_id', $entrepreneurId);
            })->whereHas('submissions', function ($q) use ($entrepreneurId) {
                $q->where('submitted_by', $entrepreneurId);
            })->count(),
        ];

        return view('entrepreneur.rounds.index', compact('rounds', 'stats'));
    }
}

