<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\CloudinaryService;
use App\Models\User;
use Log;

class ClassController extends Controller
{
    public function class_list(Request $request)
    {
        $query = SchoolClass::where('is_delete', 1)
            ->where('created_by_id', Auth::id());

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('grade')) {
            $query->where('grade', $request->grade);
        }

        $classes = $query->paginate(10);

        $meta_title = "Class List";
        return view('backend.class.index', compact('meta_title', 'classes'));
    }


    public function class_create()
    {
        $meta_title = 'Create Class';
        return view('backend.class.create', compact('meta_title'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name'   => 'required|string|max:255',
            'slug'   => 'nullable|string|max:255|unique:class,slug',
            'grade'  => 'nullable|integer|min:1|max:12',
            'status' => 'required|in:0,1',
        ]);

        SchoolClass::create([
            'name'          => $request->name,
            'slug'          => $request->slug ?: Str::slug($request->name),
            'grade'         => $request->grade,
            'status'        => $request->status,
            'created_by_id' => Auth::id(),
            'is_delete'     => 1,
        ]);

        return redirect()
            ->route('cpanel.class')
            ->with('success', 'Thêm lớp học thành công');
    }


    public function edit_class($slug)
    {
        $classes = SchoolClass::where('slug', $slug)->firstOrFail();
        $meta_title = 'Edit Class';
        return view('backend.class.edit', compact('meta_title', 'classes'));
    }

    public function update(Request $request, $slug)
    {
        $classes = SchoolClass::where('slug', $slug)
            ->where('created_by_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'name'   => 'required|string|max:255',
            'slug'   => 'nullable|string|max:255|unique:class,slug,' . $classes->id,
            'grade'  => 'nullable|integer|min:1|max:12',
            'status' => 'required|in:0,1',
        ]);

        $classes->update([
            'name'   => $request->name,
            'slug'   => $request->slug ?: Str::slug($request->name),
            'grade'  => $request->grade,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('cpanel.class')
            ->with('success', 'Cập nhật lớp học thành công');
    }

    public function toggleStatus($id)
    {
        $classes = SchoolClass::findOrFail($id);

        $classes->status = $classes->status == 1 ? 0 : 1;
        $classes->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công');
    }

    public function destroy(SchoolClass $class)
    {
        $class->delete();

        return redirect()
            ->route('cpanel.class')
            ->with('success', 'Lớp đã được xóa thành công');
    }
}
