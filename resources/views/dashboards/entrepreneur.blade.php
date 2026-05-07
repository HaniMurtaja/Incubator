@extends('layouts.app')

@section('title', __('ui.entrepreneur_dashboard'))

@section('content')
<div class="row row-cards mb-4">
    <div class="col-md-4"><div class="card card-body"><small>{{ app()->getLocale()==='ar'?'مشاريعي':'My Projects' }}</small><h3>{{ $stats['my_projects'] }}</h3></div></div>
    <div class="col-md-4"><div class="card card-body"><small>{{ app()->getLocale()==='ar'?'المشاريع المكتملة':'Completed Projects' }}</small><h3>{{ $stats['completed_projects'] }}</h3></div></div>
</div>

<div class="d-flex flex-wrap gap-2 mb-4">
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('entrepreneur.meetings.index') }}">{{ app()->getLocale()==='ar'?'اجتماعاتي':'My Meetings' }}</a>
    <a class="btn btn-outline-secondary btn-sm" href="{{ route('entrepreneur.rounds.index') }}">{{ app()->getLocale()==='ar'?'جولات الاحتضان':'Incubator Rounds' }}</a>
</div>

<div class="row row-cards">
    <div class="col-md-6">
        <div class="card card-body h-100">
            <h4 class="mb-2">{{ app()->getLocale()==='ar'?'تقديم وإدارة المشاريع':'Project Submission & Management' }}</h4>
            <p class="text-muted mb-3">{{ app()->getLocale()==='ar'?'قدّم فكرتك وتابع حالة مشروعك ومراحله خطوة بخطوة.':'Submit your idea and track its full lifecycle.' }}</p>
            <a class="btn btn-outline-primary btn-sm" href="{{ route('entrepreneur.projects.index') }}">{{ app()->getLocale()==='ar'?'فتح مشاريعي':'Open My Projects' }}</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-body h-100">
            <h4 class="mb-2">{{ app()->getLocale()==='ar'?'التسليمات والتغذية الراجعة':'Submissions & Feedback' }}</h4>
            <p class="text-muted mb-3">{{ app()->getLocale()==='ar'?'ارفع تسليمات المهام واطّلع على تعليقات الموجه.':'Upload task deliveries and review mentor feedback.' }}</p>
            <a class="btn btn-outline-primary btn-sm" href="{{ route('entrepreneur.submissions.index') }}">{{ app()->getLocale()==='ar'?'عرض التسليمات':'View Submissions' }}</a>
        </div>
    </div>
</div>
@endsection

