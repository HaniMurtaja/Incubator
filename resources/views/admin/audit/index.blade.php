@extends('layouts.app')
@section('title', app()->getLocale()==='ar' ? 'سجل تدقيق النظام' : 'System Audit Trail')
@section('content')
<div class="card">
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>Time</th><th>Event</th><th>Project</th><th>Actor</th></tr></thead>
            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ optional($log->created_at)->format('Y-m-d H:i') }}</td>
                    <td>{{ $log->event }}</td>
                    <td>{{ optional($log->project)->title }}</td>
                    <td>{{ optional($log->actor)->name }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">No audit entries</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $logs->links() }}</div>
@endsection

