@extends('backend.layouts.app')

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('cpanel.dashboard') }}">Home</a></li>
        <li class="active"><a href="{{ route('cpanel.teacher') }}">List</a></li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE TITLE -->
    <div class="page-title">
        <h2><span class="fa fa-arrow-circle-o-left"></span> Teacher</h2>
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
                        <h3 class="panel-title">Teacher Search</h3>
                    </div>

                    <div class="panel-body">
                        <form action="{{ route('cpanel.teacher') }}" method="GET">

                            <div class="col-md-2">
                                <label>Teacher Name</label>
                                <input type="text" class="form-control" name="name" value="{{ request('name') }}"
                                    placeholder="TEACHER NAME">
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
                                    <a href="{{ route('cpanel.teacher') }}" class="btn btn-success">Reset</a>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>

                <div class="panel panel-default">

                    <div class="panel-heading">
                        <h3 class="panel-title">Teacher List</h3>
                        <a class="btn btn-primary pull-right" href="{{ route('cpanel.teacher.add') }}">Create teacher</a>
                    </div>

                    <div class="panel-body panel-body-table">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-actions">
                                <thead>
                                    <tr>
                                        <th>Full Name</th>
                                        @if (Auth::user()->is_admin == 1 || Auth::user()->is_admin == 2)
                                            <th>School Name</th>
                                        @endif
                                        <th>Qualification</th>
                                        <th>Profile</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>Role</th>
                                        <th>Gender</th>
                                        <th>Date of Birth</th>
                                        <th>Date of Joining</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($teacherList as $tl)
                                        <tr>
                                            <td>
                                                <strong>{{ $tl->name }} {{ $tl->last_name }}</strong>
                                            </td>
                                            @if (Auth::user()->is_admin == 1 || Auth::user()->is_admin == 2)
                                                <td>
                                                    @if (!empty($tl->getCreatedBy))
                                                        {{ $tl->getCreatedBy->name }}
                                                    @endif
                                                </td>
                                            @endif
                                            <td>{{ $tl->qualification }}</td>
                                            <td>
                                                @if (!empty($tl->profile_pic))
                                                    <img src="{{ asset($tl->profile_pic) }}" alt="Teacher Image"
                                                        width="50" height="50"
                                                        style="object-fit: cover; border-radius: 6px;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>
                                            <td>{{ $tl->email }}</td>
                                            <td>
                                                {{ $tl->phone ?? '-' }}
                                            </td>
                                            <td title="{{ $tl->address }}">
                                                {{ \Illuminate\Support\Str::limit($tl->address, 30, '...') }}
                                            </td>
                                            <td>
                                                @if (auth()->user()->is_admin === 1 || auth()->user()->is_admin === 2)
                                                    <a href="{{ route('cpanel.teacher.toggleStatus', $tl->id) }}">
                                                        @if ($tl->status)
                                                            <span class="label label-success">Active</span>
                                                        @else
                                                            <span class="label label-danger">Inactive</span>
                                                        @endif
                                                    </a>
                                                @else
                                                    @if ($tl->status)
                                                        <span class="label label-success">Active</span>
                                                    @else
                                                        <span class="label label-danger">Inactive</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if ($tl->is_admin == 5)
                                                    <span class="label label-info">Teacher</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($tl->gender === 'male')
                                                    <span class="label label-primary">Male</span>
                                                @elseif ($tl->gender === 'female')
                                                    <span class="label label-danger">Female</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $tl->date_of_birth?->format('d-m-Y') ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $tl->date_of_joining?->format('d-m-Y') ?? '-' }}
                                            </td>

                                            {{-- Actions --}}
                                            <td>
                                                {{-- Edit --}}
                                                <a href="{{ route('cpanel.teacher.edit', $tl->slug) }}"
                                                    class="btn btn-default btn-rounded btn-sm" title="Edit">
                                                    <span class="fa fa-pencil"></span>
                                                </a>
                                                
                                                {{-- Assign Subject --}}
                                                @if(Auth::user()->is_admin === 1 || Auth::user()->is_admin === 2 || Auth::user()->is_admin === 3 || Auth::user()->is_admin === 4 )
                                                    <a href="{{ route('teacher.assign.subject', $tl->slug) }}"
                                                        class="btn btn-info btn-rounded btn-sm" title="Phân công giảng dạy">
                                                        <span class="fa fa-book"></span>
                                                    </a>
                                                @endif

                                                {{-- Delete --}}
                                                <form action="{{ route('cpanel.teacher.delete', $tl->slug) }}"
                                                    method="POST" style="display:inline-block"
                                                    onsubmit="return confirm('Are you sure you want to delete this teacher?');">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button class="btn btn-danger btn-rounded btn-sm" title="Delete">
                                                        <span class="fa fa-times"></span>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>
                <div class="pull-right">
                    {{ $teacherList->links() }}
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
