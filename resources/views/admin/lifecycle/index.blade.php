@extends('layouts.app')
@php
    $isAr = app()->getLocale() === 'ar';
    $activeTab = $tab ?? 'rounds';
@endphp
@section('title', $isAr ? 'جولات الاحتضان' : 'Incubation Rounds')

@section('content')
<style>
.lifecycle-kpi {
    border: 0;
    border-radius: 1rem;
    background: linear-gradient(135deg, #ffffff 0%, #eef4ff 100%);
    box-shadow: 0 .55rem 1.1rem rgba(15, 23, 42, .08);
    transition: transform .2s ease, box-shadow .2s ease;
}
.lifecycle-kpi:hover {
    transform: translateY(-4px);
    box-shadow: 0 .9rem 1.5rem rgba(37, 99, 235, .16);
}
.lifecycle-kpi .kpi-label {
    font-size: .92rem;
    font-weight: 700;
}
.lifecycle-kpi .kpi-value {
    font-size: 2rem;
    font-weight: 800;
    line-height: 1.1;
}
.lifecycle-kpi-icon {
    width: 2.6rem;
    height: 2.6rem;
    border-radius: .75rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.lifecycle-kpi-icon svg {
    width: 1.15rem;
    height: 1.15rem;
}
.lifecycle-logo {
    width: 34px;
    height: 34px;
    object-fit: contain;
    border-radius: 50%;
    background: #fff;
    border: 1px solid rgba(148, 163, 184, .35);
    padding: 3px;
}
</style>

<div class="row row-cards mb-3">
    <div class="col-md-3">
        <div class="card card-body lifecycle-kpi">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted kpi-label">{{ $isAr ? 'إجمالي الجولات' : 'Total Rounds' }}</div>
                    <h3 class="mb-0 kpi-value">{{ $stats['rounds'] }}</h3>
                </div>
                <div class="lifecycle-kpi-icon bg-primary-lt text-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"></rect><path d="M8 2v4M16 2v4M3 10h18"></path></svg>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-body lifecycle-kpi">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted kpi-label">{{ $isAr ? 'الجولات النشطة' : 'Active Rounds' }}</div>
                    <h3 class="mb-0 kpi-value">{{ $stats['active_rounds'] }}</h3>
                </div>
                <div class="lifecycle-kpi-icon bg-green-lt text-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"></circle><path d="M12 7v5l3 3"></path></svg>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-body lifecycle-kpi">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted kpi-label">{{ $isAr ? 'الرعاة' : 'Sponsors' }}</div>
                    <h3 class="mb-0 kpi-value">{{ $stats['sponsors'] }}</h3>
                </div>
                <div class="lifecycle-kpi-icon bg-azure-lt text-azure">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3l2.5 5 5.5.8-4 3.9.9 5.5L12 16l-4.9 2.2.9-5.5-4-3.9 5.5-.8z"></path></svg>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-body lifecycle-kpi">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted kpi-label">{{ $isAr ? 'مشاريع الجولات' : 'Round Projects' }}</div>
                    <h3 class="mb-0 kpi-value">{{ $stats['projects'] }}</h3>
                </div>
                <div class="lifecycle-kpi-icon bg-warning-lt text-warning">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"></path></svg>
                </div>
            </div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ $activeTab === 'rounds' ? 'active' : '' }}" href="{{ route('admin.lifecycle.index', ['tab' => 'rounds']) }}">{{ $isAr ? 'الجولات' : 'Rounds' }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ $activeTab === 'projects' ? 'active' : '' }}" href="{{ route('admin.lifecycle.index', ['tab' => 'projects', 'round_id' => optional($selectedRound)->id]) }}">{{ $isAr ? 'مشاريع الجولة' : 'Round Projects' }}</a>
    </li>
</ul>

@if($activeTab === 'rounds')
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ $isAr ? 'إضافة جولة جديدة' : 'Create New Round' }}</span>
            <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#createRoundModal">{{ $isAr ? 'إنشاء جولة' : 'Create New Round' }}</button>
        </div>
        <div class="card-body text-muted">{{ $isAr ? 'استخدم الزر أعلاه لفتح نافذة إنشاء جولة جديدة.' : 'Use the button above to open the Create New Round popup.' }}</div>
    </div>

    <x-filter-bar>
        <div class="col-md-5">
            <label class="form-label">{{ $isAr ? 'بحث' : 'Search' }}</label>
            <input class="form-control" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="{{ $isAr ? 'اسم الجولة أو وصفها' : 'Round name or description' }}">
        </div>
        <input type="hidden" name="tab" value="rounds">
    </x-filter-bar>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>{{ $isAr ? 'اسم الجولة' : 'Round Name' }}</th>
                        <th>{{ $isAr ? 'الفترة' : 'Period' }}</th>
                        <th>{{ $isAr ? 'الوصف' : 'Description' }}</th>
                        <th>{{ $isAr ? 'الرعاة' : 'Sponsors' }}</th>
                        <th>{{ $isAr ? 'الإجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($rounds as $round)
                    <tr>
                        <td>{{ $round->name }}</td>
                        <td>{{ optional($round->start_date)->format('Y-m-d') }} → {{ optional($round->end_date)->format('Y-m-d') }}</td>
                        <td class="text-muted">{{ $round->description ?: '-' }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-1 align-items-center">
                                @forelse($round->sponsors as $sponsor)
                                    @if($sponsor->logo_path)
                                        <img src="{{ $sponsor->logo_path }}" alt="{{ $sponsor->name }}" class="lifecycle-logo">
                                    @endif
                                    <span class="badge bg-blue-lt">{{ $sponsor->name }}</span>
                                @empty
                                    <span class="text-muted">{{ $isAr ? 'لا يوجد' : 'None' }}</span>
                                @endforelse
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.lifecycle.index', ['tab' => 'projects', 'round_id' => $round->id]) }}">{{ $isAr ? 'مشاريع الجولة' : 'Show projects' }}</a>
                                <a class="btn btn-sm btn-primary" href="{{ route('admin.lifecycle.index', ['tab' => 'projects', 'round_id' => $round->id, 'open_add_project' => 1]) }}">{{ $isAr ? 'إضافة مشروع جديد' : 'Add New Project' }}</a>
                                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#editRound{{ $round->id }}">{{ $isAr ? 'تعديل' : 'Edit' }}</button>
                                <form method="post" action="{{ route('admin.lifecycle.rounds.destroy', $round) }}" onsubmit="return confirm('{{ $isAr ? 'حذف هذه الجولة؟' : 'Delete this round?' }}');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">{{ $isAr ? 'حذف' : 'Delete' }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr class="collapse" id="editRound{{ $round->id }}">
                        <td colspan="5">
                            <form method="post" action="{{ route('admin.lifecycle.rounds.update', $round) }}" class="row g-2">
                                @csrf @method('PATCH')
                                <div class="col-md-3"><input class="form-control" name="name" value="{{ $round->name }}" required></div>
                                <div class="col-md-2"><input class="form-control" type="date" name="start_date" value="{{ optional($round->start_date)->format('Y-m-d') }}" required></div>
                                <div class="col-md-2"><input class="form-control" type="date" name="end_date" value="{{ optional($round->end_date)->format('Y-m-d') }}" required></div>
                                <div class="col-md-5"><input class="form-control" name="description" value="{{ $round->description }}"></div>
                                @foreach($round->sponsors->values() as $index => $sponsor)
                                    <div class="col-md-5"><input class="form-control" name="sponsors[{{ $index }}][name]" value="{{ $sponsor->name }}"></div>
                                    <div class="col-md-7"><input class="form-control" name="sponsors[{{ $index }}][logo_path]" value="{{ $sponsor->logo_path }}" placeholder="Logo URL"></div>
                                @endforeach
                                <div class="col-12"><button class="btn btn-sm btn-primary">{{ $isAr ? 'تحديث الجولة' : 'Update Round' }}</button></div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">{{ $isAr ? 'لا توجد جولات بعد.' : 'No rounds yet.' }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $rounds->links() }}</div>
@else
    <div class="card mb-3">
        <div class="card-body">
            <form method="get" action="{{ route('admin.lifecycle.index') }}" class="row g-2 align-items-end">
                <input type="hidden" name="tab" value="projects">
                <div class="col-md-6">
                    <label class="form-label">{{ $isAr ? 'اختر الجولة' : 'Select Round' }}</label>
                    <select class="form-select" name="round_id" onchange="this.form.submit()">
                        <option value="">{{ $isAr ? 'اختر جولة' : 'Choose round' }}</option>
                        @foreach($allRounds as $roundOption)
                            <option value="{{ $roundOption->id }}" @if(optional($selectedRound)->id === $roundOption->id) selected @endif>{{ $roundOption->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    @if($selectedRound)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>{{ $isAr ? 'إضافة مشروع للجولة' : 'Add Project to Round' }}: {{ $selectedRound->name }}</span>
                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#newRoundProjectForm">{{ $isAr ? 'إضافة مشروع جديد' : 'Add New Project' }}</button>
            </div>
            <div id="newRoundProjectForm" class="collapse {{ !empty($openAddProject) ? 'show' : '' }}">
            <div class="card-body">
                <form method="post" action="{{ route('admin.lifecycle.projects.store', $selectedRound) }}" class="row g-2">
                    @csrf
                    <div class="col-md-3"><input class="form-control" name="title" placeholder="{{ $isAr ? 'اسم المشروع' : 'Project name' }}" required></div>
                    <div class="col-md-3"><input class="form-control" name="description" placeholder="{{ $isAr ? 'الوصف' : 'Description' }}" required></div>
                    <div class="col-md-2">
                        <select class="form-select" name="status" required>
                            @foreach(['pending','accepted','rejected','in_progress','completed'] as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="mentor_id">
                            <option value="">{{ $isAr ? 'الموجه' : 'Mentor' }}</option>
                            @foreach($mentors as $mentor)
                                <option value="{{ $mentor->id }}">{{ $mentor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="entrepreneur_id" required>
                            <option value="">{{ $isAr ? 'رائد الأعمال' : 'Entrepreneur' }}</option>
                            @foreach($entrepreneurs as $entrepreneur)
                                <option value="{{ $entrepreneur->id }}">{{ $entrepreneur->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12"><button class="btn btn-primary btn-sm">{{ $isAr ? 'إضافة' : 'Add' }}</button></div>
                </form>
            </div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                <tr>
                    <th>{{ $isAr ? 'اسم المشروع' : 'Project Name' }}</th>
                    <th>{{ $isAr ? 'الحالة' : 'Status' }}</th>
                    <th>{{ $isAr ? 'الموجه' : 'Assigned Mentor' }}</th>
                    <th>{{ $isAr ? 'رائد الأعمال' : 'Entrepreneur' }}</th>
                    <th>{{ $isAr ? 'الإجراءات' : 'Actions' }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($roundProjects as $project)
                    <tr>
                        <td>{{ $project->title }}</td>
                        <td><x-status-badge :status="$project->status" /></td>
                        <td>{{ optional($project->mentor)->name ?: '-' }}</td>
                        <td>{{ optional($project->entrepreneur)->name ?: '-' }}</td>
                        <td>
                            @if($project->round)
                                <form method="post" action="{{ route('admin.lifecycle.projects.destroy', [$project->round, $project]) }}" onsubmit="return confirm('{{ $isAr ? 'حذف المشروع؟' : 'Delete project?' }}');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">{{ $isAr ? 'حذف' : 'Delete' }}</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">{{ $isAr ? 'لا توجد مشاريع للجولة المختارة.' : 'No projects for selected round.' }}</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="mt-3">{{ $roundProjects->links() }}</div>
@endif
@endsection

@if($activeTab === 'rounds')
<div class="modal fade" id="createRoundModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $isAr ? 'إنشاء جولة جديدة' : 'Create New Round' }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('admin.lifecycle.rounds.store') }}" class="row g-2">
                    @csrf
                    <div class="col-md-4"><input class="form-control" name="name" placeholder="{{ $isAr ? 'اسم الجولة' : 'Round name' }}" required></div>
                    <div class="col-md-2"><input class="form-control" type="date" name="start_date" required></div>
                    <div class="col-md-2"><input class="form-control" type="date" name="end_date" required></div>
                    <div class="col-md-4"><input class="form-control" name="description" placeholder="{{ $isAr ? 'الوصف' : 'Description' }}"></div>
                    <div class="col-12 mt-2">
                        <div class="small text-muted mb-2">{{ $isAr ? 'الرعاة (يمكن إضافة أكثر من راعٍ)' : 'Sponsors (multi-sponsor supported)' }}</div>
                        <div id="sponsorRows">
                            <div class="row g-2 mb-2 sponsor-row">
                                <div class="col-md-5"><input class="form-control" name="sponsors[0][name]" placeholder="{{ $isAr ? 'اسم الراعي' : 'Sponsor name' }}"></div>
                                <div class="col-md-6"><input class="form-control" name="sponsors[0][logo_path]" placeholder="{{ $isAr ? 'رابط الشعار' : 'Logo URL' }}"></div>
                                <div class="col-md-1"><button type="button" class="btn btn-outline-danger w-100 remove-sponsor">-</button></div>
                            </div>
                        </div>
                        <button type="button" id="addSponsorBtn" class="btn btn-outline-primary btn-sm">{{ $isAr ? 'إضافة راعٍ' : 'Add Sponsor' }}</button>
                    </div>
                    <div class="col-12"><button class="btn btn-primary btn-sm">{{ $isAr ? 'حفظ الجولة' : 'Save Round' }}</button></div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
(function () {
    const addBtn = document.getElementById('addSponsorBtn');
    const rows = document.getElementById('sponsorRows');
    if (!addBtn || !rows) return;

    let idx = rows.querySelectorAll('.sponsor-row').length;
    addBtn.addEventListener('click', function () {
        const row = document.createElement('div');
        row.className = 'row g-2 mb-2 sponsor-row';
        row.innerHTML =
            '<div class="col-md-5"><input class="form-control" name="sponsors['+idx+'][name]" placeholder="{{ $isAr ? 'اسم الراعي' : 'Sponsor name' }}"></div>' +
            '<div class="col-md-6"><input class="form-control" name="sponsors['+idx+'][logo_path]" placeholder="{{ $isAr ? 'رابط الشعار' : 'Logo URL' }}"></div>' +
            '<div class="col-md-1"><button type="button" class="btn btn-outline-danger w-100 remove-sponsor">-</button></div>';
        rows.appendChild(row);
        idx++;
    });

    rows.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-sponsor')) {
            const all = rows.querySelectorAll('.sponsor-row');
            if (all.length > 1) {
                e.target.closest('.sponsor-row').remove();
            }
        }
    });
})();
</script>
@endpush

