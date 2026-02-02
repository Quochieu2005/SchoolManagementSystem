@extends('backend.layouts.app')

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('cpanel.dashboard') }}">Home</a></li>
        <li class="active"><a href="{{ route('cpanel.subject') }}">List</a></li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE TITLE -->
    <div class="page-title">
        <h2><span class="fa fa-arrow-circle-o-left"></span> Subject</h2>
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
                        <h3 class="panel-title">Subject Search</h3>
                    </div>

                    <div class="panel-body">
                        <form action="{{ route('cpanel.subject') }}" method="GET">

                            <div class="col-md-2">
                                <label>ID</label>
                                <input type="text" class="form-control" name="id" value="{{ request('id') }}"
                                    placeholder="ID">
                            </div>

                            <div class="col-md-3">
                                <label>Subject Name</label>
                                <input type="text" class="form-control" name="name" value="{{ request('name') }}"
                                    placeholder="Subject Name">
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
                                <a href="{{ route('cpanel.subject') }}" class="btn btn-success">Reset</a>
                            </div>

                        </form>
                    </div>
                </div>

                {{-- LIST --}}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Subject List</h3>
                        <a class="btn btn-primary pull-right" href="{{ route('cpanel.subject.add') }}">
                            Create Subject
                        </a>
                    </div>

                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-actions">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Code</th>
                                        <th>Subject Name</th>
                                        <th>Type</th>
                                        <th>Credit</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th width="120">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse ($subjects as $st)
                                        <tr>
                                            <td>{{ $st->id }}</td>
                                            <td>
                                                {{ $st->code ?? '-' }}
                                            </td>
                                            <td>
                                                <strong>{{ $st->name }}</strong>
                                            </td>
                                            <td>
                                                <span class="label label-info">
                                                    {{ $st->type }}
                                                </span>
                                            </td>
                                            <td>
                                                {{ $st->credit !== null ? $st->credit : '-' }}
                                            </td>

                                            <td>
                                                @if (auth()->user()->is_admin === 3)
                                                    <a href="{{ route('cpanel.subject.toggleStatus', $st->id) }}">
                                                        @if ($st->status)
                                                            <span class="label label-success">Active</span>
                                                        @else
                                                            <span class="label label-danger">Inactive</span>
                                                        @endif
                                                    </a>
                                                @else
                                                    @if ($st->status)
                                                        <span class="label label-success">Active</span>
                                                    @else
                                                        <span class="label label-danger">Inactive</span>
                                                    @endif
                                                @endif
                                            </td>

                                            <td>
                                                {{ optional($st->created_at)->format('d-m-Y H:i') }}
                                            </td>

                                            <td>
                                                <a href="{{ route('cpanel.subject.edit', $st->slug) }}"
                                                    class="btn btn-default btn-rounded btn-sm">
                                                    <span class="fa fa-pencil"></span>
                                                </a>

                                                <form action="{{ route('cpanel.subject.delete', $st->slug) }}"
                                                    method="POST" style="display:inline-block"
                                                    onsubmit="return confirm('Are you sure you want to delete this subject?');">
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
                    {{ $subjects->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('js/demo_tables.js') }}"></script>
@endsection
