<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStageRequest;
use App\Models\Project;
use App\Models\Stage;
use App\Support\Statuses\StageStatus;
use Illuminate\Http\Request;

class StageTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'project_id']);
        $projectsQuery = Project::with('stages')->latest();

        if ($request->filled('q')) {
            $term = trim($request->input('q'));
            $projectsQuery->where('title', 'like', '%'.$term.'%');
        }

        if ($request->filled('project_id')) {
            $projectsQuery->where('id', $request->input('project_id'));
        }

        $projects = $projectsQuery->paginate(10)->withQueryString();
        $projectOptions = Project::select('id', 'title')->orderBy('title')->get();

        return view('admin.stages.index', compact('projects', 'projectOptions', 'filters'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $projects = Project::orderBy('title')->get();
        return view('admin.stages.create', compact('projects'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreStageRequest $request)
    {
        Stage::create($request->validated() + ['status' => StageStatus::NOT_STARTED]);
        return redirect()->route('admin.stages.index')->with('status', 'Stage created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Stage $stage)
    {
        return view('admin.stages.show', compact('stage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Stage $stage)
    {
        $projects = Project::orderBy('title')->get();
        return view('admin.stages.edit', compact('stage', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreStageRequest $request, Stage $stage)
    {
        $stage->update($request->validated());
        return redirect()->route('admin.stages.index')->with('status', 'Stage updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stage $stage)
    {
        $stage->delete();
        return redirect()->route('admin.stages.index')->with('status', 'Stage deleted successfully.');
    }
}
