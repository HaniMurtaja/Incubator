@extends('layouts.app')

@section('title', __('ui.entrepreneur_dashboard'))

@push('styles')
<style>
/* ── Stat cards ── */
.dash-stat-card {
    background: #FFFFFF;
    border: 1px solid #DDE2EC;
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 6px;
    transition: box-shadow .15s, transform .15s;
}
.dash-stat-card:hover {
    box-shadow: 0 4px 16px rgba(15,23,36,.1);
    transform: translateY(-2px);
}
.dash-stat-label {
    font-size: 12px;
    font-weight: 600;
    color: #8896AA;
    text-transform: uppercase;
    letter-spacing: .5px;
}
.dash-stat-value {
    font-size: 36px;
    font-weight: 700;
    color: #0F1724;
    line-height: 1;
}
.dash-stat-icon {
    width: 36px; height: 36px;
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 4px;
}
.dash-stat-icon.blue  { background: #EBF2FF; color: #1A56DB; }
.dash-stat-icon.green { background: #E6F5F0; color: #0B7B5C; }
.dash-stat-icon svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 1.8; }

/* ── Quick nav tabs ── */
.dash-nav-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 1.5rem;
    background: #FFFFFF;
    border: 1px solid #DDE2EC;
    border-radius: 12px;
    padding: .75rem 1rem;
}
.dash-nav-tab {
    padding: 7px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    border: 1.5px solid #DDE2EC;
    background: #F1F3F7;
    color: #4A5568;
    transition: background .15s, color .15s, border-color .15s, transform .12s;
}
.dash-nav-tab:hover {
    background: #1A56DB;
    border-color: #1A56DB;
    color: #fff;
    transform: translateY(-1px);
    text-decoration: none;
}
.dash-nav-tab.active {
    background: #0F1724;
    border-color: #0F1724;
    color: #fff;
}

/* ── Action cards ── */
.dash-action-card {
    background: #FFFFFF;
    border: 1px solid #DDE2EC;
    border-radius: 12px;
    padding: 1.5rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: box-shadow .15s, transform .15s;
}
.dash-action-card:hover {
    box-shadow: 0 4px 20px rgba(15,23,36,.09);
    transform: translateY(-2px);
}
.dash-action-icon {
    width: 44px; height: 44px;
    border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    margin-bottom: 1rem;
}
.dash-action-icon.blue  { background: #EBF2FF; color: #1A56DB; }
.dash-action-icon.green { background: #E6F5F0; color: #0B7B5C; }
.dash-action-icon svg { width: 22px; height: 22px; stroke: currentColor; fill: none; stroke-width: 1.8; }
.dash-action-title {
    font-size: 15px; font-weight: 700; color: #0F1724; margin-bottom: 6px;
}
.dash-action-desc {
    font-size: 13px; color: #8896AA; line-height: 1.55; flex: 1;
}
.dash-action-btn {
    display: inline-block;
    margin-top: 1.25rem;
    padding: 9px 20px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    border: 1.5px solid;
    transition: background .15s, color .15s, border-color .15s, transform .12s;
    text-align: center;
}
.dash-action-btn.blue {
    background: #EBF2FF; color: #1A56DB; border-color: #BFCFEF;
}
.dash-action-btn.blue:hover {
    background: #1A56DB; color: #fff; border-color: #1A56DB; transform: translateY(-1px); text-decoration: none;
}
.dash-action-btn.green {
    background: #E6F5F0; color: #0B7B5C; border-color: #A7D9C9;
}
.dash-action-btn.green:hover {
    background: #0B7B5C; color: #fff; border-color: #0B7B5C; transform: translateY(-1px); text-decoration: none;
}

/* ── Section header ── */
.dash-section-label {
    font-size: 11px; font-weight: 700; color: #8896AA;
    text-transform: uppercase; letter-spacing: .6px;
    margin-bottom: .875rem;
}
</style>
@endpush

@section('content')
@php $isAr = app()->getLocale() === 'ar'; @endphp

{{-- ── Stat cards ── --}}
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="dash-stat-card">
            <div class="dash-stat-icon blue">
                <svg viewBox="0 0 24 24"><path d="M3 7h18M3 12h18M3 17h12"/></svg>
            </div>
            <div class="dash-stat-label">
                {{ $isAr ? 'مشاريعي' : 'My Projects' }}
            </div>
            <div class="dash-stat-value">{{ $stats['my_projects'] }}</div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="dash-stat-card">
            <div class="dash-stat-icon green">
                <svg viewBox="0 0 24 24"><path d="M9 12l2 2 4-4M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="dash-stat-label">
                {{ $isAr ? 'المشاريع المكتملة' : 'Completed Projects' }}
            </div>
            <div class="dash-stat-value">{{ $stats['completed_projects'] }}</div>
        </div>
    </div>
</div>

{{-- ── Quick navigation tabs ── --}}
<div class="dash-nav-tabs mb-4">
    <a href="{{ route('entrepreneur.projects.index') }}"
       class="dash-nav-tab {{ request()->routeIs('entrepreneur.projects.*') ? 'active' : '' }}">
        {{ $isAr ? 'المشاريع' : 'Projects' }}
    </a>
    <a href="{{ route('entrepreneur.meetings.index') }}"
       class="dash-nav-tab {{ request()->routeIs('entrepreneur.meetings.*') ? 'active' : '' }}">
        {{ $isAr ? 'اجتماعاتي' : 'My Meetings' }}
    </a>
    <a href="{{ route('entrepreneur.rounds.index') }}"
       class="dash-nav-tab {{ request()->routeIs('entrepreneur.rounds.*') ? 'active' : '' }}">
        {{ $isAr ? 'جولات الاحتضان' : 'Incubator Rounds' }}
    </a>
    <a href="{{ route('entrepreneur.submissions.index') }}"
       class="dash-nav-tab {{ request()->routeIs('entrepreneur.submissions.*') ? 'active' : '' }}">
        {{ $isAr ? 'التسليمات' : 'Submissions' }}
    </a>
</div>

{{-- ── Action cards ── --}}
<div class="dash-section-label">{{ $isAr ? 'الإجراءات السريعة' : 'Quick Actions' }}</div>
<div class="row g-3">
    <div class="col-md-6">
        <div class="dash-action-card">
            <div class="dash-action-icon blue">
                <svg viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div class="dash-action-title">
                {{ $isAr ? 'تقديم وإدارة المشاريع' : 'Project Submission & Management' }}
            </div>
            <div class="dash-action-desc">
                {{ $isAr ? 'قدّم فكرتك وتابع حالة مشروعك ومراحله خطوة بخطوة.' : 'Submit your idea and track its full lifecycle.' }}
            </div>
            <a href="{{ route('entrepreneur.projects.index') }}" class="dash-action-btn blue">
                {{ $isAr ? 'فتح مشاريعي' : 'Open My Projects' }}
            </a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="dash-action-card">
            <div class="dash-action-icon green">
                <svg viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="dash-action-title">
                {{ $isAr ? 'التسليمات والتغذية الراجعة' : 'Submissions & Feedback' }}
            </div>
            <div class="dash-action-desc">
                {{ $isAr ? 'ارفع تسليمات المهام واطّلع على تعليقات الموجه.' : 'Upload task deliveries and review mentor feedback.' }}
            </div>
            <a href="{{ route('entrepreneur.submissions.index') }}" class="dash-action-btn green">
                {{ $isAr ? 'عرض التسليمات' : 'View Submissions' }}
            </a>
        </div>
    </div>
</div>
@endsection
