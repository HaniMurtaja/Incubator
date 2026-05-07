@extends('layouts.app')
@section('title', app()->getLocale()==='ar' ? 'مركز قيادة الموجه' : 'Mentor Command Center')
@section('content')
@php $isAr = app()->getLocale() === 'ar'; @endphp
<div class="card mb-3">
    <div class="card-body">
        <form method="get" class="row g-2 align-items-end">
            <div class="col-md-6">
                <label class="form-label">{{ app()->getLocale()==='ar' ? 'اختر المشروع' : 'Select Project' }}</label>
                <select class="form-select" name="project_id" onchange="this.form.submit()">
                    @foreach($projects as $p)
                        <option value="{{ $p->id }}" @if($project && $project->id === $p->id) selected @endif>{{ $p->title }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>

@if(!$project)
    <x-empty-state title="No assigned projects." />
@else
    <div class="card mb-3">
        <div class="card-body">
            <h3 class="mb-1">{{ $project->title }}</h3>
            <p class="text-muted mb-0">{{ app()->getLocale()==='ar' ? 'إدارة المراحل التسع للمشروع' : 'Manage the 9 incubation phases' }}</p>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3">
        @foreach($stages as $stage)
            <li class="nav-item">
                <a class="nav-link @if($activeStageOrder === $stage->stage_order) active @endif" href="{{ route('mentor.command.index', ['project_id' => $project->id, 'stage' => $stage->stage_order]) }}">
                    {{ $isAr ? 'المرحلة' : 'Phase' }} {{ $stage->stage_order }}
                </a>
            </li>
        @endforeach
    </ul>

    @php $activeStage = $stages->firstWhere('stage_order', $activeStageOrder); @endphp
    @if($activeStage)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div>
                        <h4 class="mb-1">{{ $activeStage->name }}</h4>
                        <x-status-badge :status="$activeStage->status" />
                    </div>
                    <form method="post" action="{{ route('mentor.command.stages.update', $activeStage) }}" class="d-flex gap-2">
                        @csrf @method('patch')
                        <select class="form-select form-select-sm" name="status">
                            @foreach(['not_started','in_progress','completed'] as $status)
                                <option value="{{ $status }}" @if($activeStage->status===$status) selected @endif>{{ str_replace('_',' ', $status) }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-sm btn-primary">{{ $isAr ? 'حفظ' : 'Save' }}</button>
                    </form>
                </div>

                <div class="border rounded p-3 mb-3">
                    <h5 class="mb-3">{{ $isAr ? 'إضافة مهمة جديدة' : 'Add New Task' }}</h5>
                    <form method="post" action="{{ route('mentor.command.tasks.store', $activeStage) }}" class="row g-2">
                        @csrf
                        <div class="col-md-4"><input class="form-control" name="title" placeholder="{{ $isAr ? 'عنوان المهمة' : 'Task title' }}" required></div>
                        <div class="col-md-4"><input class="form-control" name="description" placeholder="{{ $isAr ? 'وصف المهمة' : 'Task description' }}" required></div>
                        <div class="col-md-2"><input class="form-control" type="date" name="due_date"></div>
                        <div class="col-md-2"><button class="btn btn-primary w-100">{{ $isAr ? 'إضافة' : 'Add' }}</button></div>
                        <div class="col-12"><textarea class="form-control" name="mentor_comments" rows="2" placeholder="{{ $isAr ? 'تعليق الموجه' : 'Mentor comment' }}"></textarea></div>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead>
                        <tr>
                            <th>{{ $isAr ? 'المهمة' : 'Task' }}</th>
                            <th>{{ $isAr ? 'الحالة' : 'Status' }}</th>
                            <th>{{ $isAr ? 'تعليقات الموجه' : 'Mentor Comments' }}</th>
                            <th>{{ $isAr ? 'الإجراء' : 'Action' }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($activeStage->tasks as $task)
                            <tr>
                                <td>{{ $task->title }}</td>
                                <td><x-status-badge :status="$task->status" /></td>
                                <td style="min-width: 260px;">
                                    <form method="post" action="{{ route('mentor.command.tasks.comment.update', [$activeStage, $task]) }}" class="d-flex gap-2">
                                        @csrf @method('PATCH')
                                        <input class="form-control form-control-sm" name="mentor_comments" value="{{ $task->mentor_comments }}" placeholder="{{ $isAr ? 'أضف تعليقاً' : 'Add comment' }}">
                                        <button class="btn btn-sm btn-outline-primary">{{ $isAr ? 'حفظ' : 'Save' }}</button>
                                    </form>
                                </td>
                                <td>-</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">{{ $isAr ? 'لا توجد مهام.' : 'No tasks yet.' }}</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endif
@endsection

