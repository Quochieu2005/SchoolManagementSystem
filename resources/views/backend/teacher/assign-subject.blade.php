@extends('backend.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="text-dark mb-1">
                <i class="fas fa-chalkboard-teacher text-primary me-2"></i>
                Phân công giảng dạy
            </h4>
            <p class="text-muted mb-0">
                Giáo viên: <span class="fw-bold text-dark">{{ $teacher->name }} {{ $teacher->last_name }}</span>
            </p>
        </div>
        <div class="badge bg-light text-dark border">
            <i class="fas fa-user-tag me-1"></i>
            Mã GV: {{ $teacher->code ?? $teacher->id }}
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column - Form -->
        <div class="col-xl-4 col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 text-dark">
                        <i class="fas fa-plus-circle text-success me-2"></i>
                        Thêm phân công mới
                    </h5>
                </div>
                <div class="card-body">
                    @include('backend.teacher.components.assign-form', [
                        'teacher' => $teacher,
                        'classes' => $classes,
                        'subjects'=> $subjects
                    ])
                </div>
            </div>
        </div>

        <!-- Right Column - Table -->
        <div class="col-xl-8 col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-dark">
                            <i class="fas fa-list-check text-primary me-2"></i>
                            Danh sách phân công
                        </h5>
                        <span class="badge bg-primary">
                            {{ count($assigned) }} môn đã phân công
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @include('backend.teacher.components.assign-table', [
                        'assigned' => $assigned
                    ])
                </div>
                @if(count($assigned) > 5)
                <div class="card-footer bg-white border-top py-3">
                    <div class="text-muted small">
                        <i class="fas fa-info-circle me-1"></i>
                        Hiển thị {{ count($assigned) }} phân công giảng dạy
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('js/demo_tables.js') }}"></script>
@endsection