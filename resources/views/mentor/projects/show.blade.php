@extends('layouts.app')
@section('title', $project->title)
@section('content')
@php $isAr = app()->getLocale() === 'ar'; @endphp
<div class="card mb-3"><div class="card-body">
    <div><strong>{{ $isAr ? 'رائد الأعمال' : 'Entrepreneur' }}:</strong> {{ optional($project->entrepreneur)->name }}</div>
    <div><strong>{{ $isAr ? 'الحالة' : 'Status' }}:</strong> <span class="badge bg-blue-lt">{{ $project->status }}</span></div>
</div></div>

<div class="card mb-3">
    <div class="card-header">{{ $isAr ? 'مراحل الاحتضان (9 مراحل)' : '9 Incubation Phases' }}</div>
    <div class="card-body">
        <ul class="nav nav-tabs mb-3">
            @foreach($project->stages->sortBy('stage_order') as $stage)
                <li class="nav-item">
                    <a class="nav-link @if($activeStageOrder === $stage->stage_order) active @endif" href="{{ route('mentor.projects.show', [$project, 'stage' => $stage->stage_order]) }}">
                        {{ $isAr ? 'المرحلة' : 'Phase' }} {{ $stage->stage_order }}
                    </a>
                </li>
            @endforeach
        </ul>

        @php $activeStage = $project->stages->firstWhere('stage_order', $activeStageOrder) ?: $project->stages->sortBy('stage_order')->first(); @endphp
        @if($activeStage)
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div>
                    <h5 class="mb-1">{{ $activeStage->name }}</h5>
                    <x-status-badge :status="$activeStage->status" />
                </div>
            </div>

            <div class="border rounded p-3 mb-3">
                <h5 class="mb-3">{{ $isAr ? 'إضافة مهمة جديدة' : 'Add New Task' }}</h5>
                <form method="post" action="{{ route('mentor.projects.tasks.store', [$project, $activeStage]) }}" class="row g-2">
                    @csrf
                    <div class="col-md-4"><input class="form-control" name="title" placeholder="{{ $isAr ? 'عنوان المهمة' : 'Task title' }}" required></div>
                    <div class="col-md-4"><input class="form-control" name="description" placeholder="{{ $isAr ? 'وصف المهمة' : 'Task description' }}" required></div>
                    <div class="col-md-2"><input class="form-control" type="date" name="due_date"></div>
                    <div class="col-md-2"><button class="btn btn-primary w-100">{{ $isAr ? 'إضافة' : 'Add' }}</button></div>
                    <div class="col-12"><textarea class="form-control" name="mentor_comments" rows="2" placeholder="{{ $isAr ? 'تعليق الموجه' : 'Mentor comment' }}"></textarea></div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>{{ $isAr ? 'المهمة' : 'Task' }}</th>
                        <th>{{ $isAr ? 'الوصف' : 'Description' }}</th>
                        <th>{{ $isAr ? 'الحالة' : 'Status' }}</th>
                        <th>{{ $isAr ? 'تعليق الموجه' : 'Mentor Comment' }}</th>
                        <th>{{ $isAr ? 'تحديث' : 'Update' }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($activeStage->tasks as $task)
                        <tr>
                            <td>{{ $task->title }}</td>
                            <td>{{ $task->description }}</td>
                            <td><x-status-badge :status="$task->status" /></td>
                            <td>
                                <form method="post" action="{{ route('mentor.projects.tasks.comment.update', [$project, $activeStage, $task]) }}" class="d-flex gap-2">
                                    @csrf @method('PATCH')
                                    <input class="form-control form-control-sm" name="mentor_comments" value="{{ $task->mentor_comments }}" placeholder="{{ $isAr ? 'تعليق' : 'Comment' }}">
                                    <button class="btn btn-sm btn-outline-primary">{{ $isAr ? 'حفظ' : 'Save' }}</button>
                                </form>
                            </td>
                            <td>
                                <form method="post" action="{{ route('mentor.projects.tasks.status.update', [$project, $activeStage, $task]) }}" class="d-flex gap-2">
                                    @csrf @method('PATCH')
                                    <select class="form-select form-select-sm" name="status">
                                        <option value="not_started" @if($task->status==='not_started') selected @endif>{{ $isAr ? 'تم الإنشاء' : 'Created' }}</option>
                                        <option value="approved" @if($task->status==='approved') selected @endif>{{ $isAr ? 'منجز' : 'Done' }}</option>
                                    </select>
                                    <button class="btn btn-sm btn-primary">{{ $isAr ? 'تحديث' : 'Update' }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">{{ $isAr ? 'لا توجد مهام في هذه المرحلة.' : 'No tasks in this phase.' }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @foreach($activeStage->tasks as $task)
                @foreach($task->submissions as $submission)
                    <div class="mt-2 p-2 bg-light rounded">
                        <div>{{ $isAr ? 'تسليم رقم' : 'Submission #' }}{{ $submission->id }} - {{ $submission->status }}</div>
                        <div>{{ $submission->notes }}</div>
                        <form method="post" action="{{ route('mentor.submissions.evaluate', $submission) }}" class="d-flex gap-2 mt-2">
                            @csrf
                            <select class="form-select form-select-sm" name="decision">
                                <option value="approved">{{ $isAr ? 'اعتماد' : 'Approve' }}</option>
                                <option value="changes_requested">{{ $isAr ? 'طلب تعديلات' : 'Request changes' }}</option>
                            </select>
                            <input class="form-control form-control-sm" name="comments" placeholder="{{ $isAr ? 'ملاحظات' : 'Feedback' }}">
                            <button class="btn btn-sm btn-primary">{{ $isAr ? 'إرسال' : 'Submit' }}</button>
                        </form>
                    </div>
                @endforeach
            @endforeach
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header">{{ $isAr ? 'الخط الزمني للنشاط' : 'Activity Timeline' }}</div>
    <div class="list-group list-group-flush">
        @forelse($project->activityLogs as $log)
            <div class="list-group-item">
                <strong>{{ $log->event }}</strong>
                <div class="text-muted small">{{ $log->created_at }}</div>
            </div>
        @empty
            <div class="list-group-item text-muted">{{ $isAr ? 'لا يوجد نشاط حتى الآن.' : 'No activity yet.' }}</div>
        @endforelse
    </div>
</div>
@endsection

