<?php

namespace App\Http\Controllers\Entrepreneur;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskSubmissionRequest;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\TaskSubmissionFile;
use App\Services\ProjectLifecycleService;
use App\Support\Statuses\StageStatus;
use App\Support\Statuses\SubmissionStatus;
use App\Support\Statuses\TaskStatus;
use Illuminate\Http\Request;

class TaskSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'status']);
        $query = TaskSubmission::with(['task.stage.project', 'evaluation'])
            ->where('submitted_by', auth()->id());

        if ($request->filled('q')) {
            $term = trim($request->input('q'));
            $query->whereHas('task', function ($q) use ($term) {
                $q->where('title', 'like', '%'.$term.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $submissions = $query->latest()->paginate(15)->withQueryString();

        return view('entrepreneur.submissions.index', compact('submissions', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ProjectLifecycleService $projectLifecycleService)
    {
        $tasks = Task::with('stage.project')->whereHas('stage.project', function ($query) {
            $query->where('entrepreneur_id', auth()->id());
        })->orderBy('due_date')->get()->filter(function ($task) use ($projectLifecycleService) {
            return $projectLifecycleService->canStartStage($task->stage)
                && in_array($task->stage->status, [StageStatus::NOT_STARTED, StageStatus::IN_PROGRESS], true)
                && in_array($task->status, [TaskStatus::NOT_STARTED, TaskStatus::IN_PROGRESS, TaskStatus::CHANGES_REQUESTED], true);
        });

        return view('entrepreneur.submissions.create', compact('tasks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTaskSubmissionRequest $request, ProjectLifecycleService $projectLifecycleService)
    {
        $data = $request->validated();
        $task = Task::with('stage.project')->findOrFail($data['task_id']);
        abort_unless((int) $task->stage->project->entrepreneur_id === (int) auth()->id(), 403);
        abort_unless($projectLifecycleService->canStartStage($task->stage), 422, 'Complete previous stage first.');

        $submission = TaskSubmission::create([
            'task_id' => $task->id,
            'submitted_by' => auth()->id(),
            'notes' => isset($data['notes']) ? $data['notes'] : null,
            'status' => SubmissionStatus::SUBMITTED,
            'submitted_at' => now(),
        ]);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                TaskSubmissionFile::create([
                    'task_submission_id' => $submission->id,
                    'path' => $file->store('submissions/'.$submission->id, 'local'),
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        $task->update(['status' => TaskStatus::SUBMITTED]);

        return redirect()->route('entrepreneur.submissions.index')->with('status', 'Task submitted successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(TaskSubmission $submission)
    {
        abort_unless((int) $submission->submitted_by === (int) auth()->id(), 403);
        $submission->load(['task.stage.project', 'files', 'evaluation']);

        return view('entrepreneur.submissions.show', compact('submission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($request, $id)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        abort(404);
    }
}
