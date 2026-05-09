@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'تقديم مشروع' : 'Submit Project')

@push('styles')
<style>
.ep-form-shell {
    background: #fff;
    border: 1px solid #DDE2EC;
    border-radius: 12px;
    padding: 1.5rem 1.75rem;
    max-width: 720px;
}
.ep-form-shell .form-label {
    font-size: 13px;
    font-weight: 600;
    color: #4A5568;
    margin-bottom: 6px;
}
.ep-form-shell .form-control, .ep-form-shell textarea {
    border-radius: 9px;
    border: 1.5px solid #DDE2EC;
    background: #F8F9FB;
    font-size: 14px;
}
.ep-form-shell .form-control:focus, .ep-form-shell textarea:focus {
    border-color: #1A56DB;
    background: #fff;
    box-shadow: none;
}
.ep-form-submit {
    display: inline-block;
    margin-top: 1rem;
    padding: 10px 22px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    border: 1.5px solid #1A56DB;
    background: #1A56DB;
    color: #fff;
    cursor: pointer;
    transition: background .15s, transform .12s;
}
.ep-form-submit:hover {
    background: #154cbd;
    transform: translateY(-1px);
}
</style>
@endpush

@section('content')
@php $isAr = app()->getLocale() === 'ar'; @endphp

<form method="post" action="{{ route('entrepreneur.projects.store') }}" enctype="multipart/form-data" class="ep-form-shell">
    @csrf
    @include('entrepreneur.projects.partials.form')
    <button type="submit" class="ep-form-submit">{{ $isAr ? 'إرسال' : 'Submit' }}</button>
</form>
@endsection
