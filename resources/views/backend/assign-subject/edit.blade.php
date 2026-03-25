@extends('backend.layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mt-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('cpanel.admin') }}">Home</a>
            </li>
            <li class="breadcrumb-item active">Edit Assign Subject Class</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="mb-4">
        <h2 class="h4 mb-0">
            <i class="fa fa-arrow-circle-o-left"></i> Edit Assign Subject Class
        </h2>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal" method="POST" action="{{ route('cpanel.assign.subject.update', $assign->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Edit Assign Subject Class</h3>
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
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}" {{ old('class_id', $assign->class_id) == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
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
                                @if($subjects->count() > 0)
                                    @foreach ($subjects as $subject)
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" 
                                                       value="{{ $subject->id }}" 
                                                       name="subject_id[]"
                                                       {{ (is_array(old('subject_id')) && in_array($subject->id, old('subject_id'))) || 
                                                          (!old('subject_id') && $assign->subject_id == $subject->id) ? 'checked' : '' }}>
                                                {{ $subject->name }}
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
                                    <option value="1" {{ old('status', $assign->status) == '1' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="0" {{ old('status', $assign->status) == '0' ? 'selected' : '' }}>
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
                            Update
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
    // Add select/deselect all functionality
    var $subjectContainer = $('.form-group:has(input[name="subject_id[]"]) .col-md-6');
    if ($subjectContainer.find('.checkbox').length > 0) {
        // Add Select All checkbox
        var $selectAll = $('<div class="checkbox"><label><input type="checkbox" id="selectAllSubjects"> Select All Subjects</label></div>');
        $subjectContainer.prepend($selectAll);
        
        $('#selectAllSubjects').change(function() {
            $('input[name="subject_id[]"]').prop('checked', $(this).prop('checked'));
        });
        
        // Update Select All checkbox when individual checkboxes change
        $('input[name="subject_id[]"]').change(function() {
            if ($('input[name="subject_id[]"]:checked').length === $('input[name="subject_id[]"]').length) {
                $('#selectAllSubjects').prop('checked', true);
            } else {
                $('#selectAllSubjects').prop('checked', false);
            }
        });
        
        // Check if all subjects are selected initially
        if ($('input[name="subject_id[]"]:checked').length === $('input[name="subject_id[]"]').length) {
            $('#selectAllSubjects').prop('checked', true);
        }
    }
});
</script>
@endsection