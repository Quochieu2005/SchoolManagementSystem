<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ClassSubject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use App\Models\SubjectModel;
use App\Models\timetable;
use App\Models\Timetable as ModelsTimetable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Week;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SubjectController extends Controller
{
    /// Subject
    public function subject_list(Request $request)
    {
        $query = SubjectModel::where('is_delete', 1)
            ->where('created_by_id', Auth::id());

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $subjects = $query->paginate(10);

        $meta_title = "Subject List";
        return view('backend.subject.index', compact('meta_title', 'subjects'));
    }

    public function subject_create()
    {
        $meta_title = 'Create Subject';
        return view('backend.subject.create', compact('meta_title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:subjects,slug',
            'type'        => 'nullable|string|max:50',
            'code'        => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'credit'      => 'nullable|integer|min:0',
            'status'      => 'required|in:0,1',
        ]);

        SubjectModel::create([
            'name'          => $request->name,
            'slug'          => $request->slug ?: Str::slug($request->name),
            'type'          => $request->type,
            'code'          => $request->code,
            'description'   => $request->description,
            'credit'        => $request->credit,
            'status'        => $request->status,
            'created_by_id' => Auth::id(),
            'is_delete'     => 1,
        ]);

        return redirect()
            ->route('cpanel.subject')
            ->with('success', 'Thêm môn học thành công');
    }

    public function edit_subject($slug)
    {
        $subjects = SubjectModel::where('slug', $slug)
            ->where('created_by_id', Auth::id())
            ->firstOrFail();

        $meta_title = 'Edit Subject';
        return view('backend.subject.edit', compact('meta_title', 'subjects'));
    }

    public function update(Request $request, $slug)
    {
        $subjects = SubjectModel::where('slug', $slug)
            ->where('created_by_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:subjects,slug,' . $subjects->id,
            'type'        => 'nullable|string|max:50',
            'code'        => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'credit'      => 'nullable|integer|min:0',
            'status'      => 'required|in:0,1',
        ]);

        $subjects->update([
            'name'        => $request->name,
            'slug'        => $request->slug ?: Str::slug($request->name),
            'type'        => $request->type,
            'code'        => $request->code,
            'description' => $request->description,
            'credit'      => $request->credit,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('cpanel.subject')
            ->with('success', 'Cập nhật môn học thành công');
    }

    public function toggleStatus($id)
    {
        $subjects = SubjectModel::findOrFail($id);

        $subjects->status = $subjects->status == 1 ? 0 : 1;
        $subjects->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công');
    }

    public function destroy(SubjectModel $subject)
    {
        $subject->delete();

        return redirect()
            ->route('cpanel.subject')
            ->with('success', 'Môn học đã được xóa thành công');
    }



    /* ===================== Assign Subject Class ===================== */


    public function list(Request $request)
    {
        $query = ClassSubject::with(['schoolClass', 'subject'])
            ->where('is_delete', 1)
            ->where('created_by_id', Auth::id());

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $classSubjects = $query->paginate(10);

        $classes = SchoolClass::where('status', 1)
            ->where('created_by_id', Auth::id())
            ->where('is_delete', 1)
            ->get();

        $subjects = SubjectModel::where('status', 1)
            ->where('created_by_id', Auth::id())
            ->where('is_delete', 1)
            ->get();

        $meta_title = "Assign Subject Class";

        return view('backend.assign-subject.index', compact(
            'meta_title',
            'classSubjects',
            'classes',
            'subjects'
        ));
    }

    public function create()
    {
        $user_id = Auth::id();

        $schoolClass = SchoolClass::where('status', 1)
            ->where('created_by_id', $user_id)
            ->where('is_delete', 1)
            ->orderBy('id', 'desc')
            ->get();

        $Subjects = SubjectModel::where('status', 1)
            ->where('created_by_id', $user_id)
            ->where('is_delete', 1)
            ->orderBy('id', 'desc')
            ->get();

        $meta_title = 'Create Assign Subject Class';

        return view('backend.assign-subject.create', compact('meta_title', 'schoolClass', 'Subjects'));
    }

    public function store_assign(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'subject_id' => 'required',
            'status' => 'required|in:0,1',
        ]);

        $duplicateSubjects = [];
        $newSubjects = [];
        $created = false;

        foreach ($request->subject_id as $subject_id) {

            if (!empty($subject_id)) {

                $subjectName = SubjectModel::where('id', $subject_id)->value('name');

                $check = ClassSubject::where('class_id', $request->class_id)
                    ->where('subject_id', $subject_id)
                    ->where('is_delete', 1)
                    ->first();

                if ($check) {
                    $duplicateSubjects[] = $subjectName;
                    continue;
                }

                $subject = new ClassSubject;
                $subject->class_id = $request->class_id;
                $subject->subject_id = $subject_id;
                $subject->status = $request->status;
                $subject->created_by_id = Auth::id();
                $subject->is_delete = 1;
                $subject->save();

                $newSubjects[] = $subjectName;
                $created = true;
            }
        }

        if (!empty($duplicateSubjects)) {

            $messages = [];

            $messages[] = 'Môn đã tồn tại: ' . implode(', ', $duplicateSubjects);

            if (!empty($newSubjects)) {
                $messages[] = 'Đã thêm mới: ' . implode(', ', $newSubjects);
            }

            $message = implode(' | ', $messages);

            if (!$created) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', $message);
            }

            return redirect()->route('cpanel.assign.subject')
                ->with('warning', $message);
        }

        return redirect()->route('cpanel.assign.subject')
            ->with('success', 'Đã thêm mới: ' . implode(', ', $newSubjects));
    }

    public function edit($id)
    {
        $user_id = Auth::id();

        $classes = SchoolClass::where('status', 1)
            ->where('created_by_id', $user_id)
            ->where('is_delete', 1)
            ->orderBy('id', 'desc')
            ->get();

        $subjects = SubjectModel::where('status', 1)
            ->where('created_by_id', $user_id)
            ->where('is_delete', 1)
            ->orderBy('id', 'desc')
            ->get();

        $assign = ClassSubject::where('id', $id)
            ->where('created_by_id', $user_id)
            ->firstOrFail();

        $meta_title = 'Edit Subject';

        return view('backend.assign-subject.edit', compact(
            'meta_title',
            'assign',
            'classes',
            'subjects'
        ));
    }

    public function update_assign(Request $request, $id)
    {
        $request->validate([
            'class_id' => 'required',
            'subject_id' => 'required',
            'status' => 'required|in:0,1',
        ]);
        $assign = ClassSubject::where('id', $id)
            ->where('created_by_id', Auth::id())
            ->firstOrFail();

        $check = ClassSubject::where('class_id', $request->class_id)
            ->where('subject_id', $request->subject_id)
            ->where('is_delete', 1)
            ->where('id', '!=', $assign->id)
            ->first();

        if ($check) {
            return redirect()->back()
                ->with('error', 'This subject is already assigned to this class!');
        }

        $assign->class_id = $request->class_id;
        $subject_id = is_array($request->subject_id)
            ? $request->subject_id[0]
            : $request->subject_id;

        $assign->subject_id = $subject_id;
        $assign->status = $request->status;
        $assign->update();

        return redirect()->route('cpanel.assign.subject')
            ->with('success', 'Assign Subject Updated Successfully!');
    }

    public function destroy_assign($id)
    {
        $assign = ClassSubject::where('id', $id)
            ->where('created_by_id', Auth::id())
            ->firstOrFail();

        $assign->is_delete = 0;
        $assign->save();

        return redirect()->back()
            ->with('success', 'Assign Subject Deleted Successfully!');
    }


    /// Class Timetable
    public function class_timetable(Request $request)
    {
        $query = ClassSubject::with(['schoolClass', 'subject'])
            ->where('is_delete', 1)
            ->where('created_by_id', Auth::id());

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $classSubjects = $query->paginate(10);

        $classes = SchoolClass::where('status', 1)
            ->where('created_by_id', Auth::id())
            ->where('is_delete', 1)
            ->get();

        $subjects = SubjectModel::where('status', 1)
            ->where('created_by_id', Auth::id())
            ->where('is_delete', 1)
            ->get();

        // load subject theo class
        $selectedSubjects = [];
        if ($request->filled('class_id')) {
            $selectedSubjects = ClassSubject::with('subject')
                ->where('class_id', $request->class_id)
                ->where('is_delete', 1)
                ->get()
                ->pluck('subject')
                ->filter();
        }

        // ===== FIX CHÍNH Ở ĐÂY =====
        $result = [];
        $weeks = Week::all();

        foreach ($weeks as $week) {

            // mặc định
            $result[$week->id] = [
                'week_name'   => $week->name,
                'start_time'  => null,
                'end_time'    => null,
                'room_number' => null,
            ];

            if (!empty($request->class_id) && !empty($request->subject_id)) {

                $timetable = Timetable::where('class_id', $request->class_id)
                    ->where('subject_id', $request->subject_id)
                    ->where('week_id', $week->id) // ✅ FIX QUAN TRỌNG
                    ->first();

                if ($timetable) {
                    $result[$week->id] = [
                        'week_name'   => $week->name,
                        'start_time'  => $timetable->start_time,
                        'end_time'    => $timetable->end_time,
                        'room_number' => $timetable->room_number,
                    ];
                }
            }
        }

        $meta_title = "Class Timetable";

        return view('backend.class_timetable.list', compact(
            'meta_title',
            'classSubjects',
            'classes',
            'subjects',
            'selectedSubjects',
            'result'
        ));
    }

    public function getSubjectsByClass(Request $request)
    {
        $subjects = ClassSubject::with('subject')
            ->where('class_id', $request->class_id)
            ->where('is_delete', 1)
            ->get()
            ->pluck('subject');
        return response()->json($subjects);
    }

    public function save_class_timetable(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|exists:class,id',
            'subject_id' => 'required|exists:subjects,id',
            'timetable' => 'required|array',
            'timetable.*.start_time' => 'nullable|date_format:H:i',
            'timetable.*.end_time' => 'nullable|date_format:H:i',
            'timetable.*.room_number' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {

            Timetable::where('class_id', $request->class_id)
                ->where('subject_id', $request->subject_id)
                ->delete();

            foreach ($request->timetable as $day => $data) {

                if (!empty($data['start_time']) && !empty($data['end_time'])) {

                    $week = Week::where('name', $day)->first();

                    if (!$week) {
                        continue;
                    }

                    Timetable::create([
                        'class_id' => $request->class_id,
                        'subject_id' => $request->subject_id,
                        'week_id' => $week->id,
                        'start_time' => $data['start_time'],
                        'end_time' => $data['end_time'],
                        'room_number' => $data['room_number'] ?? null,
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Saved!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
