<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title">Danh sách phân công</h4>
    </div>
    <div class="panel-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Lớp</th>
                    <th>Môn</th>
                    <th width="80">Xóa</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($assigned as $row)
                    <tr>
                        <td>{{ $row->class->name }}</td>
                        <td>{{ $row->subject->name }}</td>
                        <td>
                            <form method="POST" action="{{ route('cpanel.teacher.assign.delete', $row->id) }}"
                                onsubmit="return confirm('Xóa phân công này?')">
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
                        <td colspan="3">Chưa có phân công</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
