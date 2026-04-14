@extends('backend.layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('cpanel.admin') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">Create Assign Subject Class</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-4">
            <h2 class="h4 mb-0">
                <i class="fa fa-arrow-circle-o-left"></i> Assign Subject Class
            </h2>
        </div>

        @include('_massage')

        <!-- Form -->
        <div class="row">
            <div class="col-md-12">
                <form class="form-horizontal" method="POST" action="{{ route('cpanel.assign.subject.store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Assign Subject Class</h3>
                        </div>

                        <div class="panel-body">
                            <!-- Class -->
                            <div class="form-group @error('class_id') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Class <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <select name="class_id" class="form-control" required>
                                        <option value="">-- Select Class --</option>
                                        @foreach ($schoolClass as $sc)
                                            <option value="{{ $sc->id }}"
                                                {{ old('class_id') == $sc->id ? 'selected' : '' }}>
                                                {{ $sc->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('class_id')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Subject -->
                            <div class="form-group @error('subject_id') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Subject <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    @if ($Subjects->count() > 0)
                                        @foreach ($Subjects as $Sc)
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" value="{{ $Sc->id }}" name="subject_id[]"
                                                        {{ is_array(old('subject_id')) && in_array($Sc->id, old('subject_id')) ? 'checked' : '' }}>
                                                    {{ $Sc->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fa fa-warning"></i> No subjects available. Please add subjects first.
                                        </div>
                                    @endif
                                    @error('subject_id')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="form-group @error('status') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Status <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <select name="status" class="form-control" required>
                                        <option value="">-- Select status --</option>
                                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default">
                                Clear Form
                            </button>
                            <button type="submit" class="btn btn-primary pull-right">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            // Optional: Add select/deselect all functionality
            var $subjectContainer = $('.form-group:has(input[name="subject_id[]"]) .col-md-6');
            if ($subjectContainer.find('.checkbox').length > 0) {
                // Add Select All checkbox
                var $selectAll = $(
                    '<div class="checkbox"><label><input type="checkbox" id="selectAllSubjects"> Select All Subjects</label></div>'
                    );
                $subjectContainer.prepend($selectAll);

                $('#selectAllSubjects').change(function() {
                    $('input[name="subject_id[]"]').prop('checked', $(this).prop('checked'));
                });

                // Update Select All checkbox when individual checkboxes change
                $('input[name="subject_id[]"]').change(function() {
                    if ($('input[name="subject_id[]"]:checked').length === $('input[name="subject_id[]"]')
                        .length) {
                        $('#selectAllSubjects').prop('checked', true);
                    } else {
                        $('#selectAllSubjects').prop('checked', false);
                    }
                });
            }
        });
    </script>
@endsection
