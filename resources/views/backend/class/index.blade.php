@extends('backend.layouts.app')

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('cpanel.dashboard') }}">Home</a></li>
        <li class="active"><a href="{{ route('cpanel.class') }}">List</a></li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE TITLE -->
    <div class="page-title">
        <h2><span class="fa fa-arrow-circle-o-left"></span> Class</h2>
    </div>
    <!-- END PAGE TITLE -->

    <!-- PAGE CONTENT WRAPPER -->
    <div class="page-content-wrap">

        <div class="row">
            <div class="col-md-12">
                @include('_massage')

                {{-- SEARCH --}}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Class Search</h3>
                    </div>

                    <div class="panel-body">
                        <form action="{{ route('cpanel.class') }}" method="GET">

                            <div class="col-md-2">
                                <label>ID</label>
                                <input type="text" class="form-control" name="id" value="{{ request('id') }}"
                                    placeholder="ID">
                            </div>

                            <div class="col-md-3">
                                <label>Class Name</label>
                                <input type="text" class="form-control" name="name" value="{{ request('name') }}"
                                    placeholder="Class Name">
                            </div>

                            <div class="col-md-2">
                                <label>Grade</label>
                                <select name="grade" class="form-control">
                                    <option value="">-- All --</option>
                                    <option value="1">Primary (1–5)</option>
                                    <option value="6">Secondary (6–9)</option>
                                    <option value="10">High School (10–12)</option>
                                    <option value="0">University</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">-- Select --</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                            </div>

                            <div style="clear: both;"></div>
                            <br>

                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <a href="{{ route('cpanel.class') }}" class="btn btn-success">Reset</a>
                            </div>

                        </form>
                    </div>
                </div>

                {{-- LIST --}}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Class List</h3>
                        <a class="btn btn-primary pull-right" href="{{ route('cpanel.class.add') }}">
                            Create Class
                        </a>
                    </div>

                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-actions">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Class Name</th>
                                        <th>Level</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($classes as $cl)
                                        <tr>
                                            <td>{{ $cl->id }}</td>
                                            <td><strong>{{ $cl->name }}</strong></td>
                                            <td>
                                                @if (is_null($cl->grade))
                                                    <span class="label label-primary">University</span>
                                                @elseif ($cl->grade <= 5)
                                                    <span class="label label-info">Primary</span>
                                                @elseif ($cl->grade <= 9)
                                                    <span class="label label-warning">Secondary</span>
                                                @else
                                                    <span class="label label-success">High School</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (auth()->user()->is_admin === 3)
                                                    <a href="{{ route('cpanel.class.toggleStatus', $cl->id) }}">
                                                        @if ($cl->status)
                                                            <span class="label label-success">Active</span>
                                                        @else
                                                            <span class="label label-danger">Inactive</span>
                                                        @endif
                                                    </a>
                                                @else
                                                    @if ($cl->status)
                                                        <span class="label label-success">Active</span>
                                                    @else
                                                        <span class="label label-danger">Inactive</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                {{ optional($cl->created_at)->format('d-m-Y H:i') }}
                                            </td>
                                            <td>
                                                <a href="{{ route('cpanel.class.edit', $cl->slug) }}"
                                                    class="btn btn-default btn-rounded btn-sm">
                                                    <span class="fa fa-pencil"></span>
                                                </a>

                                                <form action="{{ route('cpanel.class.delete', $cl->slug) }}" method="POST"
                                                    style="display:inline-block"
                                                    onsubmit="return confirm('Are you sure you want to delete this Class?');">
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
                                            <td colspan="5" class="text-center">
                                                No class found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="pull-right">
                    {{ $classes->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('js/demo_tables.js') }}"></script>
@endsection
