<?php

namespace App\Http\Controllers\Entrepreneur;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use App\Models\ProjectAttachment;
use App\Models\Stage;
use App\Models\Task;
use App\Models\TaskMessage;
use App\Models\TaskSubmission;
use App\Models\TaskSubmissionFile;
use App\Support\Statuses\ProjectStatus;
use App\Support\Statuses\StageStatus;
use App\Support\Statuses\SubmissionStatus;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    protected $defaultPhaseNames = [
        1 => 'Idea Maturation',
        2 => 'Market Analysis',
        3 => 'Audience Study',
        4 => 'Competitor Analysis',
        5 => 'Pricing & Sales',
        6 => 'Team & HR',
        7 => 'Financial Plans',
        8 => 'Operational Plan',
        9 => 'Investment Plan',
    ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'status']);
        $query = Project::with(['mentor', 'stages.tasks'])
            ->where('entrepreneur_id', auth()->id());

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

        $projects = $query->latest()->paginate(10)->withQueryString();

        return view('entrepreneur.projects.index', compact('projects', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('entrepreneur.projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();
        $project = Project::create([
            'entrepreneur_id' => auth()->id(),
            'title' => $data['title'],
            'description' => $data['description'],
            'category' => isset($data['category']) ? $data['category'] : null,
            'status' => ProjectStatus::PENDING,
            'submitted_at' => now(),
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                ProjectAttachment::create([
                    'project_id' => $project->id,
                    'uploaded_by' => auth()->id(),
                    'path' => $file->store('projects/'.$project->id, 'local'),
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('entrepreneur.projects.index')->with('status', 'Project submitted successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        abort_unless((int) $project->entrepreneur_id === (int) auth()->id(), 403);
        $this->ensureNineStages($project->id);
        $project->load(['mentor', 'attachments', 'stages.tasks.submissions.files', 'stages.tasks.messages.user', 'activityLogs.actor']);

        return view('entrepreneur.projects.show', compact('project'));
    }

    public function updateTaskStatus(Request $request, Project $project, \App\Models\Task $task)
    {
        abort_unless((int) $project->entrepreneur_id === (int) auth()->id(), 403);
        abort_unless((int) optional($task->stage)->project_id === (int) $project->id, 404);

        $data = $request->validate([
            'status' => ['required', 'in:in_progress,submitted'],
        ]);

        $task->update(['status' => $data['status']]);

        return back()->with('status', app()->getLocale() === 'ar' ? 'تم تحديث حالة المهمة.' : 'Task status updated.');
    }

    public function submitTaskWork(Request $request, Project $project, Task $task)
    {
        abort_unless((int) $project->entrepreneur_id === (int) auth()->id(), 403);
        abort_unless((int) optional($task->stage)->project_id === (int) $project->id, 404);

        $data = $request->validate([
            'notes' => ['nullable', 'string'],
            'files' => ['nullable', 'array'],
            'files.*' => ['file', 'max:10240'],
        ]);

        $submission = TaskSubmission::create([
            'task_id' => $task->id,
            'submitted_by' => auth()->id(),
            'notes' => $data['notes'] ?? null,
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

        $task->update(['status' => 'submitted']);

        return back()->with('status', app()->getLocale() === 'ar' ? 'تم إرسال التسليم.' : 'Task submission sent.');
    }

    public function sendTaskMessage(Request $request, Project $project, Task $task)
    {
        abort_unless((int) $project->entrepreneur_id === (int) auth()->id(), 403);
        abort_unless((int) optional($task->stage)->project_id === (int) $project->id, 404);

        $data = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        TaskMessage::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'message' => $data['message'],
        ]);

        return back()->with('status', app()->getLocale() === 'ar' ? 'تم إرسال الرسالة.' : 'Message sent.');
    }

    protected function ensureNineStages($projectId)
    {
        foreach ($this->defaultPhaseNames as $order => $name) {
            Stage::firstOrCreate(
                ['project_id' => $projectId, 'stage_order' => $order],
                ['name' => $name, 'status' => StageStatus::NOT_STARTED]
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        abort_unless((int) $project->entrepreneur_id === (int) auth()->id(), 403);
        return view('entrepreneur.projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProjectRequest $request, Project $project)
    {
        abort_unless((int) $project->entrepreneur_id === (int) auth()->id(), 403);
        abort_unless(in_array($project->status, [ProjectStatus::PENDING, ProjectStatus::REJECTED], true), 403);

        $project->update($request->validated());
        return redirect()->route('entrepreneur.projects.index')->with('status', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        abort_unless((int) $project->entrepreneur_id === (int) auth()->id(), 403);
        $project->delete();

        return redirect()->route('entrepreneur.projects.index')->with('status', 'Project deleted successfully.');
    }
}
