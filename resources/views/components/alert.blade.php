@props([
    'type' => 'info', 
    'message' => '', 
    'dismissible' => true
])

@php
    $alertClasses = [
        'success' => 'alert-success',
        'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
        'primary' => 'alert-primary',
        'secondary' => 'alert-secondary'
    ];

    $iconClasses = [
        'success' => 'fa-check-circle',
        'danger' => 'fa-exclamation-triangle',
        'warning' => 'fa-exclamation-circle',
        'info' => 'fa-info-circle',
        'primary' => 'fa-bell',
        'secondary' => 'fa-question-circle'
    ];

    $selectedClass = $alertClasses[$type] ?? 'alert-info';
    $selectedIcon = $iconClasses[$type] ?? 'fa-info-circle';
@endphp

<div 
    class="alert {{ $selectedClass }} {{ $dismissible ? 'alert-dismissible fade show' : '' }} d-flex align-items-center" 
    role="alert"
>
    <i class="fas {{ $selectedIcon }} me-3 fa-lg"></i>
    <div>
        {{ $message }}
    </div>
    
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>
