@extends('layouts.app')
@section('title', $project->title)
@section('content')
@php $isAr = app()->getLocale() === 'ar'; @endphp
<style>
.phase-tabs-wrap {
    overflow-x: auto;
    white-space: nowrap;
}
.phase-tab {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 90px;
    min-height: 36px;
    border-radius: 9px;
    font-weight: 600;
    font-size: .76rem;
    padding: .3rem .5rem;
    color: #fff;
    text-decoration: none;
    margin-inline-end: .3rem;
    border: 2px solid transparent;
}
.phase-tab.not_started { background: #dc2626; }
.phase-tab.in_progress { background: #2563eb; }
.phase-tab.completed { background: #16a34a; }
.phase-tab.active {
    border-color: #0f172a;
    box-shadow: 0 .4rem 1rem rgba(2, 6, 23, .2);
}
</style>
<div class="card mb-3"><div class="card-body">
    <div><strong>{{ $isAr ? 'الحالة' : 'Status' }}:</strong> <span class="badge bg-blue-lt">{{ $project->status }}</span></div>
    <div><strong>{{ $isAr ? 'الموجه' : 'Mentor' }}:</strong> {{ optional($project->mentor)->name ?? '-' }}</div>
    <p class="mt-2">{{ $project->description }}</p>
</div></div>

<div class="card mb-3">
    <div class="card-header">{{ $isAr ? 'الجدول الزمني للاحتضان' : 'Incubation Timeline' }}</div>
    <div class="card-body">
        <div class="phase-tabs-wrap mb-3">
            @foreach($project->stages->sortBy('stage_order') as $stage)
                @php
                    $phaseColorClass = $stage->status === 'completed' ? 'completed' : ($stage->status === 'in_progress' ? 'in_progress' : 'not_started');
                @endphp
                <a class="phase-tab {{ $phaseColorClass }} @if(request('stage', 1) == $stage->stage_order) active @endif"
                   href="{{ route('entrepreneur.projects.show', [$project, 'stage' => $stage->stage_order]) }}">
                    <span>{{ $isAr ? 'المرحلة' : 'Phase' }} {{ $stage->stage_order }} - {{ $stage->name }}</span>
                </a>
            @endforeach
        </div>

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
                                <form method="post" action="{{ route('entrepreneur.projects.tasks.submit', [$project, $task]) }}" class="mt-2" enctype="multipart/form-data">
                                    @csrf
                                    <textarea class="form-control form-control-sm mb-2" name="notes" rows="2" placeholder="{{ $isAr ? 'ملاحظات التسليم' : 'Submission notes' }}"></textarea>
                                    <input class="form-control form-control-sm mb-2" type="file" name="files[]" multiple>
                                    <button class="btn btn-sm btn-outline-primary">{{ $isAr ? 'رفع/تسليم' : 'Upload/Submit' }}</button>
                                </form>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="5">
                                <div class="bg-light rounded p-2">
                                    <div class="small fw-bold mb-2">{{ $isAr ? 'محادثة المهمة' : 'Task Chat' }}</div>
                                    @forelse($task->messages as $message)
                                        <div class="small mb-1">
                                            <strong>{{ optional($message->user)->name }}:</strong> {{ $message->message }}
                                            <span class="text-muted">({{ $message->created_at }})</span>
                                        </div>
                                    @empty
                                        <div class="small text-muted">{{ $isAr ? 'لا توجد رسائل بعد.' : 'No messages yet.' }}</div>
                                    @endforelse
                                    <form method="post" action="{{ route('entrepreneur.projects.tasks.messages.store', [$project, $task]) }}" class="d-flex gap-2 mt-2">
                                        @csrf
                                        <input class="form-control form-control-sm" name="message" placeholder="{{ $isAr ? 'اكتب رسالة للموجه' : 'Write a message to mentor' }}" required>
                                        <button class="btn btn-sm btn-primary">{{ $isAr ? 'إرسال' : 'Send' }}</button>
                                    </form>
                                </div>
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

