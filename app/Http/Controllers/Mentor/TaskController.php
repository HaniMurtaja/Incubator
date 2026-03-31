<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Stage;
use App\Models\Task;
use App\Services\ProjectLifecycleService;
use App\Services\SubmissionEvaluationService;
use App\Support\Statuses\StageStatus;
use App\Support\Statuses\TaskStatus;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'status']);
        $query = Task::with(['stage.project', 'submissions'])
            ->whereHas('stage.project', function ($query) {
                $query->where('mentor_id', auth()->id());
            });

        if ($request->filled('q')) {
            $term = trim($request->input('q'));
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', '%'.$term.'%')
                    ->orWhere('description', 'like', '%'.$term.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $tasks = $query->latest()->paginate(15)->withQueryString();

        return view('mentor.tasks.index', compact('tasks', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stages = Stage::whereHas('project', function ($query) {
            $query->where('mentor_id', auth()->id());
        })->orderBy('stage_order')->get();

        return view('mentor.tasks.create', compact('stages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        StoreTaskRequest $request,
        SubmissionEvaluationService $submissionEvaluationService,
        ProjectLifecycleService $projectLifecycleService
    )
    {
        $data = $request->validated();
        $stage = Stage::with('project')->findOrFail($data['stage_id']);
        abort_unless((int) $stage->project->mentor_id === (int) auth()->id(), 403);
        abort_unless($projectLifecycleService->canStartStage($stage), 422, 'Complete previous stage first.');

        $task = Task::create($data + [
            'created_by' => auth()->id(),
            'status' => TaskStatus::NOT_STARTED,
        ]);

        if ($stage->status === StageStatus::NOT_STARTED) {
            $stage->update(['status' => StageStatus::IN_PROGRESS, 'started_at' => now()]);
        }

        $submissionEvaluationService->notifyTaskAssigned($task);

        return redirect()->route('mentor.tasks.index')->with('status', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        abort_unless((int) $task->stage->project->mentor_id === (int) auth()->id(), 403);
        $task->load('submissions.files', 'submissions.evaluation');

        return view('mentor.tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        abort_unless((int) $task->stage->project->mentor_id === (int) auth()->id(), 403);
        $stages = Stage::whereHas('project', function ($query) {
            $query->where('mentor_id', auth()->id());
        })->orderBy('stage_order')->get();

        return view('mentor.tasks.edit', compact('task', 'stages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTaskRequest $request, Task $task)
    {
        abort_unless((int) $task->stage->project->mentor_id === (int) auth()->id(), 403);
        $task->update($request->validated());

        return redirect()->route('mentor.tasks.index')->with('status', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        abort_unless((int) $task->stage->project->mentor_id === (int) auth()->id(), 403);
        $task->delete();

        return redirect()->route('mentor.tasks.index')->with('status', 'Task deleted successfully.');
    }
}
