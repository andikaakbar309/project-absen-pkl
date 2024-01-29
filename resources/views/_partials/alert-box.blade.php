@php
    $classAlert = $status == 'success' ? 'success' : 'danger';
    $classAlertIcon = $status == 'success' ? 'mdi mdi-check' : 'mdi mdi-alert-circle-outline';
    $title = $status == 'success' ? 'Success' : 'Oooops!';
@endphp

<div class="alert alert-dismissible alert-{{ $classAlert }} d-flex align-items-center p-5">
    <span class="{{ $classAlertIcon }} text-{{ $classAlert }} mdi-36 me-4"></span>
    <div class="d-flex flex-column">
        <h4 class="mb-1 text-{{ $classAlert }}">{{ $title }}</h4>
        <span>{{ $message }}</span>
    </div>
    <button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto" data-bs-dismiss="alert">
        <span class="mdi mdi-close mdi-18 text-{{ $classAlert }}"></span>
    </button>
</div>