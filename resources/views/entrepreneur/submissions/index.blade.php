@extends('layouts.app')
@section('title', __('ui.entrepreneur_submissions'))
@section('content')
<a class="btn btn-primary mb-3" href="{{ route('entrepreneur.submissions.create') }}">{{ __('ui.entrepreneur_new_submission') }}</a>
<x-filter-bar>
    <div class="col-md-4">
        <label class="form-label">{{ __('ui.search') }} {{ __('ui.task') }}</label>
        <input class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="{{ __('ui.search_task_title') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">{{ __('ui.status') }}</label>
        <select class="form-select" name="status">
            <option value="">{{ __('ui.all') }}</option>
            @foreach(['submitted','under_review','approved','changes_requested'] as $status)
                <option value="{{ $status }}" @if(($filters['status'] ?? '') === $status) selected @endif>{{ __('ui.statuses.'.$status) }}</option>
            @endforeach
        </select>
    </div>
</x-filter-bar>
@if($submissions->isEmpty())
    <x-empty-state :title="__('ui.no_submissions')" />
@else
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead><tr><th>{{ __('ui.task') }}</th><th>{{ __('ui.project') }}</th><th>{{ __('ui.status') }}</th><th></th></tr></thead>
                <tbody>
                @foreach($submissions as $submission)
                    <tr>
                        <td>{{ optional($submission->task)->title }}</td>
                        <td>{{ optional(optional(optional($submission->task)->stage)->project)->title }}</td>
                        <td><x-status-badge :status="$submission->status" /></td>
                        <td><a class="btn btn-sm btn-outline-primary" href="{{ route('entrepreneur.submissions.show', $submission) }}">{{ __('ui.view') }}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $submissions->links() }}</div>
@endif
@endsection
