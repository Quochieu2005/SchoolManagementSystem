@extends('backend.layouts.app')

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('cpanel.dashboard') }}">Home</a></li>
        <li class="active"><a href="{{ route('cpanel.parent') }}">List</a></li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE TITLE -->
    <div class="page-title">
        <h2><span class="fa fa-arrow-circle-o-left"></span> Parent</h2>
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
                        <h3 class="panel-title">Parent Search</h3>
                    </div>

                    <div class="panel-body">
                        <form action="{{ route('cpanel.parent') }}" method="GET">

                            <div class="col-md-2">
                                <label>Parent Name</label>
                                <input type="text" class="form-control" name="name" value="{{ request('name') }}"
                                    placeholder="PARENT NAME">
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
                                    <a href="{{ route('cpanel.parent') }}" class="btn btn-success">Reset</a>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>

                <div class="panel panel-default">

                    <div class="panel-heading">
                        <h3 class="panel-title">Parent List</h3>
                        <a class="btn btn-primary pull-right" href="{{ route('cpanel.parent.add') }}">Create parent</a>
                    </div>

                    <div class="panel-body panel-body-table">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-actions">
                                <thead>
                                    <tr>
                                        
                                        @if (Auth::user()->is_admin == 1 || Auth::user()->is_admin == 2)
                                            <th>School Name</th>
                                        @endif
                                        <th width="70">Profile</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Occupation</th>
                                        <th>Gender</th>
                                        <th>Status</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($parents as $ps)
                                        <tr>
                                            @if (Auth::user()->is_admin == 1 || Auth::user()->is_admin == 2)
                                                <td>
                                                    {{ $ps->getCreatedBy->name ?? 'N/A' }}
                                                </td>
                                            @endif
                                            {{-- Profile --}}
                                            <td class="text-center">
                                                @if (!empty($ps->profile_pic))
                                                    <img src="{{ asset($ps->profile_pic) }}" alt="Parent Image"
                                                        width="50" height="50"
                                                        style="object-fit: cover; border-radius: 6px;">
                                                @else
                                                    <span class="text-muted">No Image</span>
                                                @endif
                                            </td>

                                            {{-- Full Name --}}
                                            <td>
                                                <strong>{{ $ps->name }} {{ $ps->last_name }}</strong>
                                            </td>

                                            {{-- Email --}}
                                            <td>{{ $ps->email }}</td>

                                            {{-- Phone --}}
                                            <td>{{ $ps->phone ?? '-' }}</td>

                                            {{-- Address --}}
                                            <td title="{{ $ps->permanent_address }}">
                                                {{ \Illuminate\Support\Str::limit($ps->permanent_address ?? '-', 30, '...') }}
                                            </td>

                                            {{-- Occupation --}}
                                            <td>{{ $ps->occupation ?? '-' }}</td>

                                            {{-- Gender --}}
                                            <td>
                                                @if ($ps->gender === 'male')
                                                    <span class="label label-primary">Male</span>
                                                @elseif ($ps->gender === 'female')
                                                    <span class="label label-danger">Female</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>

                                            {{-- Status --}}
                                            <td>
                                                @if ($ps->status)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-danger">Inactive</span>
                                                @endif
                                            </td>

                                            {{-- Actions --}}
                                            <td class="text-center">
                                                <a href="{{ route('cpanel.parent.edit', $ps->slug) }}"
                                                    class="btn btn-default btn-rounded btn-sm" title="Edit">
                                                    <span class="fa fa-pencil"></span>
                                                </a>

                                                <form action="{{ route('cpanel.parent.delete', $ps->slug) }}"
                                                    method="POST" style="display:inline-block"
                                                    onsubmit="return confirm('Are you sure you want to delete this parent?');">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button class="btn btn-danger btn-rounded btn-sm" title="Delete">
                                                        <span class="fa fa-times"></span>
                                                    </button>
                                                </form>

                                                <a href="{{ route('cpanel.parent.my.student', $ps->slug) }}"
                                                    class="btn btn-primary btn-rounded btn-sm" title="My Student">
                                                    My Student
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">
                                                No Parent Found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="pull-right">
                    {{ $parents->links() }}
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
