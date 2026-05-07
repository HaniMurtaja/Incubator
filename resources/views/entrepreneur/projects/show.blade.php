@extends('layouts.app')
@section('title', $project->title)
@section('content')
@php $isAr = app()->getLocale() === 'ar'; @endphp
<div class="card mb-3"><div class="card-body">
    <div><strong>{{ $isAr ? 'الحالة' : 'Status' }}:</strong> <span class="badge bg-blue-lt">{{ $project->status }}</span></div>
    <div><strong>{{ $isAr ? 'الموجه' : 'Mentor' }}:</strong> {{ optional($project->mentor)->name ?? '-' }}</div>
    <p class="mt-2">{{ $project->description }}</p>
</div></div>

<div class="card mb-3">
    <div class="card-header">{{ $isAr ? 'الجدول الزمني للاحتضان' : 'Incubation Timeline' }}</div>
    <div class="card-body">
        <ul class="nav nav-tabs mb-3">
            @foreach($project->stages as $stage)
                <li class="nav-item">
                    <a class="nav-link @if(request('stage', 1) == $stage->stage_order) active @endif" href="{{ route('entrepreneur.projects.show', [$project, 'stage' => $stage->stage_order]) }}">
                        {{ $isAr ? 'المرحلة' : 'Phase' }} {{ $stage->stage_order }}
                    </a>
                </li>
            @endforeach
        </ul>

        @php $activeStage = $project->stages->firstWhere('stage_order', (int) request('stage', 1)) ?: $project->stages->first(); @endphp
        @if($activeStage)
            <h5>{{ $activeStage->name }} <span class="badge bg-azure-lt">{{ $activeStage->status }}</span></h5>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ $isAr ? 'المهمة' : 'Task' }}</th>
                            <th>{{ $isAr ? 'الوصف' : 'Description' }}</th>
                            <th>{{ $isAr ? 'تعليق الموجه' : 'Mentor Comment' }}</th>
                            <th>{{ $isAr ? 'الحالة' : 'Status' }}</th>
                            <th>{{ $isAr ? 'تحديث الحالة' : 'Update Status' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($activeStage->tasks as $task)
                        <tr>
                            <td>{{ $task->title }}</td>
                            <td>{{ $task->description }}</td>
                            <td>{{ $task->mentor_comments ?: '-' }}</td>
                            <td><x-status-badge :status="$task->status" /></td>
                            <td>
                                <form method="post" action="{{ route('entrepreneur.projects.tasks.status.update', [$project, $task]) }}" class="d-flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select class="form-select form-select-sm" name="status">
                                        <option value="in_progress" @if($task->status==='in_progress') selected @endif>{{ $isAr ? 'قيد التنفيذ' : 'In progress' }}</option>
                                        <option value="submitted" @if($task->status==='submitted') selected @endif>{{ $isAr ? 'تم التسليم' : 'Delivered' }}</option>
                                    </select>
                                    <button class="btn btn-sm btn-primary">{{ $isAr ? 'حفظ' : 'Save' }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">{{ $isAr ? 'لا توجد مهام في هذه المرحلة.' : 'No tasks in this phase.' }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">{{ $isAr ? 'سجل النشاط' : 'Activity Log' }}</div>
    <div class="list-group list-group-flush">
        @forelse($project->activityLogs as $log)
            <div class="list-group-item">{{ $log->event }} <small class="text-muted">{{ $log->created_at }}</small></div>
        @empty
            <div class="list-group-item text-muted">{{ $isAr ? 'لا يوجد نشاط حتى الآن.' : 'No activity yet.' }}</div>
        @endforelse
    </div>
</div>
@endsection

