@extends('backend.layouts.app')

@section('content')
    <!-- START BREADCRUMB -->
    <ul class="breadcrumb">
        <li><a href="{{ route('cpanel.dashboard') }}">Home</a></li>
        <li class="active"><a href="{{ route('cpanel.assign.subject') }}">Class Timetable</a></li>
    </ul>
    <!-- END BREADCRUMB -->

    <!-- PAGE TITLE -->
    <div class="page-title">
        <h2><span class="fa fa-arrow-circle-o-left"></span> Class Timetable</h2>
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
                        <h3 class="panel-title">Search Class Timetable</h3>
                    </div>

                    <div class="panel-body">
                        <form method="GET" action="{{ route('cpanel.class.timetable') }}" class="mb-3">
                            <div class="row">

                                {{-- Class --}}
                                <div class="col-md-4">
                                    <select name="class_id" id="class_id" class="form-control" required>
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
                                    <select name="subject_id" id="subject_id" class="form-control">
                                        <option value="">-- Select Subject --</option>
                                        @foreach ($selectedSubjects as $subject)
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
                                    <a href="{{ route('cpanel.class.timetable') }}" class="btn btn-secondary">Reset</a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                {{-- LIST --}}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Class Timetable List</h3>
                    </div>

                    <div class="panel-body panel-body-table">
                        <form action="{{ route('cpanel.class.timetable.save') }}" method="POST" class="form-horizontal"
                            enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="subject_id" value="{{ Request::get('subject_id') }}">
                            <input type="hidden" name="class_id" value="{{ Request::get('class_id') }}">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-actions">
                                    <thead>
                                        <tr>
                                            <th>Week Name</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Room Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($result as $weekId => $rt)
                                            <tr>
                                                <td>{{ $rt['week_name'] }}</td>

                                                <td>
                                                    <input type="hidden"
                                                        name="timetable[{{ $rt['week_name'] }}][week_name]"
                                                        value="{{ $rt['week_name'] }}">
                                                    <input type="time" value="{{ $rt['start_time'] }}"
                                                        class="form-control"
                                                        name="timetable[{{ $rt['week_name'] }}][start_time]">
                                                </td>

                                                <td>
                                                    <input type="time" value="{{ $rt['end_time'] }}"
                                                        class="form-control"
                                                        name="timetable[{{ $rt['week_name'] }}][end_time]">
                                                </td>

                                                <td>
                                                    <input type="text" value="{{ $rt['room_number'] }}"
                                                        class="form-control"
                                                        name="timetable[{{ $rt['week_name'] }}][room_number]">
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>

                                </table>

                                @if (!empty(Request::get('subject_id')) && !empty(Request::get('class_id')))
                                    <div style="text-align: right ; padding: 20px;">
                                        <button class="btn btn-success">Save Timetable</button>
                                    </div>
                                @endif

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('js/demo_tables.js') }}"></script>

    <script>
        function loadSubjects(classId) {

            if (!classId) {
                $('#subject_id').html('<option value="">-- Select Subject --</option>');
                return;
            }

            $('#subject_id').html('<option>Loading...</option>');

            $.ajax({
                url: "{{ url('get-subjects-by-class') }}",
                type: "GET",
                data: {
                    class_id: classId
                },
                success: function(data) {
                    let html = '<option value="">-- Select Subject --</option>';

                    data.forEach(function(item) {
                        html += `<option value="${item.id}">${item.name}</option>`;
                    });

                    $('#subject_id').html(html);
                }
            });
        }

        $('#class_id').change(function() {
            let classId = $(this).val();
            loadSubjects(classId);
        });
    </script>
@endsection
