@extends('layouts.app')

@section('title', __('ui.mentor_dashboard'))

@section('content')
<div class="row row-cards mb-4">
    <div class="col-md-4"><div class="card card-body"><small>{{ app()->getLocale()==='ar'?'المشاريع المسندة':'Assigned Projects' }}</small><h3>{{ $stats['assigned_projects'] }}</h3></div></div>
    <div class="col-md-4"><div class="card card-body"><small>{{ app()->getLocale()==='ar'?'التسليمات بانتظار التقييم':'Pending Reviews' }}</small><h3>{{ $stats['pending_submissions'] }}</h3></div></div>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
    <a class="btn btn-dark btn-sm" href="{{ route('mentor.command.index') }}">Mentor Command Center</a>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('mentor.calendar.index') }}">Mentorship Calendar</a>
    <a class="btn btn-outline-primary btn-sm" href="{{ route('mentor.projects.index') }}">Projects</a>
    <a class="btn btn-outline-primary btn-sm" href="{{ route('mentor.tasks.index') }}">Tasks</a>
</div>

<div class="row row-cards">
    <div class="col-md-6">
        <div class="card card-body h-100">
            <h4 class="mb-2">{{ app()->getLocale()==='ar'?'إدارة المشاريع':'Project Management' }}</h4>
            <p class="text-muted mb-3">{{ app()->getLocale()==='ar'?'عرض المشاريع المتاحة لك ومتابعة مراحل كل مشروع.':'Track your assigned projects and stage progress.' }}</p>
            <a class="btn btn-outline-primary btn-sm" href="{{ route('mentor.projects.index') }}">{{ app()->getLocale()==='ar'?'عرض المشاريع':'View Projects' }}</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-body h-100">
            <h4 class="mb-2">{{ app()->getLocale()==='ar'?'إدارة المهام والتقييم':'Tasks & Evaluation' }}</h4>
            <p class="text-muted mb-3">{{ app()->getLocale()==='ar'?'إنشاء المهام، مراجعة التسليمات، وإرسال الملاحظات.':'Create tasks, review submissions, and provide feedback.' }}</p>
            <a class="btn btn-outline-primary btn-sm" href="{{ route('mentor.tasks.index') }}">{{ app()->getLocale()==='ar'?'الذهاب للمهام':'Open Tasks' }}</a>
        </div>
    </div>
</div>
@endsection

