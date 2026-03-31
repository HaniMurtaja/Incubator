@props(['title' => 'No data yet', 'message' => 'Try adjusting filters or create a new item.'])

<div class="card">
    <div class="card-body text-center py-5">
        <div class="text-muted">{{ $title }}</div>
        <div class="small text-muted mt-1">{{ $message }}</div>
        @if (trim($slot))
            <div class="mt-3">{{ $slot }}</div>
        @endif
    </div>
</div>

