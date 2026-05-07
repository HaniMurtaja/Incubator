@extends('layouts.app')

@section('title', __('ui.admin_dashboard'))

@php
    $isAr = app()->getLocale() === 'ar';
    $statusOrder = ['pending', 'accepted', 'rejected', 'in_progress', 'completed'];
    $donutLabels = $isAr
        ? ['قيد الانتظار', 'مقبول', 'مرفوض', 'قيد التنفيذ', 'مكتمل']
        : ['Pending', 'Accepted', 'Rejected', 'In progress', 'Completed'];
    $donutSeries = array_map(fn ($s) => (int) ($statusCounts[$s] ?? 0), $statusOrder);
    $catLabels = $categoryRows->pluck('category')->toArray();
    $catSeries = $categoryRows->pluck('c')->map(fn ($v) => (int) $v)->toArray();
    $taskOrder = ['not_started', 'in_progress', 'submitted', 'changes_requested', 'approved'];
    $taskLabels = $isAr
        ? ['لم يبدأ', 'قيد التنفيذ', 'مُسلَّم', 'يحتاج تعديلات', 'معتمد']
        : ['Not started', 'In progress', 'Submitted', 'Changes requested', 'Approved'];
    $taskSeries = array_map(fn ($s) => (int) ($tasksByStatus[$s] ?? 0), $taskOrder);
@endphp

@section('content')
<style>
.admin-analytics-wrap {
    font-family: "Segoe UI", Tahoma, Arial, sans-serif;
}
.admin-stat-card {
    transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
    border: 0;
    border-radius: 1rem;
    background: linear-gradient(135deg, #ffffff 0%, #f7f9ff 100%);
    cursor: pointer;
    height: 100%;
    box-shadow: 0 .5rem 1.2rem rgba(15, 23, 42, .08);
}
.admin-stat-card:hover {
    transform: translateY(-6px) scale(1.01);
    box-shadow: 0 .95rem 1.8rem rgba(32,107,196,.18);
}
.admin-stat-card .stat-icon {
    width: 2.75rem;
    height: 2.75rem;
    border-radius: .75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}
.admin-stat-card .stat-icon svg {
    width: 1.2rem;
    height: 1.2rem;
}
.admin-kpi-label {
    font-size: .95rem !important;
    font-weight: 700;
    color: #334155 !important;
}
.admin-kpi-value {
    font-size: 2rem;
    font-weight: 800;
    line-height: 1.1;
}
.admin-kpi-meta {
    font-size: .9rem;
}
.analysis-chip {
    border: 0;
    border-radius: 999px;
    padding: 1rem 1.2rem;
    background: linear-gradient(135deg, #eef2ff 0%, #f8fafc 100%);
    box-shadow: 0 .4rem 1rem rgba(15, 23, 42, .08);
    transition: transform .2s ease, box-shadow .2s ease;
}
.analysis-chip:hover {
    transform: translateY(-4px);
    box-shadow: 0 .8rem 1.4rem rgba(2, 132, 199, .15);
}
</style>

<div class="admin-analytics-wrap">
<div class="row row-cards mb-3">
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none text-reset">
            <div class="card card-body admin-stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted admin-kpi-label">{{ $isAr ? 'إجمالي المستخدمين' : 'Total users' }}</div>
                        <h3 class="mb-0 mt-1 admin-kpi-value">{{ $stats['users'] }}</h3>
                        <div class="text-muted mt-1 admin-kpi-meta">{{ $isAr ? 'الموجهون' : 'Mentors' }}: {{ $stats['mentors'] }} · {{ $isAr ? 'رائدون' : 'Entrepreneurs' }}: {{ $stats['entrepreneurs'] }}</div>
                    </div>
                    <div class="stat-icon bg-primary-lt text-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('admin.projects.index') }}" class="text-decoration-none text-reset">
            <div class="card card-body admin-stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted admin-kpi-label">{{ $isAr ? 'إجمالي المشاريع' : 'Total projects' }}</div>
                        <h3 class="mb-0 mt-1 admin-kpi-value">{{ $stats['projects'] }}</h3>
                        <div class="text-muted mt-1 admin-kpi-meta">{{ $isAr ? 'مكتمل' : 'Completed' }}: {{ $stats['completed_projects'] }}</div>
                    </div>
                    <div class="stat-icon bg-azure-lt text-azure">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 7a2 2 0 0 1 2-2h5l2 2h7a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('admin.projects.index', ['status' => 'pending']) }}" class="text-decoration-none text-reset">
            <div class="card card-body admin-stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted admin-kpi-label">{{ $isAr ? 'قيد المراجعة' : 'Pending review' }}</div>
                        <h3 class="mb-0 mt-1 admin-kpi-value">{{ $stats['pending_projects'] }}</h3>
                        <div class="text-muted mt-1 admin-kpi-meta">{{ $isAr ? 'مقبول / مرفوض' : 'Accepted / rejected' }}: {{ $stats['accepted_projects'] }} / {{ $stats['rejected_projects'] }}</div>
                    </div>
                    <div class="stat-icon bg-warning-lt text-warning">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M6 2h12"></path>
                            <path d="M6 22h12"></path>
                            <path d="M8 2v6a4 4 0 0 0 8 0V2"></path>
                            <path d="M16 22v-6a4 4 0 0 0-8 0v6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('admin.projects.index', ['status' => 'in_progress']) }}" class="text-decoration-none text-reset">
            <div class="card card-body admin-stat-card">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="text-muted admin-kpi-label">{{ $isAr ? 'قيد التنفيذ' : 'In progress' }}</div>
                        <h3 class="mb-0 mt-1 admin-kpi-value">{{ $stats['in_progress_projects'] }}</h3>
                        <div class="text-muted mt-1 admin-kpi-meta">{{ $isAr ? 'مهام' : 'Tasks' }}: {{ $stats['tasks_total'] }}</div>
                    </div>
                    <div class="stat-icon bg-green-lt text-green">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09a1.65 1.65 0 0 0-1-1.51 1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09a1.65 1.65 0 0 0 1.51-1 1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33h0A1.65 1.65 0 0 0 9.93 3.1V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51h0a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82v0a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row row-cards mb-4">
    <div class="col-md-4">
        <div class="analysis-chip h-100">
            <div class="text-muted small">{{ $isAr ? 'طابور التسليمات' : 'Submission queue' }}</div>
            <h3 class="mb-0">{{ $stats['submissions_pending'] }}</h3>
            <div class="text-muted small">{{ $isAr ? 'بانتظار المراجعة' : 'Awaiting mentor review' }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="analysis-chip h-100">
            <div class="text-muted small">{{ $isAr ? 'تسليمات معتمدة' : 'Approved submissions' }}</div>
            <h3 class="mb-0">{{ $stats['submissions_approved'] }}</h3>
            <div class="text-muted small">{{ $isAr ? 'بعد التقييم' : 'After evaluation' }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="analysis-chip h-100">
            <div class="text-muted small">{{ $isAr ? 'مشاريع مكتملة' : 'Completed projects' }}</div>
            <h3 class="mb-0">{{ $stats['completed_projects'] }}</h3>
            <div class="text-muted small">{{ $isAr ? 'خرجت من الحاضنة' : 'Graduated / closed' }}</div>
        </div>
    </div>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
    <a class="btn btn-dark btn-sm" href="{{ route('admin.projects.index') }}">+ {{ $isAr ? 'مشروع جديد' : 'New project' }}</a>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.users.index') }}">+ {{ $isAr ? 'مستخدم' : 'User' }}</a>
    <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.assignments.index') }}">{{ $isAr ? 'سجل التعيين' : 'Assign pair' }}</a>
    <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.meetings.index') }}">{{ $isAr ? 'طلبات الاجتماع' : 'Meeting requests' }}</a>
    <a class="btn btn-outline-primary btn-sm" href="{{ route('admin.audit.index') }}">{{ $isAr ? 'سجل التدقيق' : 'Audit trail' }}</a>
</div>

<div class="row row-cards mb-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">{{ $isAr ? 'توزيع المشاريع حسب الحالة' : 'Projects by status' }}</div>
            <div class="card-body">
                <div id="adminDonut" style="height: 300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">{{ $isAr ? 'المشاريع حسب المجال' : 'Projects by category' }}</div>
            <div class="card-body">
                <div id="adminBarCat" style="height: 300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">{{ $isAr ? 'المهام حسب الحالة' : 'Tasks by status' }}</div>
            <div class="card-body">
                <div id="adminBarTasks" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">{{ $isAr ? 'المشاريع الجديدة (آخر 7 أيام)' : 'New projects (last 7 days)' }}</div>
    <div class="card-body">
        <div id="adminTrend" style="height: 280px;"></div>
    </div>
</div>

<div class="row row-cards">
    <div class="col-md-4">
        <div class="card card-body h-100 admin-stat-card" onclick="window.location='{{ route('admin.users.index') }}'">
            <h4 class="mb-2">{{ $isAr ? 'إدارة المنصة' : 'Platform management' }}</h4>
            <p class="text-muted mb-3">{{ $isAr ? 'الحسابات والأدوار والصلاحيات.' : 'Accounts, roles, and permissions.' }}</p>
            <span class="btn btn-outline-primary btn-sm">{{ $isAr ? 'المستخدمون' : 'Manage users' }}</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-body h-100 admin-stat-card" onclick="window.location='{{ route('admin.projects.index') }}'">
            <h4 class="mb-2">{{ $isAr ? 'اعتماد المشاريع' : 'Project approvals' }}</h4>
            <p class="text-muted mb-3">{{ $isAr ? 'المراجعة والتعيين.' : 'Review, approve, assign mentors.' }}</p>
            <span class="btn btn-outline-primary btn-sm">{{ $isAr ? 'المشاريع' : 'Review projects' }}</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-body h-100 admin-stat-card" onclick="window.location='{{ route('admin.stages.index') }}'">
            <h4 class="mb-2">{{ $isAr ? 'مسارات الاحتضان' : 'Incubation tracks' }}</h4>
            <p class="text-muted mb-3">{{ $isAr ? 'المراحل والترتيب.' : 'Stages and ordering per project.' }}</p>
            <span class="btn btn-outline-primary btn-sm">{{ $isAr ? 'المراحل' : 'Manage stages' }}</span>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
(function () {
    const donutSeries = @json($donutSeries);
    const donutLabels = @json($donutLabels);
    const catLabels = @json($catLabels);
    const catSeries = @json($catSeries);
    const taskLabels = @json($taskLabels);
    const taskSeries = @json($taskSeries);
    const dayLabels = @json($dayLabels);
    const daySeries = @json($daySeries);

    new ApexCharts(document.querySelector('#adminDonut'), {
        chart: { type: 'donut', height: 300 },
        series: donutSeries,
        labels: donutLabels,
        colors: ['#f59f00', '#228be6', '#e03131', '#12b886', '#7950f2'],
        legend: { position: 'bottom' },
        dataLabels: { enabled: true },
        plotOptions: { pie: { donut: { size: '62%' } } }
    }).render();

    if (catLabels.length) {
        new ApexCharts(document.querySelector('#adminBarCat'), {
            chart: { type: 'bar', height: 300, toolbar: { show: false } },
            series: [{ name: 'Projects', data: catSeries }],
            xaxis: { categories: catLabels },
            colors: ['#206bc4'],
            plotOptions: { bar: { borderRadius: 4, horizontal: true } }
        }).render();
    } else {
        document.querySelector('#adminBarCat').innerHTML = '<p class="text-muted text-center py-5">{{ $isAr ? "لا توجد بيانات تصنيف بعد." : "No category data yet." }}</p>';
    }

    new ApexCharts(document.querySelector('#adminBarTasks'), {
        chart: { type: 'bar', height: 300, toolbar: { show: false } },
        series: [{ name: 'Tasks', data: taskSeries }],
        xaxis: { categories: taskLabels },
        colors: ['#66a8ff'],
        plotOptions: { bar: { borderRadius: 4, columnWidth: '55%' } }
    }).render();

    new ApexCharts(document.querySelector('#adminTrend'), {
        chart: { type: 'area', height: 280, toolbar: { show: false }, zoom: { enabled: false } },
        series: [{ name: '{{ $isAr ? "مشاريع جديدة" : "New projects" }}', data: daySeries }],
        xaxis: { categories: dayLabels },
        stroke: { curve: 'smooth', width: 3 },
        fill: { type: 'gradient', gradient: { opacityFrom: 0.45, opacityTo: 0.05 } },
        colors: ['#206bc4'],
        dataLabels: { enabled: false }
    }).render();
})();
</script>
@endpush
