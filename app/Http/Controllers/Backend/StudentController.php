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
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    protected CloudinaryService $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }
    public function student_list(Request $request)
    {
        $query = User::where('is_admin', 6);

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
        $studentList = $query
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends($request->query());

        $meta_title = "Học sinh List";
        return view('backend.student.index', compact('meta_title', 'studentList'));
    }

    public function create_student()
    {
        $teachers = User::where('is_admin', 3)
            ->where('status', 1)
            ->where('trang_thai', 1)
            ->get();

        $meta_title = "Create Student";

        return view(
            'backend.student.create',
            compact('meta_title', 'teachers')
        );
    }

    public function getClassesBySchool($schoolId)
    {
        $classes = SchoolClass::where('status', 1)
            ->where('is_delete', 1)
            ->where('created_by_id', $schoolId) // 🔥 QUAN TRỌNG
            ->select('id', 'name')
            ->get();

        return response()->json($classes);
    }

    public function store(Request $request)
    {
        // ===== Validate =====
        // dd($request->all());
        $request->validate([
            'school_id'         => 'required|exists:users,id',
            'name'              => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255',
            'email'             => 'required|email|max:255|unique:users,email',
            'admission_number'  => 'required|string|max:100|unique:users,admission_number',
            'roll_number'       => 'required|string',
            'class_id'          => 'required|integer|exists:class,id',
            'gender'            => 'required|in:male,female',
            'date_of_birth'     => 'required|date',
            'admission_date'    => 'required|date',
            'phone'             => 'required|string|max:20',
            'address'           => 'required|string|max:500',
            'permanent_address' => 'required|string|max:500',
            'password'          => 'required|string|min:6',
            'status'            => 'required|in:0,1',
        ]);

        // ===== CHECK CLASS THUỘC SCHOOL =====
        $classValid = SchoolClass::where('id', $request->class_id)
            ->where('created_by_id', $request->school_id)
            ->exists();

        if (! $classValid) {
            return back()
                ->withInput()
                ->withErrors(['class_id' => 'Class không thuộc trường đã chọn']);
        }

        try {
            // ===== Slug =====
            $baseSlug = $request->slug
                ? Str::slug($request->slug)
                : Str::slug($request->name . ' ' . $request->last_name);

            $slug = $baseSlug;
            $count = 1;
            while (User::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }

            // ===== Create student =====
            $user = new User();
            $user->name              = $request->name;
            $user->last_name         = $request->last_name;
            $user->slug              = $slug;
            $user->email             = $request->email;
            $user->admission_number  = $request->admission_number;
            $user->roll_number       = $request->roll_number;
            $user->class_id          = $request->class_id;
            $user->gender            = $request->gender;
            $user->date_of_birth     = $request->date_of_birth;
            $user->admission_date    = $request->admission_date;
            $user->phone             = $request->phone;
            $user->address           = $request->address;
            $user->permanent_address = $request->permanent_address;
            $user->password          = bcrypt($request->password);
            $user->status            = $request->status;
            $user->is_admin          = 6;
            $user->created_by_id     = $request->school_id;

            $user->save();

            // ===== Upload ảnh =====
            if ($request->hasFile('profile_pic')) {
                $upload = $this->cloudinary->uploadImage(
                    $request->file('profile_pic'),
                    'Student/List',
                    "{$user->slug}-{$user->id}"
                );

                $user->profile_pic = $upload['url'];
                $user->save();
            }

            return redirect()
                ->route('cpanel.student')
                ->with('success', 'Học sinh đã được tạo thành công!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo Student.');
        }
    }

    public function edit_student($slug)
    {
        $student = User::where('slug', $slug)->firstOrFail();

        $schools = User::where('is_admin', 3)
            ->where('status', 1)
            ->where('trang_thai', 1)
            ->get();

        $classes = SchoolClass::where('status', 1)
            ->where('is_delete', 1)
            ->where('created_by_id', $student->created_by_id)
            ->get();

        $meta_title = "Edit Student";

        return view('backend.student.edit', compact(
            'meta_title',
            'schools',
            'classes',
            'student'
        ));
    }

    public function update(Request $request, User $student)
    {
        // ===== Validate school =====
        if (in_array(Auth::user()->is_admin, [1, 2])) {
            $request->validate([
                'school_id' => 'required|exists:users,id',
            ]);
        }

        // ===== Validate Học sinh =====
        $request->validate([
            'name'      => 'required|string|max:255',
            'last_name' => 'required|string|max:255',

            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('users', 'slug')->ignore($student->id),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($student->id),
            ],

            'admission_number' => [
                'required',
                'string',
                'max:100',
                Rule::unique('users', 'admission_number')->ignore($student->id),
            ],

            'roll_number'       => 'required|string',
            'class_id'          => 'required|integer|exists:class,id',
            'gender'            => 'required|in:male,female',
            'date_of_birth'     => 'required|date',
            'admission_date'    => 'required|date',
            'caste'             => 'nullable|string|max:100',
            'religion'          => 'nullable|string|max:100',
            'phone'             => 'required|string|max:20',
            'blood_group'       => 'nullable|string|max:5',
            'height'            => 'nullable|string|max:20',
            'weight'            => 'nullable|string|max:20',
            'address'           => 'required|string|max:500',
            'permanent_address' => 'required|string|max:500',

            // UPDATE → không bắt buộc password
            'password' => 'nullable|string|min:6',

            'status' => 'required|in:0,1',
        ]);

        $classValid = SchoolClass::where('id', $request->class_id)
            ->where('created_by_id', $request->school_id)
            ->exists();

        if (! $classValid) {
            return back()
                ->withInput()
                ->withErrors(['class_id' => 'Class không thuộc School đã chọn']);
        }

        try {
            // ===== 1. Slug (FIX ignore chính nó) =====
            $baseSlug = $request->slug
                ? Str::slug($request->slug)
                : Str::slug($request->name . ' ' . $request->last_name);

            $slug = $baseSlug;
            $count = 1;

            while (
                User::where('slug', $slug)
                ->where('id', '!=', $student->id)
                ->exists()
            ) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }

            // ===== 2. UPDATE Học sinh (KHÔNG new User) =====
            $user = $student;

            $user->name              = $request->name;
            $user->last_name         = $request->last_name;
            $user->slug              = $slug;
            $user->email             = $request->email;
            $user->admission_number  = $request->admission_number;
            $user->roll_number       = $request->roll_number;
            $user->class_id          = $request->class_id;
            $user->gender            = $request->gender;
            $user->date_of_birth     = $request->date_of_birth;
            $user->admission_date    = $request->admission_date;
            $user->caste             = $request->caste;
            $user->religion          = $request->religion;
            $user->phone             = $request->phone;
            $user->blood_group       = $request->blood_group;
            $user->height            = $request->height;
            $user->weight            = $request->weight;
            $user->address           = $request->address;
            $user->permanent_address = $request->permanent_address;
            $user->status            = $request->status;
            $user->is_admin          = 6;

            $user->created_by_id =
                in_array(Auth::user()->is_admin, [1, 2])
                ? $request->school_id
                : Auth::id();

            // ===== Password chỉ update khi nhập =====
            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }

            $user->save();

            // ===== Upload ảnh =====
            if ($request->hasFile('profile_pic')) {
                $publicId = "{$user->slug}-{$user->id}";

                $upload = $this->cloudinary->uploadImage(
                    $request->file('profile_pic'),
                    'Student/List',
                    $publicId
                );

                $user->profile_pic = $upload['url'];
                $user->save();
            }

            return redirect()
                ->route('cpanel.student')
                ->with('success', 'Học sinh đã được cập nhật thành công!');
        } catch (\Exception $e) {
            Log::error('Error updating student', [
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật Student.');
        }
    }

    public function destroy(User $student)
    {
        if ($student->profile_pic && Storage::exists($student->profile_pic)) {
            Storage::delete($student->profile_pic);
        }

        $student->delete();

        return redirect()
            ->route('cpanel.student')
            ->with('success', 'Học sinh đã được xóa thành công.');
    }
}
