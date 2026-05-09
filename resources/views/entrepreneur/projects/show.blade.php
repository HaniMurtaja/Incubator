@extends('layouts.app')

@section('title', $project->title)

@push('styles')
@include('mentor.partials.incubator-project-shell-styles')
@endpush

@section('content')
@php
    $isAr = app()->getLocale() === 'ar';
    $activeStage = $project->stages->firstWhere('stage_order', $activeStageOrder)
        ?: $project->stages->sortBy('stage_order')->first();
    $totalStages = $project->stages->count();
    $doneStages = $project->stages->where('status', 'completed')->count();
    $overallPct = $totalStages > 0 ? round($doneStages / $totalStages * 100) : 0;
    $totalTasks = $activeStage ? $activeStage->tasks->count() : 0;
    $doneTasks = $activeStage ? $activeStage->tasks->whereIn('status', ['submitted', 'approved'])->count() : 0;
    $stagePct = $totalTasks > 0 ? round($doneTasks / $totalTasks * 100) : 0;
@endphp

{{-- Project info bar (mirrors mentor layout; mentor → entrepreneur fields) --}}
<div class="inc-proj-info">
    <div class="inc-info-field">
        <strong>{{ $isAr ? 'الموجه' : 'Mentor' }}:</strong>
        {{ optional($project->mentor)->name ?? '-' }}
    </div>
    <div class="inc-info-field">
        <strong>{{ $isAr ? 'الحالة' : 'Status' }}:</strong>
        <span class="inc-pill pill-{{ $project->status }} ms-1">{{ $project->status }}</span>
    </div>
    @if ($project->description)
        <div class="inc-info-field" style="width:100%;color:var(--txt3);">{{ $project->description }}</div>
    @endif
</div>

{{-- Stage strip --}}
<div class="inc-stage-wrap">
    <div class="inc-strip-header">
        <span class="inc-strip-label">{{ $isAr ? 'مراحل الاحتضان' : 'Incubation stages' }}</span>
        <div class="inc-strip-legend">
            <div class="inc-leg completed"><span class="inc-leg-dot"></span>{{ $isAr ? 'مكتملة' : 'Done' }}</div>
            <div class="inc-leg in_progress"><span class="inc-leg-dot"></span>{{ $isAr ? 'جارية' : 'In progress' }}</div>
            <div class="inc-leg not_started"><span class="inc-leg-dot"></span>{{ $isAr ? 'لم تبدأ' : 'Not started' }}</div>
        </div>
    </div>
    <div class="inc-stages">
        @foreach ($project->stages->sortBy('stage_order') as $stage)
            @php $isActive = (int) $stage->stage_order === (int) $activeStageOrder; @endphp
            <a class="stage-tab {{ $stage->status }} {{ $isActive ? 'active-stage' : '' }}"
               href="{{ route('entrepreneur.projects.show', [$project, 'stage' => $stage->stage_order]) }}">
                <span class="tab-num">{{ str_pad($stage->stage_order, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="tab-name">{{ $stage->name }}</span>
                <span class="tab-dot"></span>
            </a>
        @endforeach
    </div>
</div>

{{-- Two-column layout: tasks | progress (same order as mentor project show) --}}
<div class="inc-cols">

    <div>
        @if ($activeStage)

            <div class="inc-card inc-card-white">
                <div class="inc-card-head">
                    <div>
                        <p class="inc-card-title">
                            {{ $isAr ? 'مهام المرحلة' : 'Stage tasks' }} — {{ $activeStage->name }}
                        </p>
                        <p class="inc-card-desc">
                            {{ $isAr ? 'حدّث الحالة، سلّم أعمالك، وتابع ملاحظات الموجه' : 'Update status, submit work, and follow mentor feedback' }}
                        </p>
                    </div>
                    <span class="inc-pill pill-{{ $activeStage->status }}">
                        @if ($activeStage->status === 'not_started')
                            {{ $isAr ? 'لم تبدأ' : 'Not started' }}
                        @elseif ($activeStage->status === 'in_progress')
                            {{ $isAr ? 'جارية' : 'In progress' }}
                        @elseif ($activeStage->status === 'completed')
                            {{ $isAr ? 'مكتملة' : 'Completed' }}
                        @else
                            {{ $activeStage->status }}
                        @endif
                    </span>
                </div>
                <div class="inc-card-body">

                    <div class="inc-task-cards">
                        @forelse ($activeStage->tasks as $index => $task)

                            <div class="inc-task-card task-{{ $task->status }}">

                                <div class="inc-task-top">
                                    <div class="inc-task-num num-{{ $task->status }}">
                                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <div>
                                        <div class="inc-task-name">{{ $task->title }}</div>
                                        <div class="inc-task-desc">{{ $task->description }}</div>
                                        @if ($task->due_date)
                                            <div class="inc-task-due">
                                                {{ $isAr ? 'الموعد النهائي:' : 'Due:' }}
                                                {{ $task->due_date->format('d M Y') }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="inc-pill pill-{{ $task->status }}">
                                        @if ($task->status === 'not_started')
                                            {{ $isAr ? 'لم تبدأ' : 'Not started' }}
                                        @elseif ($task->status === 'in_progress')
                                            {{ $isAr ? 'جارية' : 'In progress' }}
                                        @elseif ($task->status === 'submitted')
                                            {{ $isAr ? 'تم التسليم' : 'Submitted' }}
                                        @elseif ($task->status === 'approved')
                                            {{ $isAr ? 'مقبولة' : 'Approved' }}
                                        @elseif ($task->status === 'changes_requested')
                                            {{ $isAr ? 'مطلوب تعديل' : 'Changes requested' }}
                                        @else
                                            {{ $task->status }}
                                        @endif
                                    </span>
                                </div>

                                <div class="inc-panel">
                                    <div class="inc-panel-label">{{ $isAr ? 'تحديث الحالة' : 'Update status' }}</div>
                                    @if (in_array($task->status, ['approved', 'changes_requested'], true))
                                        <p class="mb-0" style="font-size:12px;color:var(--txt2);">
                                            <span class="inc-pill pill-{{ $task->status }}">{{ $task->status }}</span>
                                            <span class="ms-2">{{ $isAr ? '(تم ضبط الحالة من قبل الموجه)' : '(Status set by mentor)' }}</span>
                                        </p>
                                    @else
                                        <form method="post"
                                              action="{{ route('entrepreneur.projects.tasks.status.update', [$project, $task]) }}">
                                            @csrf
                                            @method('PATCH')
                                            <div class="inc-panel-row">
                                                <select class="inc-select" name="status">
                                                    <option value="not_started" @if ($task->status === 'not_started') selected @endif>
                                                        {{ $isAr ? 'لم تبدأ' : 'Not started' }}
                                                    </option>
                                                    <option value="in_progress" @if ($task->status === 'in_progress') selected @endif>
                                                        {{ $isAr ? 'قيد التنفيذ' : 'In progress' }}
                                                    </option>
                                                    <option value="submitted" @if ($task->status === 'submitted') selected @endif>
                                                        {{ $isAr ? 'تم التسليم' : 'Delivered' }}
                                                    </option>
                                                </select>
                                                <button type="submit" class="inc-btn-dark">
                                                    {{ $isAr ? 'حفظ' : 'Save' }}
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </div>

                                <div class="inc-panel" style="margin-top:6px;">
                                    <div class="inc-panel-label">{{ $isAr ? 'رفع العمل والتسليم' : 'Submit work' }}</div>
                                    <form method="post"
                                          action="{{ route('entrepreneur.projects.tasks.submit', [$project, $task]) }}"
                                          enctype="multipart/form-data">
                                        @csrf
                                        <textarea class="inc-textarea mb-2" name="notes" rows="2"
                                                  placeholder="{{ $isAr ? 'ملاحظات التسليم…' : 'Submission notes…' }}"></textarea>
                                        <input type="file" name="files[]" multiple
                                               class="mb-2"
                                               style="font-size:12px;width:100%;">
                                        <button type="submit" class="inc-btn-green">
                                            {{ $isAr ? 'رفع / تسليم' : 'Upload / Submit' }}
                                        </button>
                                    </form>
                                </div>

                                <div class="inc-panel" style="margin-top:6px;">
                                    <div class="inc-panel-label">{{ $isAr ? 'تعليق الموجه' : 'Mentor comment' }}</div>
                                    <p class="mb-0" style="font-size:12px;color:var(--txt2);line-height:1.45;">
                                        {{ $task->mentor_comments ? $task->mentor_comments : '—' }}
                                    </p>
                                </div>

                                @foreach ($task->submissions as $submission)
                                    <div class="inc-submission">
                                        <div class="inc-sub-header">
                                            <span class="inc-sub-id">
                                                {{ $isAr ? 'تسليم #' : 'Submission #' }}{{ $submission->id }}
                                            </span>
                                            <span class="inc-pill pill-{{ $submission->status }}">{{ $submission->status }}</span>
                                        </div>
                                        @if ($submission->notes)
                                            <p class="inc-sub-notes">{{ $submission->notes }}</p>
                                        @endif
                                        @if ($submission->files->count())
                                            <p class="inc-sub-files">
                                                {{ $isAr ? 'مرفقات:' : 'Attachments:' }}
                                                {{ $submission->files->count() }}
                                                {{ $isAr ? 'ملف(ات)' : 'file(s)' }}
                                            </p>
                                        @endif
                                    </div>
                                @endforeach

                                <div class="inc-chat">
                                    <div class="inc-chat-label">
                                        {{ $isAr ? 'محادثة مع الموجه' : 'Chat with mentor' }}
                                    </div>
                                    @forelse ($task->messages as $msg)
                                        <div class="inc-chat-msg">
                                            <strong>{{ optional($msg->user)->name }}</strong>
                                            {{ $msg->message }}
                                            <span class="chat-time">{{ $msg->created_at->diffForHumans() }}</span>
                                        </div>
                                    @empty
                                        <div class="inc-chat-empty">{{ $isAr ? 'لا توجد رسائل بعد.' : 'No messages yet.' }}</div>
                                    @endforelse
                                    <form method="post"
                                          action="{{ route('entrepreneur.projects.tasks.messages.store', [$project, $task]) }}">
                                        @csrf
                                        <div class="inc-chat-row">
                                            <input class="inc-chat-input" name="message" required
                                                   placeholder="{{ $isAr ? 'اكتب رسالة للموجه…' : 'Write a message to your mentor…' }}">
                                            <button type="submit" class="inc-send-btn">
                                                <svg viewBox="0 0 14 14"><path d="M12 7L2 2l2 5-2 5 10-5z"/></svg>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </div>

                        @empty
                            <p style="text-align:center;color:var(--txt3);font-size:13px;padding:.75rem 0;">
                                {{ $isAr ? 'لا توجد مهام في هذه المرحلة بعد.' : 'No tasks in this stage yet.' }}
                            </p>
                        @endforelse
                    </div>

                </div>
            </div>

        @endif
    </div>

    <div>

        <div class="inc-card inc-card-slate">
            <div class="inc-card-head">
                <div>
                    <p class="inc-card-title">{{ $isAr ? 'نظرة عامة على التقدم' : 'Progress overview' }}</p>
                    <p class="inc-card-desc">
                        {{ $isAr ? 'المرحلة' : 'Stage' }} {{ optional($activeStage)->stage_order ?? '-' }}
                        {{ $isAr ? 'من' : 'of' }} {{ $totalStages }}
                        — {{ optional($activeStage)->name ?? '' }}
                    </p>
                </div>
            </div>
            <div class="inc-card-body">
                <div class="inc-metrics-grid">
                    <div class="inc-metric">
                        <div class="inc-metric-label">{{ $isAr ? 'المرحلة الحالية' : 'Current stage' }}</div>
                        <div class="inc-metric-val">{{ optional($activeStage)->stage_order ?? '-' }}</div>
                        <div class="inc-metric-sub">{{ $isAr ? 'من' : 'of' }} {{ $totalStages }} {{ $isAr ? 'مراحل' : 'stages' }}</div>
                    </div>
                    <div class="inc-metric">
                        <div class="inc-metric-label">{{ $isAr ? 'مهام مكتملة' : 'Tasks done' }}</div>
                        <div class="inc-metric-val">{{ $doneTasks }}</div>
                        <div class="inc-metric-sub">{{ $isAr ? 'من' : 'of' }} {{ $totalTasks }} {{ $isAr ? 'مهام' : 'tasks' }}</div>
                    </div>
                </div>
                <div class="inc-prog-wrap">
                    <div class="inc-prog-head">
                        <span>{{ $isAr ? 'التقدم الكلي' : 'Overall progress' }}</span>
                        <strong>{{ $overallPct }}%</strong>
                    </div>
                    <div class="inc-prog-bar"><div class="inc-prog-fill" style="width:{{ $overallPct }}%"></div></div>
                </div>
                <div class="inc-prog-wrap">
                    <div class="inc-prog-head">
                        <span>{{ $isAr ? 'إتمام المرحلة' : 'Stage completion' }}</span>
                        <strong>{{ $stagePct }}%</strong>
                    </div>
                    <div class="inc-prog-bar"><div class="inc-prog-fill" style="width:{{ $stagePct }}%"></div></div>
                </div>
                <div class="inc-stage-dots">
                    @foreach ($project->stages->sortBy('stage_order') as $stage)
                        <div class="inc-dot {{ $stage->status }}"></div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="inc-card inc-card-light">
            <div class="inc-card-head">
                <div>
                    <p class="inc-card-title">{{ $isAr ? 'سجل النشاط' : 'Activity log' }}</p>
                    <p class="inc-card-desc">{{ $isAr ? 'آخر الأحداث على هذا المشروع' : 'Recent events on this project' }}</p>
                </div>
            </div>
            <div class="inc-card-body" style="padding-top:.5rem;padding-bottom:.5rem;">
                @forelse ($project->activityLogs as $log)
                    <div class="inc-activity-item">
                        <span>{{ $log->event }}</span>
                        <span class="inc-activity-time">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p style="text-align:center;color:var(--txt3);font-size:12px;padding:.5rem 0;">
                        {{ $isAr ? 'لا يوجد نشاط حتى الآن.' : 'No activity yet.' }}
                    </p>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
