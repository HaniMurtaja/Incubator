@extends('layouts.app')
@section('title', app()->getLocale()==='ar' ? 'مركز قيادة الموجه' : 'Mentor Command Center')
@section('content')
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

    <div class="row row-cards">
        @foreach($stages as $stage)
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="small text-muted mb-1">PHASE {{ $stage->stage_order }}</div>
                        <h4 class="h5">{{ $stage->name }}</h4>
                        <div class="mb-3"><x-status-badge :status="$stage->status" /></div>
                        <form method="post" action="{{ route('mentor.command.stages.update', $stage) }}" class="d-flex gap-2">
                            @csrf @method('patch')
                            <select class="form-select form-select-sm" name="status">
                                @foreach(['not_started','in_progress','completed'] as $status)
                                    <option value="{{ $status }}" @if($stage->status===$status) selected @endif>{{ str_replace('_',' ', $status) }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection

