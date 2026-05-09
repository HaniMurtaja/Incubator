@extends('layouts.app')
@section('title', app()->getLocale() === 'ar' ? 'مركز قيادة الموجه' : 'Mentor Command Center')

@push('styles')
<style>
/* ══════════════════════════════════════════════
   COMMAND CENTER — Page styles (incubator-v10)
   ══════════════════════════════════════════════ */

.cc-page { font-size: 14.5px; }
.cc-page p, .cc-page td, .cc-page label,
.cc-page .form-label, .cc-page small { font-size: 14px; }
.cc-page h5 { font-size: 16px; font-weight: 700; }

.cc-card {
    background: #fff;
    border: 1px solid #DDE2EC;
    border-radius: 12px;
    padding: 1.125rem 1.5rem;
    margin-bottom: 1.125rem;
}
.cc-card-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #8896AA;
    margin-bottom: 6px;
}
.cc-select {
    font-size: 14px;
    padding: 9px 14px;
    border-radius: 9px;
    border: 1.5px solid #DDE2EC;
    background: #F8F9FB;
    color: #0F1724;
    width: 100%;
    outline: none;
    font-family: 'Cairo', sans-serif;
    cursor: pointer;
}
.cc-select:focus { border-color: #1A56DB; background: #fff; }

.cc-proj-card {
    background: #fff;
    border: 1.5px solid #DDE2EC;
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.125rem;
}
.cc-proj-title {
    font-size: 20px;
    font-weight: 700;
    color: #0F1724;
    margin-bottom: 10px;
}
.cc-proj-fields {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    align-items: center;
}
.cc-proj-field {
    font-size: 14.5px;
    color: #4A5568;
}
.cc-proj-field strong {
    font-size: 15px;
    color: #0F1724;
    font-weight: 700;
}
.cc-proj-desc {
    font-size: 14px;
    color: #8896AA;
    margin-top: 8px;
    margin-bottom: 0;
}

.cc-pill {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12.5px;
    font-weight: 700;
}
.cc-pill-pending       { background: #FEF3C7; color: #92400E; border: 1.5px solid #FCD34D; }
.cc-pill-accepted      { background: #E6F5F0; color: #0B7B5C; border: 1.5px solid #A7D9C9; }
.cc-pill-rejected      { background: #FDECEA; color: #C0392B; border: 1.5px solid #F4B8B2; }
.cc-pill-in_progress   { background: #EBF2FF; color: #1A56DB; border: 1.5px solid #BFCFEF; }
.cc-pill-not_started   { background: #FDECEA; color: #C0392B; border: 1.5px solid #F4B8B2; }
.cc-pill-completed     { background: #E6F5F0; color: #0B7B5C; border: 1.5px solid #A7D9C9; }
.cc-pill-submitted         { background: #FFFBEB; color: #92400E; border: 1.5px solid #FCD34D; }
.cc-pill-approved          { background: #E6F5F0; color: #0B7B5C; border: 1.5px solid #A7D9C9; }
.cc-pill-changes_requested { background: #FEF3C7; color: #92400E; border: 1.5px solid #FBBF24; }

.cc-stages-wrap {
    background: #fff;
    border: 1.5px solid #DDE2EC;
    border-radius: 12px;
    padding: 1rem 1.25rem 1.375rem;
    margin-bottom: 1.125rem;
}
.cc-stages-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: .875rem;
}
.cc-stages-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #4A5568;
}
.cc-legend {
    display: flex;
    align-items: center;
    gap: 14px;
}
.cc-leg {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12.5px;
    font-weight: 600;
}
.cc-leg-dot {
    width: 8px; height: 8px;
    border-radius: 50%; flex-shrink: 0;
}
.cc-leg.completed   { color: #0B7B5C; } .cc-leg.completed   .cc-leg-dot { background: #1D9E75; }
.cc-leg.in_progress { color: #1A56DB; } .cc-leg.in_progress .cc-leg-dot { background: #1A56DB; }
.cc-leg.not_started { color: #C0392B; } .cc-leg.not_started .cc-leg-dot { background: #E24B4A; }

.cc-stages {
    display: grid;
    grid-template-columns: repeat(9, 1fr);
    gap: 5px;
    position: relative;
}

.cc-stage {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    position: relative;
    border-radius: 8px;
    font-size: 11px;
    transition: transform .15s, box-shadow .15s;
    border: 2px solid transparent;
    clip-path: polygon(0% 0%, 100% 0%, 100% 68%, 50% 100%, 0% 68%);
    min-height: 82px;
    padding: 10px 8px 22px;
}
.cc-stage:hover {
    transform: translateY(-3px);
    text-decoration: none;
    filter: brightness(0.95);
}
.cc-stage .s-num {
    display: block;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: .3px;
    margin-bottom: 5px;
    opacity: .75;
}
.cc-stage .s-name {
    display: block;
    line-height: 1.3;
    font-weight: 700;
    font-size: 11px;
    word-break: break-word;
}

.cc-stage.completed {
    background: #C8EFE1;
    color: #054F35;
    border-color: #7DCFB0;
}
.cc-stage.completed .s-num { color: #0B7B5C; }

.cc-stage.in_progress {
    background: #BFCFEF;
    color: #0C2A6B;
    border-color: #7BA4E2;
}
.cc-stage.in_progress .s-num { color: #1A3F8F; }

.cc-stage.not_started {
    background: #F7C8C8;
    color: #6B1515;
    border-color: #E87474;
}
.cc-stage.not_started .s-num { color: #9B2020; }

.cc-stage.active-stage {
    box-shadow: 0 0 0 2.5px #0F1724;
    transform: translateY(-3px);
    z-index: 1;
}

.cc-detail-card {
    background: #fff;
    border: 1.5px solid #DDE2EC;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 1.125rem;
}
.cc-detail-head {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #DDE2EC;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: .75rem;
}
.cc-detail-title {
    font-size: 17px;
    font-weight: 700;
    color: #0F1724;
    margin: 0 0 4px;
}
.cc-detail-body { padding: 1.25rem 1.5rem; }

.cc-status-form {
    display: flex;
    align-items: center;
    gap: 8px;
}
.cc-form-select {
    font-size: 13px;
    padding: 7px 12px;
    border-radius: 8px;
    border: 1.5px solid #DDE2EC;
    background: #F8F9FB;
    color: #0F1724;
    font-family: 'Cairo', sans-serif;
    outline: none;
    cursor: pointer;
}
.cc-form-select:focus { border-color: #1A56DB; }
.cc-btn-save {
    padding: 7px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    background: #0F1724;
    color: #fff;
    border: none;
    cursor: pointer;
    font-family: 'Cairo', sans-serif;
    white-space: nowrap;
}
.cc-btn-save:hover { background: #1A56DB; }

.cc-add-task {
    background: #F0FAF6;
    border: 1.5px solid #A7D9C9;
    border-radius: 10px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
}
.cc-add-task-title {
    font-size: 14px;
    font-weight: 700;
    color: #064E3B;
    margin-bottom: .75rem;
}
.cc-add-grid {
    display: grid;
    grid-template-columns: 1fr 1fr auto auto;
    gap: 8px;
    align-items: end;
    margin-bottom: 8px;
}
@media (max-width: 992px) {
    .cc-add-grid { grid-template-columns: 1fr 1fr; }
}
.cc-inp {
    font-size: 13px;
    padding: 8px 11px;
    border-radius: 8px;
    border: 1.5px solid #A7D9C9;
    background: #fff;
    color: #0F1724;
    font-family: 'Cairo', sans-serif;
    outline: none;
    width: 100%;
}
.cc-inp:focus { border-color: #0B7B5C; }
.cc-inp-date {
    font-size: 13px;
    padding: 7px 10px;
    border-radius: 8px;
    border: 1.5px solid #A7D9C9;
    background: #fff;
    color: #0F1724;
    font-family: 'Cairo', sans-serif;
    outline: none;
}
.cc-btn-add {
    padding: 8px 18px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    background: #0B7B5C;
    color: #fff;
    border: none;
    cursor: pointer;
    font-family: 'Cairo', sans-serif;
    white-space: nowrap;
}
.cc-btn-add:hover { background: #064E3B; }
.cc-textarea {
    width: 100%;
    border: 1.5px solid #A7D9C9;
    border-radius: 8px;
    padding: 8px 11px;
    font-size: 13px;
    color: #0F1724;
    background: #fff;
    resize: none;
    font-family: 'Cairo', sans-serif;
    outline: none;
}
.cc-textarea:focus { border-color: #0B7B5C; }

.cc-table-wrap { overflow-x: auto; border-radius: 10px; border: 1px solid #DDE2EC; }
.cc-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}
.cc-table thead th {
    background: #F1F3F7;
    padding: 11px 14px;
    font-size: 12px;
    font-weight: 700;
    color: #4A5568;
    text-transform: uppercase;
    letter-spacing: .5px;
    border-bottom: 1.5px solid #DDE2EC;
    white-space: nowrap;
}
.cc-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #EEF3F7;
    color: #0F1724;
    font-size: 14px;
    vertical-align: middle;
}
.cc-table tbody tr:last-child td { border-bottom: none; }
.cc-table tbody tr:hover td { background: #F8F9FB; }

.cc-comment-form { display: flex; gap: 8px; align-items: center; min-width: 220px; }
.cc-comment-inp {
    flex: 1;
    font-size: 13px;
    padding: 6px 10px;
    border-radius: 7px;
    border: 1.5px solid #DDE2EC;
    background: #F8F9FB;
    color: #0F1724;
    font-family: 'Cairo', sans-serif;
    outline: none;
}
.cc-comment-inp:focus { border-color: #1A56DB; background: #fff; }
.cc-btn-comment {
    padding: 6px 14px;
    border-radius: 7px;
    font-size: 12px;
    font-weight: 700;
    background: #0B7B5C;
    color: #fff;
    border: none;
    cursor: pointer;
    font-family: 'Cairo', sans-serif;
    white-space: nowrap;
}
.cc-btn-comment:hover { background: #064E3B; }

.cc-prog-card {
    background: #0F1724;
    border: 1px solid rgba(255,255,255,.07);
    border-radius: 12px;
    padding: 1.25rem;
}
.cc-met-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 1rem; }
.cc-met {
    background: rgba(255,255,255,.07);
    border-radius: 8px;
    padding: 12px 13px;
    border: 1px solid rgba(255,255,255,.06);
}
.cc-met-label { font-size: 11px; color: #6B7A99; margin-bottom: 4px; }
.cc-met-val   { font-size: 26px; font-weight: 700; color: #5DCAA5; line-height: 1; }
.cc-met-sub   { font-size: 11px; color: #4A5568; margin-top: 2px; }
.cc-prog-wrap { margin-bottom: .875rem; }
.cc-prog-wrap:last-child { margin-bottom: 0; }
.cc-prog-hd { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 5px; }
.cc-prog-hd span { color: #6B7A99; }
.cc-prog-hd strong { color: #5DCAA5; }
.cc-prog-bar { height: 5px; background: rgba(255,255,255,.08); border-radius: 3px; overflow: hidden; }
.cc-prog-fill { height: 100%; border-radius: 3px; background: #1D9E75; }
.cc-dots { display: flex; gap: 4px; margin-top: .875rem; }
.cc-dot { flex: 1; height: 4px; border-radius: 2px; }
.cc-dot.completed   { background: #1D9E75; }
.cc-dot.in_progress { background: rgba(26,86,219,.55); }
.cc-dot.not_started { background: rgba(220,80,80,.35); }

.cc-detail-progress-grid {
    display: grid;
    grid-template-columns: 1fr 280px;
    gap: 1.125rem;
    align-items: start;
}
@media (max-width: 992px) {
    .cc-detail-progress-grid { grid-template-columns: 1fr; }
    .cc-stages { grid-template-columns: repeat(3, 1fr); }
}
</style>
@endpush

@section('content')
@php
    $isAr = app()->getLocale() === 'ar';
    $totalStages = $project ? $stages->count() : 0;
    $doneStages = $project ? $stages->where('status', 'completed')->count() : 0;
    $overallPct = $totalStages > 0 ? round($doneStages / $totalStages * 100) : 0;
    $activeStage = $project ? $stages->firstWhere('stage_order', $activeStageOrder) : null;
    $totalTasks = $activeStage ? $activeStage->tasks->count() : 0;
    $doneTasks = $activeStage ? $activeStage->tasks->whereIn('status', ['submitted', 'approved'])->count() : 0;
    $stagePct = $totalTasks > 0 ? round($doneTasks / $totalTasks * 100) : 0;
@endphp

<div class="cc-page">

    <div class="cc-card">
        <div class="cc-card-label">{{ $isAr ? 'اختر المشروع' : 'Select Project' }}</div>
        <form method="get">
            <select class="cc-select" name="project_id" onchange="this.form.submit()">
                @foreach($projects as $p)
                    <option value="{{ $p->id }}" @if($project && $project->id === $p->id) selected @endif>
                        {{ $p->title }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    @if(!$project)
        <div class="cc-card" style="text-align:center;color:#8896AA;padding:2rem;">
            {{ $isAr ? 'لا توجد مشاريع مسندة.' : 'No assigned projects.' }}
        </div>
    @else

    <div class="cc-proj-card">
        <div class="cc-proj-title">{{ $project->title }}</div>
        <div class="cc-proj-fields">
            <div class="cc-proj-field">
                <strong>{{ $isAr ? 'المشروع:' : 'Project:' }}</strong> {{ $project->title }}
            </div>
            <div class="cc-proj-field">
                <strong>{{ $isAr ? 'رائد الأعمال:' : 'Entrepreneur:' }}</strong>
                {{ optional($project->entrepreneur)->name ?? '-' }}
            </div>
            <div class="cc-proj-field">
                <strong>{{ $isAr ? 'الحالة:' : 'Status:' }}</strong>
                <span class="cc-pill cc-pill-{{ $project->status }} ms-1">{{ $project->status }}</span>
            </div>
        </div>
        @if($project->description)
            <p class="cc-proj-desc">{{ $project->description }}</p>
        @endif
    </div>

    <div class="cc-stages-wrap">
        <div class="cc-stages-header">
            <span class="cc-stages-title">{{ $isAr ? 'مراحل الاحتضان' : 'Incubation Stages' }}</span>
            <div class="cc-legend">
                <div class="cc-leg completed"><span class="cc-leg-dot"></span>{{ $isAr ? 'مكتملة' : 'Done' }}</div>
                <div class="cc-leg in_progress"><span class="cc-leg-dot"></span>{{ $isAr ? 'جارية' : 'In progress' }}</div>
                <div class="cc-leg not_started"><span class="cc-leg-dot"></span>{{ $isAr ? 'لم تبدأ' : 'Not started' }}</div>
            </div>
        </div>
        <div class="cc-stages">
            @foreach($stages->sortBy('stage_order') as $stage)
                @php $isActive = $stage->stage_order === $activeStageOrder; @endphp
                <a class="cc-stage {{ $stage->status }} {{ $isActive ? 'active-stage' : '' }}"
                   href="{{ route('mentor.command.index', ['project_id' => $project->id, 'stage' => $stage->stage_order]) }}#stage-detail"
                   data-stage="{{ $stage->stage_order }}"
                   data-active="{{ $isActive ? 'true' : 'false' }}"
                   onclick="handleStageClick(event, this)">
                    <span class="s-num">{{ str_pad($stage->stage_order, 2, '0', STR_PAD_LEFT) }}</span>
                    <span class="s-name">{{ $stage->name }}</span>
                </a>
            @endforeach
        </div>
    </div>

    @if($activeStage)
    <div class="cc-detail-progress-grid">

        <div class="cc-detail-card" id="stage-detail">
            <div class="cc-detail-head">
                <div>
                    <p class="cc-detail-title">{{ $activeStage->name }}</p>
                    <span class="cc-pill cc-pill-{{ $activeStage->status }}">
                        @if($activeStage->status === 'not_started')
                            {{ $isAr ? 'لم تبدأ' : 'Not started' }}
                        @elseif($activeStage->status === 'in_progress')
                            {{ $isAr ? 'جارية' : 'In progress' }}
                        @elseif($activeStage->status === 'completed')
                            {{ $isAr ? 'مكتملة' : 'Completed' }}
                        @else
                            {{ $activeStage->status }}
                        @endif
                    </span>
                </div>
                <form method="post" action="{{ route('mentor.command.stages.update', $activeStage) }}"
                      class="cc-status-form">
                    @csrf @method('patch')
                    <select class="cc-form-select" name="status">
                        @foreach(['not_started', 'in_progress', 'completed'] as $s)
                            <option value="{{ $s }}" @if($activeStage->status === $s) selected @endif>
                                @if($s === 'not_started')
                                    {{ $isAr ? 'لم تبدأ' : 'Not started' }}
                                @elseif($s === 'in_progress')
                                    {{ $isAr ? 'جارية' : 'In progress' }}
                                @else
                                    {{ $isAr ? 'مكتملة' : 'Completed' }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <button class="cc-btn-save">{{ $isAr ? 'حفظ' : 'Save' }}</button>
                </form>
            </div>

            <div class="cc-detail-body">

                <div class="cc-add-task">
                    <div class="cc-add-task-title">{{ $isAr ? 'إضافة مهمة جديدة' : 'Add New Task' }}</div>
                    <form method="post" action="{{ route('mentor.command.tasks.store', $activeStage) }}">
                        @csrf
                        <div class="cc-add-grid">
                            <input class="cc-inp" name="title" required
                                   placeholder="{{ $isAr ? 'عنوان المهمة' : 'Task title' }}">
                            <input class="cc-inp" name="description" required
                                   placeholder="{{ $isAr ? 'وصف المهمة' : 'Task description' }}">
                            <input class="cc-inp-date" type="date" name="due_date">
                            <button type="submit" class="cc-btn-add">
                                + {{ $isAr ? 'إضافة' : 'Add' }}
                            </button>
                        </div>
                        <textarea class="cc-textarea" name="mentor_comments" rows="2"
                                  placeholder="{{ $isAr ? 'تعليق الموجه' : 'Mentor comment' }}"></textarea>
                    </form>
                </div>

                <div class="cc-table-wrap">
                    <table class="cc-table">
                        <thead>
                            <tr>
                                <th>{{ $isAr ? 'المهمة' : 'Task' }}</th>
                                <th>{{ $isAr ? 'الحالة' : 'Status' }}</th>
                                <th>{{ $isAr ? 'تعليق الموجه' : 'Mentor Comment' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($activeStage->tasks as $task)
                            <tr>
                                <td>
                                    <div style="font-weight:600;color:#0F1724;margin-bottom:3px;">{{ $task->title }}</div>
                                    @if($task->description)
                                        <div style="font-size:13px;color:#8896AA;">{{ $task->description }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span class="cc-pill cc-pill-{{ $task->status }}">
                                        @if($task->status === 'not_started')
                                            {{ $isAr ? 'لم تبدأ' : 'Not started' }}
                                        @elseif($task->status === 'in_progress')
                                            {{ $isAr ? 'جارية' : 'In progress' }}
                                        @elseif($task->status === 'submitted')
                                            {{ $isAr ? 'تم التسليم' : 'Submitted' }}
                                        @elseif($task->status === 'approved')
                                            {{ $isAr ? 'مقبولة' : 'Approved' }}
                                        @elseif($task->status === 'changes_requested')
                                            {{ $isAr ? 'تعديلات مطلوبة' : 'Changes requested' }}
                                        @else
                                            {{ $task->status }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    <form method="post"
                                          action="{{ route('mentor.command.tasks.comment.update', [$activeStage, $task]) }}"
                                          class="cc-comment-form">
                                        @csrf @method('PATCH')
                                        <input class="cc-comment-inp" name="mentor_comments"
                                               value="{{ $task->mentor_comments }}"
                                               placeholder="{{ $isAr ? 'أضف تعليقاً…' : 'Add comment…' }}">
                                        <button type="submit" class="cc-btn-comment">
                                            {{ $isAr ? 'حفظ' : 'Save' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" style="text-align:center;color:#8896AA;padding:1.5rem;">
                                    {{ $isAr ? 'لا توجد مهام في هذه المرحلة.' : 'No tasks yet.' }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div>
            <div class="cc-prog-card">
                <div style="font-size:13px;font-weight:700;color:#EEF3F7;margin-bottom:3px;">
                    {{ $isAr ? 'نظرة عامة على التقدم' : 'Progress Overview' }}
                </div>
                <div style="font-size:12px;color:#6B7A99;margin-bottom:1rem;">
                    {{ $isAr ? 'المرحلة' : 'Stage' }} {{ optional($activeStage)->stage_order ?? '-' }}
                    {{ $isAr ? 'من' : 'of' }} {{ $totalStages }}
                </div>
                <div class="cc-met-grid">
                    <div class="cc-met">
                        <div class="cc-met-label">{{ $isAr ? 'المرحلة' : 'Current stage' }}</div>
                        <div class="cc-met-val">{{ optional($activeStage)->stage_order ?? '-' }}</div>
                        <div class="cc-met-sub">{{ $isAr ? 'من' : 'of' }} {{ $totalStages }}</div>
                    </div>
                    <div class="cc-met">
                        <div class="cc-met-label">{{ $isAr ? 'مهام منجزة' : 'Tasks done' }}</div>
                        <div class="cc-met-val">{{ $doneTasks }}</div>
                        <div class="cc-met-sub">{{ $isAr ? 'من' : 'of' }} {{ $totalTasks }}</div>
                    </div>
                </div>
                <div class="cc-prog-wrap">
                    <div class="cc-prog-hd">
                        <span>{{ $isAr ? 'التقدم الكلي' : 'Overall progress' }}</span>
                        <strong>{{ $overallPct }}%</strong>
                    </div>
                    <div class="cc-prog-bar"><div class="cc-prog-fill" style="width:{{ $overallPct }}%"></div></div>
                </div>
                <div class="cc-prog-wrap">
                    <div class="cc-prog-hd">
                        <span>{{ $isAr ? 'إتمام المرحلة' : 'Stage completion' }}</span>
                        <strong>{{ $stagePct }}%</strong>
                    </div>
                    <div class="cc-prog-bar"><div class="cc-prog-fill" style="width:{{ $stagePct }}%"></div></div>
                </div>
                <div class="cc-dots">
                    @foreach($stages->sortBy('stage_order') as $stage)
                        <div class="cc-dot {{ $stage->status }}"></div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
    @endif

    @endif

</div>

@push('scripts')
<script>
function handleStageClick(e, el) {
    var isActive = el.getAttribute('data-active') === 'true';
    if (isActive) {
        e.preventDefault();
        var target = document.getElementById('stage-detail');
        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    } else {
        sessionStorage.setItem('scrollToStageDetail', '1');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    if (sessionStorage.getItem('scrollToStageDetail') === '1') {
        sessionStorage.removeItem('scrollToStageDetail');
        var target = document.getElementById('stage-detail');
        if (target) {
            setTimeout(function () {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        }
    }
});
</script>
@endpush

@endsection
