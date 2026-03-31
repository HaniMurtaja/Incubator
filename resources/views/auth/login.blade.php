<x-layouts.auth>
    @php $isAr = app()->getLocale() === 'ar'; @endphp

    <div class="text-center mb-4">
        <div class="d-inline-flex align-items-center bg-white rounded-pill shadow-sm p-2 px-3 border">
            <span class="badge bg-dark text-white me-2">V</span>
            <strong>{{ __('ui.app_name') }}</strong>
        </div>
        <h2 class="h1 mt-4 mb-1">{{ __('ui.gateway_title') }}</h2>
        <p class="text-muted">{{ __('ui.gateway_subtitle') }}</p>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12">
            <form method="post" action="{{ route('quick.access', 'admin') }}">
                @csrf
                <button type="submit" class="role-card card card-body py-3 w-100 border-0 text-start">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if($isAr)
                                <strong class="d-block">{{ __('ui.role_admin_ar') }}</strong>
                                <div class="text-muted small">{{ __('ui.role_admin_en') }}</div>
                            @else
                                <strong class="d-block">{{ __('ui.role_admin_en') }}</strong>
                                <div class="text-muted small">{{ __('ui.role_admin_ar') }}</div>
                            @endif
                        </div>
                        <span class="btn btn-dark btn-sm">{{ __('ui.enter_as') }}</span>
                    </div>
                </button>
            </form>
        </div>
        <div class="col-12">
            <form method="post" action="{{ route('quick.access', 'mentor') }}">
                @csrf
                <button type="submit" class="role-card card card-body py-3 w-100 border-0 text-start">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if($isAr)
                                <strong class="d-block">{{ __('ui.role_mentor_ar') }}</strong>
                                <div class="text-muted small">{{ __('ui.role_mentor_en') }}</div>
                            @else
                                <strong class="d-block">{{ __('ui.role_mentor_en') }}</strong>
                                <div class="text-muted small">{{ __('ui.role_mentor_ar') }}</div>
                            @endif
                        </div>
                        <span class="btn btn-dark btn-sm">{{ __('ui.enter_as') }}</span>
                    </div>
                </button>
            </form>
        </div>
        <div class="col-12">
            <form method="post" action="{{ route('quick.access', 'entrepreneur') }}">
                @csrf
                <button type="submit" class="role-card card card-body py-3 w-100 border-0 text-start">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if($isAr)
                                <strong class="d-block">{{ __('ui.role_entrepreneur_ar') }}</strong>
                                <div class="text-muted small">{{ __('ui.role_entrepreneur_en') }}</div>
                            @else
                                <strong class="d-block">{{ __('ui.role_entrepreneur_en') }}</strong>
                                <div class="text-muted small">{{ __('ui.role_entrepreneur_ar') }}</div>
                            @endif
                        </div>
                        <span class="btn btn-dark btn-sm">{{ __('ui.enter_as') }}</span>
                    </div>
                </button>
            </form>
        </div>
    </div>
</x-layouts.auth>
