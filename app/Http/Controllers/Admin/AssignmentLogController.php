<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssignmentLog;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class AssignmentLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logs = AssignmentLog::with(['project', 'mentor', 'entrepreneur'])->latest('assignment_date')->paginate(15);
        $projects = Project::orderBy('title')->get();
        $mentors = User::whereHas('roles', function ($q) {
            $q->where('name', 'Mentor');
        })->orderBy('name')->get();
        $entrepreneurs = User::whereHas('roles', function ($q) {
            $q->where('name', 'Entrepreneur');
        })->orderBy('name')->get();

        return view('admin.assignments.index', compact('logs', 'projects', 'mentors', 'entrepreneurs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'assignment_date' => ['required', 'date'],
            'project_id' => ['required', 'exists:projects,id'],
            'business_mentor_id' => ['required', 'exists:users,id'],
            'entrepreneur_id' => ['required', 'exists:users,id'],
            'notes' => ['nullable', 'string'],
        ]);

        AssignmentLog::create($data);

        return back()->with('status', 'Assignment log added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AssignmentLog::findOrFail($id)->delete();
        return back()->with('status', 'Assignment log deleted.');
    }
}
