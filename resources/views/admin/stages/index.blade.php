@extends('layouts.app')
@section('title', __('ui.admin_stages'))
@section('content')
<a class="btn btn-primary mb-3" href="{{ route('admin.stages.create') }}">{{ __('ui.admin_create_stage') }}</a>
<x-filter-bar>
    <div class="col-md-4">
        <label class="form-label">{{ __('ui.search_project') }}</label>
        <input class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="{{ __('ui.project_title_placeholder') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">{{ __('ui.project') }}</label>
        <select class="form-select" name="project_id">
            <option value="">{{ __('ui.all_projects') }}</option>
            @foreach($projectOptions as $projectOption)
                <option value="{{ $projectOption->id }}" @if(($filters['project_id'] ?? '') == $projectOption->id) selected @endif>{{ $projectOption->title }}</option>
            @endforeach
        </select>
    </div>
</x-filter-bar>
@if($projects->isEmpty())
    <x-empty-state :title="__('ui.no_projects_stages')" />
@else
    @foreach($projects as $project)
        <div class="card mb-3">
            <div class="card-header"><strong>{{ $project->title }}</strong></div>
            <div class="card-body">
                @if($project->stages->isEmpty())
                    <x-empty-state :title="__('ui.no_stages_for_project')" />
                @else
                    <table class="table">
                        <thead><tr><th>{{ __('ui.order') }}</th><th>{{ __('ui.name') }}</th><th>{{ __('ui.status') }}</th><th></th></tr></thead>
                        <tbody>
                        @foreach($project->stages as $stage)
                            <tr>
                                <td>{{ $stage->stage_order }}</td>
                                <td>{{ $stage->name }}</td>
                                <td><x-status-badge :status="$stage->status" /></td>
                                <td class="d-flex gap-2">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.stages.edit', $stage) }}">{{ __('ui.edit') }}</a>
                                    <form method="post" action="{{ route('admin.stages.destroy', $stage) }}">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger">{{ __('ui.delete') }}</button></form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @endforeach
    <div class="mt-3">{{ $projects->links() }}</div>
@endif
@endsection
