<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\IncubatorRound;
use App\Models\Project;
use App\Models\Task;

class RoundController extends Controller
{
    public function index()
    {
        $mentorId = auth()->id();
        $rounds = IncubatorRound::with(['projects' => function ($q) {
            $q->with(['entrepreneur', 'mentor'])->where('mentor_id', auth()->id())->latest();
        }])->whereHas('projects', function ($q) {
            $q->where('mentor_id', auth()->id());
        })->orderByDesc('start_date')->paginate(10);

        $stats = [
            'current_rounds' => IncubatorRound::whereHas('projects', function ($q) use ($mentorId) {
                $q->where('mentor_id', $mentorId);
            })->count(),
            'projects' => Project::where('mentor_id', $mentorId)->count(),
            'tasks_created' => Task::where('created_by', $mentorId)
                ->whereHas('stage.project', function ($q) use ($mentorId) {
                    $q->where('mentor_id', $mentorId);
                })->count(),
        ];

        return view('mentor.rounds.index', compact('rounds', 'stats'));
    }
}

