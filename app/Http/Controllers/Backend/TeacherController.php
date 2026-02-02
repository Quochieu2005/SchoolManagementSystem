<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Logs;
use App\Models\TeacherSubject;
use App\Models\SubjectModel;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\CloudinaryService;
use Log;

class TeacherController extends Controller
{
    protected CloudinaryService $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }
    public function teacher_list(Request $request)
    {
        $query = User::where('is_admin', 5);

        if ($request->filled('name')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%')
                    ->orWhere('last_name', 'like', '%' . $request->name . '%');
            });
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->filled('gender')) {
            $query->where('gender', 'like', '%' . $request->gender . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $teacherList = $query
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends($request->query());
        $meta_title = "Teacher List";
        return view('backend.teacher.index', compact('meta_title', 'teacherList'));
    }
    public function create_teacher()
    {
        $teachers = User::where('is_admin', 3)
            ->where('status', 1)
            ->where('trang_thai', 1)
            ->get();
        $meta_title = "Teacher Create";
        return view('backend.teacher.create', compact('meta_title', 'teachers'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name'              => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:users,slug',
            'email'             => 'required|email|unique:users,email',
            'phone'             => 'required|string|max:20',
            'gender'            => 'required|in:male,female',
            'marital_status'    => 'required|string|max:100',
            'date_of_birth'    => 'required|date',
            'date_of_joining'  => 'required|date',
            'address'          => 'required|string|max:500',
            'permanent_address' => 'required|string|max:500',
            'qualification'    => 'required|string',
            'work_experience'  => 'required|string',
            'note'             => 'nullable|string',
            'password'         => 'required|min:6',
            'status'           => 'required|in:0,1',
            'profile_pic'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $baseSlug = $request->slug
                ? Str::slug($request->slug)
                : Str::slug($request->name . ' ' . $request->last_name);

            $slug = $baseSlug;
            $count = 1;

            while (User::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }

            $user = new User();
            $user->name               = $request->name;
            $user->last_name          = $request->last_name;
            $user->slug               = $slug;
            $user->email              = $request->email;
            $user->phone              = $request->phone;
            $user->gender             = $request->gender;
            $user->marital_status     = $request->marital_status;
            $user->date_of_birth      = $request->date_of_birth;
            $user->date_of_joining    = $request->date_of_joining;
            $user->address            = $request->address;
            $user->permanent_address  = $request->permanent_address;
            $user->qualification      = $request->qualification;
            $user->work_experience    = $request->work_experience;
            $user->note               = $request->note;
            $user->password           = bcrypt($request->password);
            $user->status             = $request->status;
            $user->is_admin           = 5;

            if (Auth::user()->is_admin == 1 || Auth::user()->is_admin == 2) {
                $user->created_by_id = $request->school_id;
            } else {
                $user->created_by_id = Auth::id();
            }

            $user->save();

            // Upload ảnh
            if ($request->hasFile('profile_pic')) {

                $publicId = "{$user->slug}-{$user->id}";

                $upload = $this->cloudinary->uploadImage(
                    $request->file('profile_pic'),
                    'Teacher/List',
                    $publicId
                );

                $user->profile_pic = $upload['url'];
                $user->save();
            }

            return redirect()
                ->route('cpanel.teacher')
                ->with('success', 'Teacher đã được tạo thành công!');
        } catch (\Exception $e) {

            Log::error('Error creating teacher', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo Teacher.');
        }
    }

    public function edit($slug)
    {
        $teacherlist = User::where('slug', $slug)->firstOrFail();

        $schools = User::where('is_admin', 3)
            ->where('status', 1)
            ->where('trang_thai', 1)
            ->get();

        $meta_title = "Teacher Edit";

        return view('backend.teacher.edit', compact('teacherlist', 'meta_title', 'schools'));
    }

    public function update(Request $request, User $teacher)
    {
        if (Auth::user()->is_admin == 1 || Auth::user()->is_admin == 2) {
            $request->validate([
                'school_id' => 'required|exists:users,id',
            ]);
        }

        $request->validate([
            'name'               => 'required|string|max:255',
            'last_name'          => 'required|string|max:255',
            'slug'               => 'nullable|string|max:255|unique:users,slug,' . $teacher->id,
            'email'              => 'sometimes|nullable|email|unique:users,email,' . $teacher->id,
            'phone'              => 'required|string|max:20',
            'gender'             => 'required|in:male,female',
            'marital_status'     => 'required|string|max:100',
            'date_of_birth'     => 'required|date',
            'date_of_joining'   => 'required|date',
            'address'           => 'required|string|max:500',
            'permanent_address' => 'required|string|max:500',
            'qualification'     => 'required|string',
            'work_experience'   => 'required|string',
            'note'              => 'nullable|string',
            'password'          => 'nullable|min:6',
            'status'            => 'required|in:0,1',
            'profile_pic'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {

            // 1. Tạo slug mới (nếu đổi tên / slug)
            $baseSlug = $request->filled('slug')
                ? Str::slug($request->slug)
                : Str::slug($request->name . ' ' . $request->last_name);

            $slug  = $baseSlug;
            $count = 1;

            while (
                User::where('slug', $slug)
                ->where('id', '!=', $teacher->id)
                ->exists()
            ) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }

            // 2. Update toàn bộ field giống store
            $teacher->name               = $request->name;
            $teacher->last_name          = $request->last_name;
            $teacher->slug               = $slug;
            $teacher->email              = $request->email;
            $teacher->phone              = $request->phone;
            $teacher->gender             = $request->gender;
            $teacher->marital_status     = $request->marital_status;
            $teacher->date_of_birth      = $request->date_of_birth;
            $teacher->date_of_joining    = $request->date_of_joining;
            $teacher->address            = $request->address;
            $teacher->permanent_address  = $request->permanent_address;
            $teacher->qualification      = $request->qualification;
            $teacher->work_experience    = $request->work_experience;
            $teacher->note               = $request->note;
            $teacher->status             = $request->status;

            if (Auth::user()->is_admin == 1 || Auth::user()->is_admin == 2) {
                $teacher->created_by_id = $request->school_id;
            }

            if ($request->filled('password')) {
                $teacher->password = bcrypt($request->password);
            }

            $teacher->save();

            // 3. Upload ảnh giống store
            if ($request->hasFile('profile_pic')) {

                $publicId = "{$teacher->slug}-{$teacher->id}";

                $upload = $this->cloudinary->uploadImage(
                    $request->file('profile_pic'),
                    'Teacher/List',
                    $publicId
                );

                $teacher->profile_pic = $upload['url'];
                $teacher->save();
            }

            return redirect()
                ->route('cpanel.teacher')
                ->with('success', 'Cập nhật Teacher thành công!');
        } catch (\Exception $e) {

            Log::error('Error updating teacher', [
                'user_id' => $teacher->id,
                'message' => $e->getMessage(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật Teacher.');
        }
    }

    public function toggleStatus($id)
    {
        $teacher = User::findOrFail($id);

        $teacher->status = $teacher->status == 1 ? 0 : 1;
        $teacher->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công');
    }

    public function destroy(User $teacher)
    {
        if ($teacher->profile_pic && Storage::exists($teacher->profile_pic)) {
            Storage::delete($teacher->profile_pic);
        }

        $teacher->delete();

        return redirect()
            ->route('cpanel.teacher')
            ->with('success', 'Giáo viên đã được xóa thành công.');
    }

    // Phân công môn cho giáo viên
    public function assignSubjectForm(User $teacher)
    {
        abort_if($teacher->is_admin != 5, 404);

        $classes  = SchoolClass::where('status', 1)->get();
        $subjects = SubjectModel::where('status', 1)->get();

        $assigned = TeacherSubject::where('teacher_id', $teacher->id)->get();

        $meta_title = "Teacher Assignments";

        return view(
            'backend.teacher.assign-subject',
            compact('teacher', 'classes', 'subjects', 'assigned' , 'meta_title')
        );
    }

    // Lưu phân công giảng dạy
    public function assignSubjectStore(Request $request, User $teacher)
    {
        abort_if($teacher->is_admin != 5, 404);

        $request->validate([
            'class_id'   => 'required|exists:class,id',
            'subject_id' => 'required|exists:subjects,id',
        ]);

        TeacherSubject::firstOrCreate([
            'teacher_id' => $teacher->id,
            'class_id'   => $request->class_id,
            'subject_id' => $request->subject_id,
        ]);

        return back()->with('success', 'Đã phân công môn cho giáo viên');
    }

    public function assignSubjectDelete(TeacherSubject $assign)
    {
        $assign->delete();

        return back()->with('success', 'Đã xóa phân công giảng dạy');
    }
}
