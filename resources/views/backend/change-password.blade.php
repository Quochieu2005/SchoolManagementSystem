@extends('backend.layouts.app')

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('cpanel.admin') }}">Home</a></li>
        <li class="active">Change Password</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE TITLE -->
    <div class="page-title">
        <h2><span class="fa fa-arrow-circle-o-left"></span> Change Password</h2>
    </div>
    <!-- END PAGE TITLE -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                @include('_massage')
                <form class="form-horizontal" method="POST" action="{{ route('cpanel.update.password') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Change Password</h3>
                        </div>

                        <div class="panel-body">

                            {{-- Old Password --}}
                            <div class="form-group @error('old_password') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Old Password <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <span class="fa fa-key"></span>
                                        </span>
                                        <input type="password" name="old_password" class="form-control"
                                            value="">
                                    </div>
                                    @error('old_password')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- New Password --}}
                            <div class="form-group @error('new_password') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    New Password <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <span class="fa fa-key"></span>
                                        </span>
                                        <input type="password" name="new_password" class="form-control"
                                            value="">
                                    </div>
                                    @error('new_password')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            {{-- Confirm Password --}}
                            <div class="form-group @error('confirm_password') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Confirm Password <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <span class="fa fa-key"></span>
                                        </span>
                                        <input type="password" name="confirm_password" class="form-control"
                                            value="">
                                    </div>
                                    @error('confirm_password')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>



                        </div>

                        <div class="panel-footer">
                            <button type="submit" class="btn btn-primary pull-right">
                                Update Password
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
@endsection
