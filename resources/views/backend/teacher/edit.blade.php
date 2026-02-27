@extends('backend.layouts.app')

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('cpanel.teacher') }}">Home</a></li>
        <li class="active">Edit</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE TITLE -->
    <div class="page-title">
        <h2><span class="fa fa-arrow-circle-o-left"></span> Edit Teacher</h2>
    </div>
    <!-- END PAGE TITLE -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">

                <form class="form-horizontal" method="POST" action="{{ route('cpanel.teacher.update', $teacherlist->slug) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edit Teacher</h3>
                        </div>

                        <div class="panel-body">

                            @if(Auth::user()->is_admin == 1 || Auth::user()->is_admin == 2)
                                <div class="form-group @error('school_id') has-error @enderror">
                                    <label class="col-md-3 control-label">
                                        School Name <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-university"></span></span>
                                            <select name="school_id" class="form-control">
                                                <option value="">-- Select School --</option>
                                                @foreach ($schools as $tl)
                                                    <option value="{{ $tl->id }}"
                                                        {{ old('school_id', $teacherlist->created_by_id) == $tl->id ? 'selected' : '' }}>
                                                        {{ $tl->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
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
                                            value="{{ old('name', $teacherlist->name) }}">
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
                                            value="{{ old('last_name', $teacherlist->last_name) }}">
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
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-venus-mars"></span></span>
                                        <select name="gender" class="form-control">
                                            <option value="">-- Select Gender --</option>
                                            <option value="male"
                                                {{ old('gender', $teacherlist->gender) == 'male' ? 'selected' : '' }}>
                                                Male
                                            </option>
                                            <option value="female"
                                                {{ old('gender', $teacherlist->gender) == 'female' ? 'selected' : '' }}>
                                                Female
                                            </option>
                                        </select>
                                    </div>
                                    @error('gender')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Date of Birth --}}
                            <div class="form-group @error('date_of_birth') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Date of Birth <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-birthday-cake"></span></span>
                                        <input type="date" name="date_of_birth" class="form-control"
                                            value="{{ old('date_of_birth', $teacherlist->date_of_birth?->format('Y-m-d')) }}">
                                    </div>
                                    @error('date_of_birth')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Marital Status --}}
                            <div class="form-group @error('marital_status') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Marital Status <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-heart"></span></span>
                                        <input type="text" name="marital_status" class="form-control"
                                            value="{{ old('marital_status', $teacherlist->marital_status) }}"
                                            placeholder="Single/Married/Divorced">
                                    </div>
                                    @error('marital_status')
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
                                            value="{{ old('email', $teacherlist->email) }}">
                                    </div>
                                    @error('email')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Phone --}}
                            <div class="form-group @error('phone') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Phone Number <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-phone"></span></span>
                                        <input type="text" name="phone" class="form-control"
                                            value="{{ old('phone', $teacherlist->phone) }}">
                                    </div>
                                    @error('phone')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Current Address --}}
                            <div class="form-group @error('address') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Current Address <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-home"></span></span>
                                        <textarea name="address" class="form-control" rows="3">{{ old('address', $teacherlist->address) }}</textarea>
                                    </div>
                                    @error('address')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Permanent Address --}}
                            <div class="form-group @error('permanent_address') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Permanent Address <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-building"></span></span>
                                        <textarea name="permanent_address" class="form-control" rows="3">{{ old('permanent_address', $teacherlist->permanent_address) }}</textarea>
                                    </div>
                                    @error('permanent_address')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Qualification --}}
                            <div class="form-group @error('qualification') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Qualification
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-graduation-cap"></span></span>
                                        <textarea name="qualification" class="form-control" rows="3" placeholder="Educational qualifications">{{ old('qualification', $teacherlist->qualification) }}</textarea>
                                    </div>
                                    @error('qualification')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Work Experience --}}
                            <div class="form-group @error('work_experience') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Work Experience
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-briefcase"></span></span>
                                        <textarea name="work_experience" class="form-control" rows="3" placeholder="Teaching experience details">{{ old('work_experience', $teacherlist->work_experience) }}</textarea>
                                    </div>
                                    @error('work_experience')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Note --}}
                            <div class="form-group @error('note') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Note
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-sticky-note"></span></span>
                                        <textarea name="note" class="form-control" rows="3">{{ old('note', $teacherlist->note) }}</textarea>
                                    </div>
                                    @error('note')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Date of Joining --}}
                            <div class="form-group @error('date_of_joining') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Date of Joining <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-calendar-check-o"></span></span>
                                        <input type="date" name="date_of_joining" class="form-control"
                                            value="{{ old('date_of_joining', $teacherlist->date_of_joining?->format('Y-m-d')) }}">
                                    </div>
                                    @error('date_of_joining')
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
                                        <input type="file" name="profile_pic" class="form-control" style="padding:5px;">
                                    </div>
                                    @error('profile_pic')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror

                                    @if ($teacherlist->profile_pic)
                                        <div style="margin-top:10px;">
                                            <img src="{{ asset($teacherlist->profile_pic) }}" alt="Current Profile"
                                                style="width:100px;border-radius:6px;">
                                            <div class="text-muted small">Current profile picture</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Password (không bắt đổi lại) --}}
                            <div class="form-group @error('password') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Password
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-lock"></span></span>
                                        <input type="password" name="password" class="form-control"
                                            placeholder="Leave blank if not change">
                                    </div>
                                    <small class="text-muted">Để trống nếu không muốn thay đổi mật khẩu</small>
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
                                            value="{{ old('slug', $teacherlist->slug) }}">
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
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-power-off"></span></span>
                                        <select name="status" class="form-control">
                                            <option value="">-- Select status --</option>
                                            <option value="1"
                                                {{ old('status', $teacherlist->status) == 1 ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="0"
                                                {{ old('status', $teacherlist->status) == 0 ? 'selected' : '' }}>
                                                Inactive
                                            </option>
                                        </select>
                                    </div>
                                    @error('status')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="panel-footer">
                            <a href="{{ route('cpanel.teacher') }}" class="btn btn-default"><span class="fa fa-arrow-left"></span> Back</a>
                            <button type="submit" class="btn btn-primary pull-right"><span class="fa fa-check"></span> Update</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
    <!-- END PAGE CONTENT WRAPPER -->
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