@extends('backend.layouts.app')

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('cpanel.admin') }}">Home</a></li>
        <li class="active">List</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE TITLE -->
    <div class="page-title">
        <h2><span class="fa fa-arrow-circle-o-left"></span> Create Class</h2>
    </div>
    <!-- END PAGE TITLE -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">

                <form class="form-horizontal" method="POST" action="{{ route('cpanel.class.store') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Create Class</h3>
                        </div>

                        <div class="panel-body">

                            {{-- Name --}}
                            <div class="form-group @error('name') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Name <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <span class="fa fa-pencil"></span>
                                        </span>
                                        <input type="text" name="name" class="form-control"
                                            value="{{ old('name') }}">
                                    </div>
                                    @error('name')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Grade --}}
                            <div class="form-group @error('grade') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Grade (Lớp)
                                </label>
                                <div class="col-md-6">
                                    <select name="grade" class="form-control">
                                        <option value="">-- Đại học / Không áp dụng --</option>

                                        @for ($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('grade') == $i ? 'selected' : '' }}>
                                                Lớp {{ $i }}
                                            </option>
                                        @endfor
                                    </select>

                                    <small class="text-muted">
                                        1–5: Tiểu học | 6–9: THCS | 10–12: THPT
                                    </small>

                                    @error('grade')
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
                                            value="{{ old('slug') }}" placeholder="tu-dong-theo-class-name">
                                    </div>
                                    <small class="text-muted">
                                        Slug dùng cho URL, có thể chỉnh sửa
                                    </small>
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

        document.getElementById('slug').addEventListener('input', function() {
            slugEdited = true;
        });

        document.querySelector('input[name="name"]').addEventListener('input', function() {
            if (!slugEdited) {
                document.getElementById('slug').value = slugify(this.value);
            }
        });
    </script>

    <script>
        const gradeSelect = document.querySelector('select[name="grade"]');

        if (gradeSelect) {
            gradeSelect.addEventListener('change', function() {
                let level = '';

                const grade = parseInt(this.value);

                if (!grade) level = 'Đại học';
                else if (grade <= 5) level = 'Tiểu học';
                else if (grade <= 9) level = 'THCS';
                else level = 'THPT';

                this.title = 'Cấp học: ' + level;
            });
        }
    </script>
@endsection
