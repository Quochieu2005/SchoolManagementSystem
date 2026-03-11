<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Thêm phân công</h4>
    </div>

    <div class="panel-body">

        <form method="POST"
              action="{{ route('teacher.assign.subject.store', $teacher->slug) }}">
            @csrf

            <div class="form-group">
                <label>Lớp <span class="text-danger">*</span></label>
                <select name="class_id" class="form-control" required>
                    <option value="">-- Không chọn lớp (dạy toàn khối) --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Môn học <span class="text-danger">*</span></label>
                <select name="subject_id" class="form-control" required>
                    <option value="">-- Chọn môn --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                    @endforeach
                </select>
            </div>

            <button class="btn btn-primary btn-block">
                Phân công
            </button>
        </form>
    </div>
</div>
