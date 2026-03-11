@extends('backend.layouts.app')

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('cpanel.admin') }}">Home</a></li>
        <li class="active">Create</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE TITLE -->
    <div class="page-title">
        <h2><span class="fa fa-arrow-circle-o-left"></span> Create Student</h2>
    </div>
    <!-- END PAGE TITLE -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form class="form-horizontal" method="POST" action="{{ route('cpanel.parent.store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Create Parent</h3>
                        </div>

                        <div class="panel-body">

                            @if (Auth::user()->is_admin == 1 || Auth::user()->is_admin == 2)
                                <div class="form-group @error('school_id') has-error @enderror">
                                    <label class="col-md-3 control-label">
                                        School Name <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <select name="school_id" class="form-control SchoolChange">
                                            <option value="">-- Select --</option>
                                            @foreach ($schools as $sl)
                                                <option value="{{ $sl->id }}">{{ $sl->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('school_id')
                                            <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            {{-- First Name --}}
                            <div class="form-group @error('name') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    First Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name') }}">
                                    </div>
                                    @error('name')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Last Name --}}
                            <div class="form-group @error('last_name') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Last Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                        <input type="text" name="last_name" class="form-control"
                                            value="{{ old('last_name') }}">
                                    </div>
                                    @error('last_name')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Gender --}}
                            <div class="form-group @error('gender') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Gender <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <select name="gender" class="form-control">
                                        <option value="">-- Select Gender --</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                                        </option>
                                    </select>
                                    @error('gender')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="form-group @error('email') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Email <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-envelope"></span></span>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ old('email') }}">
                                    </div>
                                    @error('email')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Mobile Number --}}
                            <div class="form-group @error('phone') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Mobile Number <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-phone"></span></span>
                                        <input type="text" name="phone" class="form-control"
                                            value="{{ old('phone') }}">
                                    </div>
                                    @error('phone')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Occupation --}}
                            <div class="form-group @error('occupation') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Occupation <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <input type="text" name="occupation" class="form-control"
                                        value="{{ old('occupation') }}">
                                    @error('occupation')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{--  Address --}}
                            <div class="form-group @error('permanent_address') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Address <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-building"></span></span>
                                        <textarea name="permanent_address" class="form-control" rows="3">{{ old('permanent_address') }}</textarea>
                                    </div>
                                    @error('permanent_address')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Profile Pic --}}
                            <div class="form-group @error('profile_pic') has-error @enderror">
                                <label class="col-md-3 control-label">Profile Pic</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-camera"></span></span>
                                        <input type="file" name="profile_pic" class="form-control"
                                            style="padding:5px;">
                                    </div>
                                    @error('profile_pic')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password --}}
                            <div class="form-group @error('password') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Password <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-lock"></span></span>
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                    @error('password')
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
                                        <span class="input-group-addon"><span class="fa fa-link"></span></span>
                                        <input type="text" name="slug" id="slug" class="form-control"
                                            value="{{ old('slug') }}" placeholder="tu-dong-theo-student-name">
                                    </div>
                                    <small class="text-muted">Slug dùng cho URL, có thể chỉnh sửa</small>
                                    @error('slug')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
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
                                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="panel-footer">
                            <button type="reset" class="btn btn-default"><span class="fa fa-eraser"></span> Clear
                                Form</button>
                            <button type="submit" class="btn btn-primary pull-right"><span class="fa fa-save"></span>
                                Submit</button>
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
                .normalize('NFD').replace(/[\u0300-\u036f]/g, '') // bỏ dấu tiếng Việt
                .replace(/đ/g, 'd')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        let slugEdited = false;

        // Khi user sửa slug tay → không auto nữa
        document.getElementById('slug').addEventListener('input', function() {
            slugEdited = true;
        });

        function updateSlug() {
            if (!slugEdited) {
                let name = document.querySelector('input[name="name"]').value;
                let lastName = document.querySelector('input[name="last_name"]').value;

                let fullName = (name + ' ' + lastName).trim();

                document.getElementById('slug').value = slugify(fullName);
            }
        }

        // Gõ First Name → update slug
        document.querySelector('input[name="name"]').addEventListener('input', updateSlug);

        // Gõ Last Name → update slug
        document.querySelector('input[name="last_name"]').addEventListener('input', updateSlug);
    </script>
@endsection
