@extends('backend.layouts.app')

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('cpanel.admin') }}">Home</a></li>
        <li class="active">Edit</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE TITLE -->
    <div class="page-title">
        <h2><span class="fa fa-arrow-circle-o-left"></span> Edit Admin</h2>
    </div>
    <!-- END PAGE TITLE -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">

                <form action="{{ route('cpanel.admin.update' , $adminlist->slug) }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                    @method('PUT')

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Edit Admin</h3>
                        </div>

                        <div class="panel-body">

                            {{-- School Name --}}
                            <div class="form-group @error('name') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Admin Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <span class="fa fa-pencil"></span>
                                        </span>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name', $adminlist->name) }}">
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
                                            value="{{ old('slug', $adminlist->slug) }}"
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


                            {{-- Profile Pic --}}
                            <div class="form-group @error('profile_pic') has-error @enderror">
                                <label class="col-md-3 control-label">Profile Pic</label>

                                <div class="col-md-6">

                                    <div class="row">

                                        {{-- Ảnh cũ --}}
                                        <div class="col-xs-6 text-center">
                                            <p><strong>Current Image</strong></p>

                                            @if ($adminlist->profile_pic)
                                                <img src="{{ $adminlist->profile_pic }}" id="current-image"
                                                    class="img-thumbnail" style="width:120px; height:auto;">
                                            @else
                                                <p class="text-muted">No image</p>
                                            @endif
                                        </div>

                                        {{-- Ảnh mới --}}
                                        <div class="col-xs-6 text-center">
                                            <p><strong>New Image Preview</strong></p>

                                            <img id="preview-image" src="" class="img-thumbnail"
                                                style="width:120px; height:auto; display:none;">
                                            <p id="preview-text" class="text-muted">Choose image to preview</p>
                                        </div>

                                    </div>

                                    <br>

                                    <input type="file" name="profile_pic" class="form-control" style="padding:5px;"
                                        accept="image/*" onchange="previewProfileImage(this)">

                                    @error('profile_pic')
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
                                        <span class="input-group-addon">
                                            <span class="fa fa-envelope"></span>
                                        </span>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ old('email', $adminlist->email) }}"
                                            placeholder="Email (có thể bỏ trống nếu không đổi)">

                                    </div>
                                    @error('email')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Password (KHÔNG bắt buộc khi edit) --}}
                            <div class="form-group @error('password') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Password
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <span class="fa fa-unlock-alt"></span>
                                        </span>
                                        <input type="password" name="password" class="form-control"
                                            placeholder="Leave blank if not change">
                                    </div>
                                    @error('password')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Address --}}
                            <div class="form-group @error('address') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Address <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <textarea name="address" class="form-control" rows="3">{{ old('address', $adminlist->address) }}</textarea>
                                    @error('address')
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
                                        <option value="1"
                                            {{ old('status', $adminlist->status) == '1' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="0"
                                            {{ old('status', $adminlist->status) == '0' ? 'selected' : '' }}>
                                            Inactive
                                        </option>
                                    </select>
                                    @error('status')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group @error('is_admin') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Role <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <select name="is_admin" class="form-control">
                                        <option value="">-- Select status --</option>
                                        <option value="1"
                                            {{ old('is_admin', $adminlist->is_admin) == '1' ? 'selected' : '' }}>
                                            Super Admin
                                        </option>
                                        <option value="2"
                                            {{ old('is_admin', $adminlist->is_admin ) == '2' ? 'selected' : '' }}>
                                            Admin
                                        </option>
                                    </select>
                                    @error('is_admin')
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
        function previewProfileImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                    document.getElementById('preview-image').style.display = 'block';
                    document.getElementById('preview-text').style.display = 'none';
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

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
