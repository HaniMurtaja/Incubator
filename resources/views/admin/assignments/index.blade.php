@extends('layouts.app')
@section('title', app()->getLocale()==='ar' ? 'سجل التعيينات' : 'Assignments Log')
@section('content')
<div class="card mb-3">
    <div class="card-header">{{ app()->getLocale()==='ar' ? 'إضافة سجل تعيين' : 'Add Assignment Log' }}</div>
    <div class="card-body">
        <form method="post" action="{{ route('admin.assignments.store') }}" class="row g-2">
            @csrf
            <div class="col-md-2"><input class="form-control" type="date" name="assignment_date" required></div>
            <div class="col-md-3">
                <select class="form-select" name="project_id" required>
                    <option value="">{{ app()->getLocale()==='ar'?'المشروع':'Project' }}</option>
                    @foreach($projects as $project)<option value="{{ $project->id }}">{{ $project->title }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="business_mentor_id" required>
                    <option value="">{{ app()->getLocale()==='ar'?'الموجه':'Business Mentor' }}</option>
                    @foreach($mentors as $mentor)<option value="{{ $mentor->id }}">{{ $mentor->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="entrepreneur_id" required>
                    <option value="">{{ app()->getLocale()==='ar'?'رائد الأعمال':'Entrepreneur' }}</option>
                    @foreach($entrepreneurs as $entrepreneur)<option value="{{ $entrepreneur->id }}">{{ $entrepreneur->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-1"><button class="btn btn-primary w-100">+</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>Date</th><th>Project</th><th>Business Mentor</th><th>Entrepreneur</th><th></th></tr></thead>
            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ optional($log->assignment_date)->format('Y-m-d') }}</td>
                    <td>{{ optional($log->project)->title }}</td>
                    <td>{{ optional($log->mentor)->name }}</td>
                    <td>{{ optional($log->entrepreneur)->name }}</td>
                    <td>
                        <form method="post" action="{{ route('admin.assignments.destroy', $log->id) }}">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-muted py-4">No assignment logs</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $logs->links() }}</div>
@endsection

