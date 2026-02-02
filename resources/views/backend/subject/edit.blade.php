@extends('backend.layouts.app')

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('cpanel.class') }}">Home</a></li>
        <li class="active">Edit</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE TITLE -->
    <div class="page-title">
        <h2><span class="fa fa-arrow-circle-o-left"></span> Edit Class</h2>
    </div>
    <!-- END PAGE TITLE -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">

               <form action="{{ route('cpanel.subject.update', $subjects->slug) }}" method="POST"
                    enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    @method('PUT')

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edit Class</h3>
                        </div>

                        <div class="panel-body">

                            {{-- School Name --}}
                            <div class="form-group @error('name') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Class Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <span class="fa fa-pencil"></span>
                                        </span>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name', $subjects->name) }}">
                                    </div>
                                    @error('name')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Slug --}}
                            <div class="form-group @error('slug') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Slug
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <span class="fa fa-link"></span>
                                        </span>
                                        <input type="text" name="slug" id="slug" class="form-control"
                                            value="{{ old('slug', $subjects->slug) }}"
                                            placeholder="tu-dong-theo-school-name">
                                    </div>
                                    <small class="text-muted">
                                        Slug dùng cho URL, có thể chỉnh sửa
                                    </small>

                                    @error('slug')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Code --}}
                            <div class="form-group @error('code') has-error @enderror">
                                <label class="col-md-3 control-label">Subject Code</label>
                                <div class="col-md-6">
                                    <input type="text" name="code" class="form-control"
                                        value="{{ old('code', $subjects->code) }}">
                                </div>
                            </div>

                            {{-- Type --}}
                            <div class="form-group @error('type') has-error @enderror">
                                <label class="col-md-3 control-label">Type *</label>
                                <div class="col-md-6">
                                    <select name="type" class="form-control">
                                        <option value="">-- Select --</option>
                                        @foreach (['Theory', 'Practical', 'Combined', 'Elective'] as $type)
                                            <option value="{{ $type }}"
                                                {{ old('type', $subjects->type) == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Credit --}}
                            <div class="form-group @error('credit') has-error @enderror">
                                <label class="col-md-3 control-label">Credit</label>
                                <div class="col-md-6">
                                    <input type="number" name="credit" class="form-control"
                                        value="{{ old('credit', $subjects->credit) }}">
                                    <small class="text-muted">Để trống với Tiểu học / THCS / THPT</small>
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="form-group">
                                <label class="col-md-3 control-label">Description</label>
                                <div class="col-md-6">
                                    <textarea name="description" class="form-control" rows="4">{{ old('description', $subjects->description) }}</textarea>
                                </div>
                            </div>

                            {{-- Status --}}
                            <div class="form-group @error('status') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Status <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <select name="status" class="form-control">
                                        <option value="">-- Select status --</option>
                                        <option value="1"
                                            {{ old('status', $subjects->status) == '1' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="0"
                                            {{ old('status', $subjects->status) == '0' ? 'selected' : '' }}>
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
                                Reset
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

    <!-- END PAGE CONTENT WRAPPER -->
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('js/demo_tables.js') }}"></script>

    <script>
        function slugify(text) {
            return text.toString().toLowerCase()
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
                .replace(/đ/g, 'd')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        let slugEdited = false;

        const nameInput = document.querySelector('input[name="name"]');
        const slugInput = document.getElementById('slug');

        slugInput.addEventListener('input', function() {
            slugEdited = true;
        });

        nameInput.addEventListener('input', function() {
            if (!slugEdited) {
                slugInput.value = slugify(this.value);
            }
        });
    </script>
@endsection
