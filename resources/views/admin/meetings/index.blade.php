@extends('layouts.app')
@section('title', app()->getLocale()==='ar' ? 'طلبات الاجتماعات' : 'Meeting Requests')
@section('content')
<div class="card mb-3">
    <div class="card-header">{{ app()->getLocale()==='ar' ? 'إضافة طلب اجتماع' : 'Add Meeting Request' }}</div>
    <div class="card-body">
        <form method="post" action="{{ route('admin.meetings.store') }}" class="row g-2">
            @csrf
            <div class="col-md-3">
                <select class="form-select" name="project_id">
                    <option value="">Project (optional)</option>
                    @foreach($projects as $project)<option value="{{ $project->id }}">{{ $project->title }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-2"><input class="form-control" type="datetime-local" name="requested_for" required></div>
            <div class="col-md-2">
                <select class="form-select" name="mentor_id" required>
                    <option value="">Mentor</option>
                    @foreach($mentors as $mentor)<option value="{{ $mentor->id }}">{{ $mentor->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="entrepreneur_id" required>
                    <option value="">Entrepreneur</option>
                    @foreach($entrepreneurs as $entrepreneur)<option value="{{ $entrepreneur->id }}">{{ $entrepreneur->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="status" required>
                    <option value="requested">requested</option>
                    <option value="approved">approved</option>
                    <option value="rejected">rejected</option>
                    <option value="done">done</option>
                </select>
            </div>
            <div class="col-md-1"><button class="btn btn-primary w-100">+</button></div>
            <div class="col-12"><input class="form-control" name="agenda" placeholder="Agenda"></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>Date</th><th>Project</th><th>Mentor</th><th>Entrepreneur</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($requests as $meeting)
                <tr>
                    <td>{{ optional($meeting->requested_for)->format('Y-m-d H:i') }}</td>
                    <td>{{ optional($meeting->project)->title }}</td>
                    <td>{{ optional($meeting->mentor)->name }}</td>
                    <td>{{ optional($meeting->entrepreneur)->name }}</td>
                    <td><x-status-badge :status="$meeting->status" /></td>
                    <td class="d-flex gap-2">
                        <form method="post" action="{{ route('admin.meetings.update', $meeting->id) }}" class="d-flex gap-2">
                            @csrf @method('put')
                            <input type="hidden" name="requested_for" value="{{ optional($meeting->requested_for)->format('Y-m-d H:i:s') }}">
                            <input type="hidden" name="agenda" value="{{ $meeting->agenda }}">
                            <select class="form-select form-select-sm" name="status">
                                @foreach(['requested','approved','rejected','done'] as $status)
                                    <option value="{{ $status }}" @if($meeting->status===$status) selected @endif>{{ $status }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-outline-primary">Save</button>
                        </form>
                        <form method="post" action="{{ route('admin.meetings.destroy', $meeting->id) }}">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No meeting requests</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $requests->links() }}</div>
@endsection

