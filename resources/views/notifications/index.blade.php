@extends('layouts.app')
@section('title', 'Notifications')
@section('content')
<x-filter-bar>
    <div class="col-md-5">
        <label class="form-label">Search</label>
        <input class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Keyword in notification">
    </div>
    <div class="col-md-3">
        <label class="form-label">Unread only</label>
        <select class="form-select" name="unread">
            <option value="0" @if(($filters['unread'] ?? '0') == '0') selected @endif>No</option>
            <option value="1" @if(($filters['unread'] ?? '0') == '1') selected @endif>Yes</option>
        </select>
    </div>
</x-filter-bar>
<div class="mb-3">
    <form method="post" action="{{ route('notifications.read') }}">
        @csrf
        <button class="btn btn-outline-primary btn-sm">Mark all as read</button>
    </form>
</div>

@if($notifications->isEmpty())
    <x-empty-state title="No notifications yet." />
@else
    <div class="card">
        <div class="list-group list-group-flush">
            @foreach($notifications as $notification)
                <div class="list-group-item d-flex justify-content-between align-items-start">
                    <div>
                        <div class="fw-bold">{{ data_get($notification->data, 'message', 'Notification') }}</div>
                        <div class="small text-muted">{{ $notification->created_at }}</div>
                        @if(data_get($notification->data, 'decision'))
                            <div class="small">Decision: <x-status-badge :status="data_get($notification->data, 'decision')" /></div>
                        @endif
                    </div>
                    @if(is_null($notification->read_at))
                        <span class="badge bg-red-lt">Unread</span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    <div class="mt-3">{{ $notifications->links() }}</div>
@endif
@endsection

