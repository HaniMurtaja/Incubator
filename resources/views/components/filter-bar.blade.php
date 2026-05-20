<div class="card mb-3">
    <div class="card-body">
        <form method="get" class="row g-2 align-items-end">
            {{ $slot }}
            <div class="col-auto">
                <button class="btn btn-primary">{{ __('ui.filter') }}</button>
            </div>
            <div class="col-auto">
                <a href="{{ url()->current() }}" class="btn btn-outline-secondary">{{ __('ui.reset') }}</a>
            </div>
        </form>
    </div>
</div>
