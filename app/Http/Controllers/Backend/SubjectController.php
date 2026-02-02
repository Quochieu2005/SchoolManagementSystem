<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
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
}
