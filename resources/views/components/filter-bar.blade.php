<div class="card mb-3">
    <div class="card-body">
        <form method="get" class="row g-2 align-items-end">
            {{ $slot }}
            <div class="col-auto">
                <button class="btn btn-primary">Filter</button>
            </div>
            <div class="col-auto">
                <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

