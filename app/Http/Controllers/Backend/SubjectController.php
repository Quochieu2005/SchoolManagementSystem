<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ClassSubject;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use App\Models\SubjectModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use Log;

class SubjectController extends Controller
{
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

    /* ===================== CREATE ===================== */
    public function subject_create()
    {
        $meta_title = 'Create Subject';
        return view('backend.subject.create', compact('meta_title'));
    }

    /* ===================== STORE ===================== */
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

    /* ===================== EDIT ===================== */
    public function edit_subject($slug)
    {
        $subjects = SubjectModel::where('slug', $slug)
            ->where('created_by_id', Auth::id())
            ->firstOrFail();

        $meta_title = 'Edit Subject';
        return view('backend.subject.edit', compact('meta_title', 'subjects'));
    }

    /* ===================== UPDATE ===================== */
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

    /* ===================== TOGGLE STATUS ===================== */
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

        // 🔍 Filter theo class
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
        // dd($request->all());
        $request->validate([
            'class_id' => 'required',
            'subject_id' => 'required',
            'status' => 'required|in:0,1',
        ]);

        if (!empty($request->class_id) && !empty($request->subject_id)) {

            foreach ($request->subject_id as $subject_id) {

                if (!empty($subject_id)) {

                    $check = ClassSubject::where('class_id', $request->class_id)
                        ->where('subject_id', $subject_id)
                        ->where('is_delete', 1)
                        ->first();

                    if ($check) {
                        continue;
                    }

                    $subject = new ClassSubject;
                    $subject->class_id = $request->class_id;
                    $subject->subject_id = $subject_id;
                    $subject->status = $request->status;
                    $subject->created_by_id = Auth::id();
                    $subject->is_delete = 1;
                    $subject->save();
                }
            }
        }

        return redirect()->route('cpanel.assign.subject')
            ->with('success', 'Assign Subject Class Successfully Created!');
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
}
