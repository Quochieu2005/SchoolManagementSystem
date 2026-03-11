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
use Illuminate\Support\Facades\Log;

class ParentController extends Controller
{
    protected CloudinaryService $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    public function add_student($parent_id, User $user)
    {
        // tránh add trùng
        if ($user->parent_id == $parent_id) {
            return redirect()->back()->with('error', 'Student already added');
        }

        $user->parent_id = $parent_id;
        $user->save();

        return redirect()->back()->with('success', 'Student successfully assigned');
    }

    public function my_student_delete(User $user)
    {
        $user->parent_id = null;
        $user->save();

        return redirect()->back()->with('success', 'Student successfully unassigned');
    }

    public function my_student(Request $request, $parent_slug)
    {
        // tìm parent
        $parent = User::where('slug', $parent_slug)
            ->where('is_admin', 7)
            ->firstOrFail();

        $parent_id = $parent->id;

        /*
    |--------------------------------------------------------------------------
    | MY STUDENT LIST (chỉ student đã add)
    |--------------------------------------------------------------------------
    */
        $myStudentsQuery = User::query()
            ->where('is_admin', 6)
            ->where('trang_thai', 1)
            ->where('parent_id', $parent_id);

        if (in_array(Auth::user()->is_admin, [3, 4, 5])) {
            $myStudentsQuery->where('created_by_id', Auth::id());
        }

        $myStudents = $myStudentsQuery
            ->orderBy('id', 'desc')
            ->get();


        /*
    |--------------------------------------------------------------------------
    | SEARCH STUDENT
    |--------------------------------------------------------------------------
    */
        $searchStudents = null;

        if ($request->filled('name') || $request->filled('email')) {

            $searchQuery = User::query()
                ->where('is_admin', 6)
                ->where('trang_thai', 1);

            if (in_array(Auth::user()->is_admin, [3, 4, 5])) {
                $searchQuery->where('created_by_id', Auth::id());
            }

            // search name
            if ($request->filled('name')) {
                $searchQuery->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->name . '%')
                        ->orWhere('last_name', 'like', '%' . $request->name . '%');
                });
            }

            // search email
            if ($request->filled('email')) {
                $searchQuery->where('email', 'like', '%' . $request->email . '%');
            }

            $searchStudents = $searchQuery
                ->orderBy('id', 'desc')
                ->get();
        }


        $meta_title = "Parent My Student";

        // dd([
        //     'sql'      => $searchQuery->toSql(),
        //     'bindings' => $searchQuery->getBindings(),
        //     'total'    => $searchQuery->count(),  // số record thực tế trước paginate
        //     'request'  => $request->all(),
        // ]);

        return view('backend.parent.my_student', compact(
            'meta_title',
            'myStudents',
            'searchStudents',
            'parent_id',
            'parent',
            'request'
        ));
    }

    public function parent_list(Request $request)
    {
        $query = User::where('is_admin', 7);

        if (in_array(Auth::user()->is_admin, [3, 4])) {
            $query->where('created_by_id', Auth::id());
        }

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
            $query->where('gender', $request->gender);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $parents = $query
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends($request->query());

        $meta_title = "Parent List";
        return view('backend.parent.index', compact('meta_title', 'parents'));
    }

    public function create()
    {
        $schools = User::where('is_admin', 3)
            ->where('status', 1)
            ->where('trang_thai', 1)
            ->get();

        $classes = SchoolClass::where('status', 1)
            ->where('is_delete', 0)
            ->get();

        $meta_title = "Create Student";

        return view('backend.student.create', compact(
            'meta_title',
            'schools',
            'classes'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_id'         => 'required|exists:users,id',
            'name'              => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255',
            'email'             => 'required|email|max:255|unique:users,email',
            'occupation'        => 'required|string|max:255',
            'gender'            => 'required|in:male,female',
            'phone'             => 'required|string|max:20',
            'permanent_address' => 'required|string|max:500',
            'password'          => 'required|string|min:6',
            'status'            => 'required|in:0,1',
            'profile_pic'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            $baseSlug = $request->slug
                ? Str::slug($request->slug)
                : Str::slug($request->name . ' ' . $request->last_name);

            $slug = $baseSlug;
            $i = 1;
            while (User::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$i}";
                $i++;
            }

            $user = User::create([
                'name'              => $request->name,
                'last_name'         => $request->last_name,
                'slug'              => $slug,
                'email'             => $request->email,
                'gender'            => $request->gender,
                'phone'             => $request->phone,
                'occupation'        => $request->occupation,
                'permanent_address' => $request->permanent_address,
                'password'          => bcrypt($request->password),
                'status'            => $request->status,
                'is_admin'          => 7,
                'created_by_id'     => $request->school_id,
            ]);

            if ($request->hasFile('profile_pic')) {
                $upload = $this->cloudinary->uploadImage(
                    $request->file('profile_pic'),
                    'Parent/List',
                    "{$user->slug}-{$user->id}"
                );

                $user->update(['profile_pic' => $upload['url']]);
            }

            return redirect()->route('cpanel.parent')
                ->with('success', 'Parent đã được tạo thành công!');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
    public function parent_edit($slug)
    {
        $parents = User::where('slug', $slug)->firstOrFail();

        $schools = User::where('is_admin', 3)
            ->where('status', 1)
            ->where('trang_thai', 1)
            ->get();

        $meta_title = "Edit Parent";
        return view('backend.parent.edit', compact('meta_title', 'parents', 'schools'));
    }

    public function update(Request $request, $slug)
    {
        $parent = User::where('slug', $slug)->firstOrFail();

        $request->validate([
            'name'              => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:users,slug,' . $parent->id,
            'email'             => 'nullable|email|unique:users,email,' . $parent->id,
            'gender'            => 'required|in:male,female',
            'occupation'        => 'required|string|max:255',
            'phone'             => 'required|string|max:20',
            'permanent_address' => 'required|string|max:500',
            'password'          => 'nullable|string|min:6',
            'status'            => 'required|in:0,1',
            'profile_pic'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            // ===== Slug không trùng =====
            $baseSlug = $request->slug
                ? Str::slug($request->slug)
                : Str::slug($request->name . ' ' . $request->last_name);

            $slugNew = $baseSlug;
            $count = 1;

            while (
                User::where('slug', $slugNew)
                ->where('id', '!=', $parent->id)
                ->exists()
            ) {
                $slugNew = "{$baseSlug}-{$count}";
                $count++;
            }

            // ===== Update thông tin =====
            $parent->update([
                'name'              => $request->name,
                'last_name'         => $request->last_name,
                'slug'              => $slugNew,
                'email'             => $request->email,
                'gender'            => $request->gender,
                'phone'             => $request->phone,
                'occupation'        => $request->occupation,
                'permanent_address' => $request->permanent_address,
                'password'          => $request->filled('password')
                    ? bcrypt($request->password)
                    : $parent->password,
                'status'            => $request->status,
                'created_by_id'     => in_array(Auth::user()->is_admin, [1, 2])
                    ? $request->school_id
                    : Auth::id(),
            ]);

            // ===== Upload ảnh mới (GIỐNG STORE) =====
            if ($request->hasFile('profile_pic')) {

                $upload = $this->cloudinary->uploadImage(
                    $request->file('profile_pic'),
                    'Parent/List',
                    "{$parent->slug}-{$parent->id}"
                );

                $parent->update([
                    'profile_pic' => $upload['url']
                ]);
            }

            return redirect()
                ->route('cpanel.parent')
                ->with('success', 'Parent đã được cập nhật thành công!');
        } catch (\Throwable $e) {
            Log::error($e);
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy($slug)
    {
        $parents = User::where('slug', $slug)->firstOrFail();

        if ($parents->profile_pic && Storage::exists($parents->profile_pic)) {
            Storage::delete($parents->profile_pic);
        }

        $parents->delete();

        return redirect()
            ->route('cpanel.parent')
            ->with('success', 'Parents đã được xóa thành công.');
    }
}
