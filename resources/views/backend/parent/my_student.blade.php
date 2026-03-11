@extends('backend.layouts.app')

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('cpanel.dashboard') }}">Home</a></li>
        <li class="active"><a href="{{ route('cpanel.parent') }}">Parent</a></li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE TITLE -->
    <div class="page-title">
        <h2><span class="fa fa-arrow-circle-o-left"></span> Parent My Student</h2>
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
                        <h3 class="panel-title">Search Student</h3>
                    </div>

                    <div class="panel-body">
                        <form action="{{ route('cpanel.parent.my.student', $parent->slug) }}" method="GET">
                            <div class="row">

                                <div class="col-md-3">
                                    <label>Student Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ request('name') }}"
                                        placeholder="Student Name">
                                </div>

                                <div class="col-md-3">
                                    <label>Email</label>
                                    <input type="text" name="email" class="form-control" value="{{ request('email') }}"
                                        placeholder="Email">
                                </div>

                                <div class="col-md-3" style="margin-top:25px;">
                                    <button type="submit" class="btn btn-primary">
                                        Search
                                    </button>

                                    <a href="{{ route('cpanel.parent.my.student', $parent->slug) }}"
                                        class="btn btn-success">
                                        Reset
                                    </a>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>


                <!-- Bảng SEARCH RESULT -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Search Student List</h3>
                    </div>

                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-actions">
                                <thead>
                                    <tr>
                                        <th width="70">Profile</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Gender</th>
                                        <th>Parent Name</th>
                                        <th>Created Date</th>
                                        <th>Status</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($searchStudents && $searchStudents->count())
                                        @foreach ($searchStudents as $student)
                                            <tr>
                                                <td class="text-center">
                                                    @if ($student->profile_pic)
                                                        <img src="{{ asset($student->profile_pic) }}" width="50"
                                                            height="50" style="object-fit:cover;border-radius:6px;">
                                                    @else
                                                        <span class="text-muted">No Image</span>
                                                    @endif
                                                </td>
                                                <td><strong>{{ $student->name }} {{ $student->last_name }}</strong></td>
                                                <td>{{ $student->email }}</td>
                                                <td>{{ $student->phone ?? '-' }}</td>
                                                <td>
                                                    @if ($student->gender === 'male')
                                                        <span class="label label-primary">Male</span>
                                                    @elseif($student->gender === 'female')
                                                        <span class="label label-danger">Female</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(!empty($student->getParent))
                                                        {{ $student->getParent->name }} {{ $student->getParent->last_name }}
                                                    @endif
                                                </td>
                                                <td>{{ $student->created_at ? $student->created_at->format('d-m-Y') : '-' }}
                                                </td>
                                                <td>
                                                    @if ($student->status)
                                                        <span class="label label-success">Active</span>
                                                    @else
                                                        <span class="label label-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('cpanel.parent.add.student', [
                                                        'parent_id' => $parent_id,
                                                        'user' => $student->slug,
                                                    ]) }}"
                                                        class="btn btn-primary btn-rounded btn-sm">
                                                        Add To Parent
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">
                                                @if ($request->filled('name') || $request->filled('email'))
                                                    No student found matching your search
                                                @else
                                                    Enter name or email to search
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Bảng MY STUDENTS (danh sách thật) -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">My Student List</h3>
                    </div>

                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-actions">
                                <thead>
                                    <tr>
                                        <th width="70">Profile</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Gender</th>
                                        <th>Created Date</th>
                                        <th>Status</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($myStudents as $student)
                                        <tr>
                                            <td class="text-center">
                                                @if ($student->profile_pic)
                                                    <img src="{{ asset($student->profile_pic) }}" width="50"
                                                        height="50" style="object-fit:cover;border-radius:6px;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td><strong>{{ $student->name }} {{ $student->last_name }}</strong></td>
                                            <td>{{ $student->email }}</td>
                                            <td>{{ $student->phone ?? '-' }}</td>
                                            <td>
                                                @if ($student->gender === 'male')
                                                    <span class="label label-primary">Male</span>
                                                @elseif($student->gender === 'female')
                                                    <span class="label label-danger">Female</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $student->created_at ? $student->created_at->format('d-m-Y') : '-' }}
                                            </td>
                                            <td>
                                                @if ($student->status)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <form
                                                    action="{{ route('cpanel.parent.my.student.delete', $student->slug) }}"
                                                    method="POST" onsubmit="return confirm('Are you sure?');">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button class="btn btn-danger btn-rounded btn-sm">
                                                        <span class="fa fa-times"></span>
                                                    </button>

                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">Record not found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
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
