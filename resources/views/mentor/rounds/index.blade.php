@extends('layouts.app')

@php $isAr = app()->getLocale() === 'ar'; @endphp

@section('title', $isAr ? 'جولات الاحتضان' : 'Incubator Rounds')

@section('content')
<div class="row row-cards mb-3">
    <div class="col-md-4"><div class="card card-body"><small>{{ $isAr ? 'عدد الجولات الحالية' : 'Current Rounds' }}</small><h3>{{ $stats['current_rounds'] }}</h3></div></div>
    <div class="col-md-4"><div class="card card-body"><small>{{ $isAr ? 'عدد المشاريع' : 'Projects' }}</small><h3>{{ $stats['projects'] }}</h3></div></div>
    <div class="col-md-4"><div class="card card-body"><small>{{ $isAr ? 'المهام التي أنشأتها' : 'Tasks Created' }}</small><h3>{{ $stats['tasks_created'] }}</h3></div></div>
</div>
<div class="card">
    <div class="card-header">{{ $isAr ? 'الجولات المرتبطة بمشاريعك' : 'Rounds linked to your projects' }}</div>
    <div class="card-body">
        @forelse($rounds as $round)
            <div class="border rounded p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                    <h3 class="h5 mb-0">{{ $round->name }}</h3>
                    <small class="text-muted">
                        {{ optional($round->start_date)->format('Y-m-d') }} - {{ optional($round->end_date)->format('Y-m-d') }}
                    </small>
                </div>
                <p class="text-muted mb-2">{{ $round->description ?: ($isAr ? 'لا يوجد وصف.' : 'No description.') }}</p>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>{{ $isAr ? 'المشروع' : 'Project' }}</th>
                                <th>{{ $isAr ? 'رائد الأعمال' : 'Entrepreneur' }}</th>
                                <th>{{ $isAr ? 'الحالة' : 'Status' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($round->projects as $project)
                                <tr>
                                    <td>{{ $project->title }}</td>
                                    <td>{{ optional($project->entrepreneur)->name }}</td>
                                    <td><x-status-badge :status="$project->status" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4">{{ $isAr ? 'لا توجد جولات مرتبطة بك.' : 'No rounds assigned to you yet.' }}</div>
        @endforelse
    </div>
    <div class="card-body pt-0">{{ $rounds->links() }}</div>
</div>
@endsection

