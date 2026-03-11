<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\CloudinaryService;
use App\Models\User;
use Log;

class SchoolAdminController extends Controller
{
    protected CloudinaryService $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }
    public function admin_school_list(Request $request)
    {
        $query = User::where('is_admin', 4); // School Admin

        // 🔒 LỌC THEO TRƯỜNG
        if (Auth::user()->is_admin == 3) {
            $query->where('created_by_id', Auth::id());
        }

        // ===== SEARCH =====
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

        $schooladmin = $query
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends($request->query());

        $meta_title = "School Admin List";
        return view('backend.school_admin.index', compact('meta_title', 'schooladmin'));
    }

    public function school_admin_create()
    {
        $schooladmin = User::where('is_admin', 3)
            ->where('status', 1)
            ->where('trang_thai', 1)
            ->get();
        $meta_title = "Create School Admin";
        return view('backend.school_admin.create', compact('meta_title', 'schooladmin'));
    }

    public function school_admin_store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:users,slug',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:6',
            'address'     => 'required|string|max:500',
            'status'      => 'required|in:0,1',
            'profile_pic' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {

            // Nếu không nhập slug → tự tạo từ name
            $baseSlug = $request->slug
                ? Str::slug($request->slug)
                : Str::slug($request->name);

            $slug = $baseSlug;
            $count = 1;

            while (User::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }

            $user = new User();
            $user->name          = $request->name;
            $user->slug          = $slug;
            $user->email         = $request->email;
            $user->password      = bcrypt($request->password);
            $user->address       = $request->address;
            $user->status        = $request->status;
            $user->is_admin      = 4;
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
                    'School_admin/List',
                    $publicId
                );

                $user->profile_pic = $upload['url'];
                $user->save();
            }

            return redirect()
                ->route('cpanel.school.admin')
                ->with('success', 'School Admin đã được tạo thành công!');
        } catch (\Exception $e) {

            Log::error('Error creating School admin', [
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo school admin.');
        }
    }

    public function school_admin_edit($slug)
    {
        $schooladmin = User::where('slug', $slug)->firstOrFail();
        $schools = User::where('is_admin', 3)
            ->where('status', 1)
            ->where('trang_thai', 1)
            ->get();
        $meta_title = "School Admin Edit";
        return view('backend.school_admin.edit', compact('schooladmin', 'meta_title', 'schools'));
    }

    public function school_admin_update(Request $request, $slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();

        if (Auth::user()->is_admin == 1 || Auth::user()->is_admin == 2) {
            $request->validate([
                'school_id' => 'required|exists:users,id',
            ]);
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'slug'        => 'nullable|string|max:255|unique:users,slug,' . $user->id,
            'email'       => 'sometimes|nullable|email|unique:users,email,' . $user->id,
            'password'    => 'nullable|min:6',
            'address'     => 'required|string|max:500',
            'status'      => 'required|in:0,1',
            'profile_pic' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {

            $baseSlug = $request->slug
                ? Str::slug($request->slug)
                : Str::slug($request->name);

            $slug = $baseSlug;
            $count = 1;

            while (
                User::where('slug', $slug)
                ->where('id', '!=', $user->id)
                ->exists()
            ) {
                $slug = "{$baseSlug}-{$count}";
                $count++;
            }

            $user->name    = $request->name;
            $user->slug    = $slug;
            $user->email   = $request->email;
            $user->address = $request->address;
            $user->status  = $request->status;

            if (Auth::user()->is_admin == 1 || Auth::user()->is_admin == 2) {
                $user->created_by_id = $request->school_id;
            }

            if ($request->filled('password')) {
                $user->password = bcrypt($request->password);
            }

            $user->save();

            if ($request->hasFile('profile_pic')) {

                $publicId = "{$user->slug}-{$user->id}";

                $upload = $this->cloudinary->uploadImage(
                    $request->file('profile_pic'),
                    'School_admin/List',
                    $publicId
                );

                $user->profile_pic = $upload['url'];
                $user->save();
            }

            return redirect()
                ->route('cpanel.school.admin')
                ->with('success', 'Cập nhật school admin thành công!');
        } catch (\Exception $e) {

            Log::error('Error updating admin', [
                'message' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật school admin.');
        }
    }

    public function toggleStatus($id)
    {
        $teacher = User::findOrFail($id);

        $teacher->status = $teacher->status == 1 ? 0 : 1;
        $teacher->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công');
    }

    public function school_admin_delete(User $user)
    {
        if ($user->profile_pic && Storage::exists($user->profile_pic)) {
            Storage::delete($user->profile_pic);
        }

        $user->delete();

        return redirect()
            ->route('cpanel.school.admin')
            ->with('success', 'School Admin đã được xóa thành công.');
    }
}
