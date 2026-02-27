@extends('backend.layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <nav class="mb-0" style="--bs-breadcrumb-divider: '›';">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('cpanel.dashboard') }}" class="text-decoration-none">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('cpanel.teacher') }}" class="text-decoration-none">Giáo viên</a></li>
                    <li class="breadcrumb-item active text-gray-600">Phân công</li>
                </ol>
            </nav>
            <h1 class="h3 mb-2 text-gray-900 fw-bold">
                <i class="fas fa-chalkboard-teacher text-primary me-2"></i>
                Phân công giảng dạy
            </h1>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center bg-light rounded-3 px-3 py-2">
                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                    <i class="bi bi-person text-primary"></i>
                </div>
                <div>
                    <div class="text-xs text-muted">Giáo viên</div>
                    <div class="fw-semibold">{{ $teacher->name }} {{ $teacher->last_name }}</div>
                </div>
            </div>
            <div class="d-flex align-items-center bg-light rounded-3 px-3 py-2">
                <div class="bg-success bg-opacity-10 rounded-circle p-2 me-2">
                    <i class="bi bi-qr-code text-success"></i>
                </div>
                <div>
                    <div class="text-xs text-muted">Mã GV</div>
                    <div class="fw-semibold">{{ $teacher->code ?? $teacher->id }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Form Column -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="bi bi-plus-lg text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="card-title mb-1 fw-semibold">Thêm phân công mới</h5>
                            <p class="text-muted mb-0 small">Chọn lớp và môn học cần phân công</p>
                        </div>
                    </div>
                    
                    @include('backend.teacher.components.assign-form', [
                        'teacher' => $teacher,
                        'classes' => $classes,
                        'subjects'=> $subjects
                    ])
                    
                    <div class="alert alert-light border mt-4 mb-0" role="alert">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="bi bi-info-circle text-info"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <small class="text-muted">Mỗi giáo viên có thể được phân công nhiều lớp và môn học khác nhau.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Column -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="card-title mb-1 fw-semibold">Danh sách phân công</h5>
                            <p class="text-muted mb-0 small">Tổng cộng {{ count($assigned) }} phân công</p>
                        </div>
                        <div>
                            @if(count($assigned) > 0)
                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                    <i class="bi bi-check-circle me-1"></i>
                                    {{ count($assigned) }} môn
                                </span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 py-2">
                                    <i class="bi bi-exclamation-circle me-1"></i>
                                    Chưa có phân công
                                </span>
                            @endif
                        </div>
                    </div>

                    @if(count($assigned) > 0)
                        <div class="table-responsive">
                            @include('backend.teacher.components.assign-table', [
                                'assigned' => $assigned
                            ])
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-journal-text text-light" style="font-size: 4rem;"></i>
                            </div>
                            <h5 class="text-muted mb-2">Chưa có phân công nào</h5>
                            <p class="text-muted mb-0">Bắt đầu bằng cách thêm phân công đầu tiên</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Base */
    body {
        background-color: #f8f9fa;
    }
    
    .container-fluid {
        padding: 20px;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    /* Header */
    .h3 {
        color: #2c3e50;
        font-weight: 600;
    }
    
    .breadcrumb {
        font-size: 0.875rem;
        padding: 0;
        margin: 0;
        background: transparent;
    }
    
    .breadcrumb-item a {
        color: #6c757d;
        transition: color 0.2s;
    }
    
    .breadcrumb-item a:hover {
        color: #2c3e50;
    }
    
    /* Cards */
    .card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.04);
        background: #fff;
        overflow: hidden;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    /* Tables */
    .table {
        margin-bottom: 0;
        font-size: 0.875rem;
    }
    
    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #e9ecef;
        color: #6c757d;
        font-weight: 600;
        padding: 0.875rem 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.75rem;
    }
    
    .table tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
        color: #495057;
    }
    
    .table tbody tr:last-child td {
        border-bottom: none;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    /* Buttons */
    .btn-primary {
        background-color: #2c3e50;
        border-color: #2c3e50;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
        border-radius: 6px;
        transition: all 0.2s;
    }
    
    .btn-primary:hover {
        background-color: #1a252f;
        border-color: #1a252f;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px rgba(44, 62, 80, 0.1);
    }
    
    .btn-outline-light {
        border-color: #dee2e6;
        color: #6c757d;
    }
    
    .btn-outline-light:hover {
        background-color: #f8f9fa;
        border-color: #adb5bd;
        color: #495057;
    }
    
    /* Form */
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }
    
    .form-control,
    .form-select {
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        height: 42px;
    }
    
    .form-control:focus,
    .form-select:focus {
        border-color: #2c3e50;
        box-shadow: 0 0 0 0.2rem rgba(44, 62, 80, 0.1);
    }
    
    /* Badges */
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }
    
    .badge.bg-primary {
        background-color: #2c3e50 !important;
    }
    
    /* Alerts */
    .alert-light {
        background-color: #f8f9fa;
        border-color: #e9ecef;
        color: #6c757d;
    }
    
    /* Action buttons */
    .action-btn {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid #dee2e6;
        background: #fff;
        color: #6c757d;
        transition: all 0.2s;
    }
    
    .action-btn:hover {
        background-color: #f8f9fa;
        border-color: #adb5bd;
        color: #495057;
        transform: translateY(-1px);
    }
    
    /* Empty state */
    .text-light {
        color: #e9ecef !important;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .container-fluid {
            padding: 15px;
        }
        
        .row {
            flex-direction: column;
        }
        
        .col-lg-4,
        .col-lg-8 {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .card-body {
            padding: 1.25rem;
        }
    }
    
    @media (max-width: 768px) {
        .d-flex.justify-content-between.align-items-center.mb-5 {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .d-flex.align-items-center.gap-3 {
            width: 100%;
            justify-content: flex-start;
            flex-wrap: wrap;
        }
        
        .table-responsive {
            margin: 0 -1.25rem;
            width: calc(100% + 2.5rem);
        }
    }
</style>
@endsection

@section('script')
<script type="text/javascript" src="{{ asset('js/demo_tables.js') }}"></script>
@endsection