@extends('backend.layouts.app')

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('cpanel.admin') }}">Home</a></li>
        <li class="active">My Account</li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE TITLE -->
    <div class="page-title">
        <h2><span class="fa fa-arrow-circle-o-left"></span> My Account</h2>
    </div>
    <!-- END PAGE TITLE -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                @include('_massage')
                <form class="form-horizontal" method="POST" action="{{ route('cpanel.update.my.account') }}"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">My Account</h3>
                        </div>

                        <div class="panel-body">

                            @if (Auth::user()->is_admin === 3)
                                {{-- Name --}}
                                <div class="form-group @error('name') has-error @enderror">
                                    <label class="col-md-3 control-label">
                                        School Name <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name', $user->name) }}">
                                        </div>
                                        @error('name')
                                            <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @else
                                {{-- First Name --}}
                                <div class="form-group @error('name') has-error @enderror">
                                    <label class="col-md-3 control-label">
                                        First Name <span class="required">*</span>
                                    </label>
                                    <div class="col-md-6">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="fa fa-user"></span></span>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name', $user->name) }}">
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
                                                value="{{ old('last_name', $user->last_name) }}">
                                        </div>
                                        @error('last_name')
                                            <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            {{-- Email --}}
                            <div class="form-group @error('email') has-error @enderror">
                                <label class="col-md-3 control-label">
                                    Email <span class="required">*</span>
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-envelope"></span></span>
                                        <input type="email" name="email" class="form-control"
                                            value="{{ old('email', $user->email) }}">
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
                                            value="{{ old('phone', $user->phone) }}">
                                    </div>
                                    @error('phone')
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

                                    @if ($user->profile_pic)
                                        <div style="margin-top:10px;">
                                            <img src="{{ asset($user->profile_pic) }}" alt="Current Profile"
                                                style="width:100px;border-radius:6px;">
                                            <div class="text-muted small">Current profile picture</div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="panel-footer">
                            <button type="submit" class="btn btn-primary pull-right">
                                Update Account
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
