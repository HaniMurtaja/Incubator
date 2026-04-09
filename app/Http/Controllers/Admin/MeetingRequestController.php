<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MeetingRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class MeetingRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requests = MeetingRequest::with(['project', 'mentor', 'entrepreneur'])->latest('requested_for')->paginate(15);
        $projects = Project::orderBy('title')->get();
        $mentors = User::whereHas('roles', function ($q) {
            $q->where('name', 'Mentor');
        })->orderBy('name')->get();
        $entrepreneurs = User::whereHas('roles', function ($q) {
            $q->where('name', 'Entrepreneur');
        })->orderBy('name')->get();

        return view('admin.meetings.index', compact('requests', 'projects', 'mentors', 'entrepreneurs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return redirect()->route('admin.meetings.index');
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
            'project_id' => ['nullable', 'exists:projects,id'],
            'mentor_id' => ['required', 'exists:users,id'],
            'entrepreneur_id' => ['required', 'exists:users,id'],
            'requested_for' => ['required', 'date'],
            'status' => ['required', 'in:requested,approved,rejected,done'],
            'agenda' => ['nullable', 'string'],
        ]);

        MeetingRequest::create($data);
        return back()->with('status', 'Meeting request added.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('admin.meetings.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return redirect()->route('admin.meetings.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'status' => ['required', 'in:requested,approved,rejected,done'],
            'agenda' => ['nullable', 'string'],
            'requested_for' => ['required', 'date'],
        ]);

        MeetingRequest::findOrFail($id)->update($data);
        return back()->with('status', 'Meeting request updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MeetingRequest::findOrFail($id)->delete();
        return back()->with('status', 'Meeting request deleted.');
    }
}
