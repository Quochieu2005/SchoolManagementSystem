@extends('backend.layouts.app')

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('cpanel.dashboard') }}">Home</a></li>
        <li class="active"><a href="{{ route('cpanel.assign.subject') }}">Assign Subject Class</a></li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE TITLE -->
    <div class="page-title">
        <h2><span class="fa fa-arrow-circle-o-left"></span> Assign Subject Class</h2>
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
                        <h3 class="panel-title">Assign Subject Class Search</h3>
                    </div>

                    <div class="panel-body">
                        <form method="GET" action="{{ route('cpanel.assign.subject') }}" class="mb-3">
                            <div class="row">

                                {{-- Class --}}
                                <div class="col-md-4">
                                    <select name="class_id" class="form-control">
                                        <option value="">-- Select Class --</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}"
                                                {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Subject --}}
                                <div class="col-md-4">
                                    <select name="subject_id" class="form-control">
                                        <option value="">-- Select Subject --</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->id }}"
                                                {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Button --}}
                                <div class="col-md-4">
                                    <button class="btn btn-primary">Filter</button>
                                    <a href="{{ route('cpanel.assign.subject') }}" class="btn btn-secondary">Reset</a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                {{-- LIST --}}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Assign Subject Class List</h3>
                        <a class="btn btn-primary pull-right" href="{{ route('cpanel.assign.subject.add') }}">
                            Create Assign Subject
                        </a>
                    </div>

                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-actions">
                                <thead>
                                    <tr>
                                        <th>Class</th>
                                        <th>Subject Name</th>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Credit</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($classSubjects as $cs)
                                        <tr>
                                            <td>{{ $cs->schoolClass->name ?? '-' }}</td>

                                            <td>
                                                <strong>{{ $cs->subject->name ?? '-' }}</strong>
                                            </td>

                                            <td>
                                                {{ $cs->subject->code ?? '-' }}
                                            </td>

                                            <td>
                                                <span class="label label-info">
                                                    {{ $cs->subject->type ?? '-' }}
                                                </span>
                                            </td>

                                            <td>
                                                {{ $cs->subject->credit ?? '-' }}
                                            </td>

                                            <td>
                                                {{-- @if (auth()->user()->is_admin === 3)
                                                    <a href="{{ route('cpanel.assign.subject.toggleStatus', $cs->id) }}">
                                                        @if ($cs->status)
                                                            <span class="label label-success">Active</span>
                                                        @else
                                                            <span class="label label-danger">Inactive</span>
                                                        @endif
                                                    </a>
                                                @else --}}
                                                @if ($cs->status)
                                                    <span class="label label-success">Active</span>
                                                @else
                                                    <span class="label label-danger">Inactive</span>
                                                @endif
                                                {{-- @endif --}}
                                            </td>

                                            <td>
                                                {{ optional($cs->created_at)->format('d-m-Y H:i') }}
                                            </td>

                                            <td>
                                                {{-- EDIT --}}
                                                <a href="{{ route('cpanel.assign.subject.edit', $cs->id) }}"
                                                    class="btn btn-default btn-rounded btn-sm">
                                                    <span class="fa fa-pencil"></span>
                                                </a>

                                                {{-- DELETE --}}
                                                <form action="{{ route('cpanel.assign.subject.delete', $cs->id) }}"
                                                    method="POST" style="display:inline-block"
                                                    onsubmit="return confirm('Are you sure?');">
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
                                            <td colspan="8" class="text-center">
                                                No subject found
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>

                <div class="pull-right">
                    {{ $classSubjects->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('js/demo_tables.js') }}"></script>
@endsection
