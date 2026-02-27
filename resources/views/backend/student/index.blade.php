@extends('backend.layouts.app')

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('cpanel.dashboard') }}">Home</a></li>
        <li class="active"><a href="{{ route('cpanel.student') }}">List</a></li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE TITLE -->
    <div class="page-title">
        <h2><span class="fa fa-arrow-circle-o-left"></span> Student</h2>
    </div>
    <!-- END PAGE TITLE -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">

        <!-- START RESPONSIVE TABLES -->
        <div class="row">
            <div class="col-md-12">
                @include('_massage')
                <div class="panel panel-default">

                    <div class="panel-heading">
                        <h3 class="panel-title">Student Search</h3>
                    </div>

                    <div class="panel-body">
                        <form action="{{ route('cpanel.student') }}" method="GET">

                            <div class="col-md-2">
                                <label>Student Name</label>
                                <input type="text" class="form-control" name="name" value="{{ request('name') }}"
                                    placeholder="STUDENT NAME">
                            </div>

                            <div class="col-md-2">
                                <label>Email</label>
                                <input type="text" class="form-control" name="email" value="{{ request('email') }}"
                                    placeholder="EMAIL">
                            </div>

                            <div class="col-md-2">
                                <label>Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="">-- Select --</option>
                                    <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">-- Select --</option>
                                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>

                            <div style="clear: both;">
                                <br>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ route('cpanel.student') }}" class="btn btn-success">Reset</a>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>

                <div class="panel panel-default">

                    <div class="panel-heading">
                        <h3 class="panel-title">Student List</h3>
                        <a class="btn btn-primary pull-right" href="{{ route('cpanel.student.add') }}">Create student</a>
                    </div>

                    <div class="panel-body panel-body-table">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-actions">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Profile</th>
                                        <th>Student ID Number</th>
                                        <th>Full Name</th>

                                        @if (Auth::user()->is_admin == 1 || Auth::user()->is_admin == 2)
                                            <th>School Name</th>
                                        @endif

                                        <th>Class</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Gender</th>
                                        <th>Date of Birth</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($studentList as $sl)
                                        <tr>
                                            <td>{{ $sl->admission_number }}</td>
                                            <td>
                                                @if (!empty($sl->profile_pic))
                                                    <img src="{{ asset($sl->profile_pic) }}" alt="Student Image"
                                                        width="50" height="50"
                                                        style="object-fit: cover; border-radius: 6px;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td><strong>{{ $sl->roll_number ?? '-' }}</strong></td>
                                            <td>
                                                <strong>{{ $sl->name }} {{ $sl->last_name }}</strong>
                                            </td>
                                            @if (Auth::user()->is_admin == 1 || Auth::user()->is_admin == 2)
                                                <td>
                                                    {{ $sl->getCreatedBy->name ?? 'N/A' }}
                                                </td>
                                            @endif
                                            <td>
                                                {{ $sl->schoolClass->name ?? 'N/A' }}
                                            </td>
                                            <td>{{ $sl->email }}</td>
                                            <td>{{ $sl->phone ?? '-' }}</td>
                                            <td title="{{ $sl->address }}">
                                                {{ \Illuminate\Support\Str::limit($sl->address ?? '-', 30, '...') }}
                                            </td>
                                            <td>
                                                @if ($sl->gender === 'male')
                                                    <span class="label label-primary">Male</span>
                                                @elseif ($sl->gender === 'female')
                                                    <span class="label label-danger">Female</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            {{-- Date of birth --}}
                                            <td>
                                                {{ optional($sl->date_of_birth)->format('d-m-Y') ?? '-' }}
                                            </td>
                                            <td>
                                                @if ($sl->status)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-danger">Inactive</span>
                                                @endif
                                            </td>
                                            {{-- Actions --}}
                                            <td>
                                                {{-- Edit --}}
                                                <a href="{{ route('cpanel.student.edit', $sl->slug) }}"
                                                    class="btn btn-default btn-rounded btn-sm" title="Edit">
                                                    <span class="fa fa-pencil"></span>
                                                </a>

                                                {{-- Delete --}}
                                                <form action="{{ route('cpanel.student.delete', $sl->slug) }}"
                                                    method="POST" style="display:inline-block"
                                                    onsubmit="return confirm('Are you sure you want to delete this student?');">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button class="btn btn-danger btn-rounded btn-sm" title="Delete">
                                                        <span class="fa fa-times"></span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="14" class="text-center">
                                                No Student Found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="pull-right">
                    {{ $studentList->links() }}
                </div>
            </div>
        </div>
        <!-- END RESPONSIVE TABLES -->

        <!-- END PAGE CONTENT WRAPPER -->
    </div>

    <!-- END PAGE CONTENT WRAPPER -->
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('js/demo_tables.js') }}"></script>
@endsection
