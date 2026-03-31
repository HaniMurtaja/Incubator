@props(['status'])

@php
    $map = [
        'pending' => 'bg-yellow-lt',
        'accepted' => 'bg-green-lt',
        'rejected' => 'bg-red-lt',
        'in_progress' => 'bg-blue-lt',
        'completed' => 'bg-teal-lt',
        'not_started' => 'bg-secondary-lt',
        'submitted' => 'bg-indigo-lt',
        'changes_requested' => 'bg-orange-lt',
        'approved' => 'bg-green-lt',
        'under_review' => 'bg-cyan-lt',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'badge '.($map[$status] ?? 'bg-azure-lt')]) }}>
    {{ str_replace('_', ' ', $status) }}
</span>

