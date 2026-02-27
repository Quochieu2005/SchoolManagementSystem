<?php

use App\Http\Controllers\Backend\SchoolAdminController;
use App\Http\Controllers\Backend\SubjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\SchoolController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\ClassController;
use App\Http\Controllers\Backend\StudentController;
use App\Http\Controllers\Backend\TeacherController;

// Login
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'post_login'])->name('post.login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// FORGOT PASSWORD
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])
    ->name('password.request');

Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->name('password.email');

Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
    ->name('password.update');

    // BACKEND (CPANEL)
Route::middleware(['auth', 'common'])->group(function () {

    // Dashboard
    Route::get('/cpanel/dashboard', [DashboardController::class, 'dashboard'])->name('cpanel.dashboard');

    Route::fallback(function () {
        return response()->view('errors.404', [], 404);
    });
});

Route::group(['middleware' => 'admin'], function(){

    // ADMIN
    Route::get('/cpanel/admin', [AdminController::class, 'admin_list'])->name('cpanel.admin');
    Route::get('/cpanel/admin/add', [AdminController::class, 'create_admin'])->name('cpanel.admin.add');
    Route::post('/cpanel/admin/store', [AdminController::class, 'store'])->name('cpanel.admin.store');
    Route::get('/cpanel/admin/{user:slug}/edit', [AdminController::class, 'edit_admin'])->name('cpanel.admin.edit');
    Route::put('/cpanel/admin/{user:slug}', [AdminController::class, 'update'])->name('cpanel.admin.update');
    Route::delete('/cpanel/admin/{user:slug}', [AdminController::class, 'destroy'])->name('cpanel.admin.delete');

    // SCHOOL
    Route::get('/cpanel/school', [SchoolController::class, 'school_list'])->name('cpanel.school');
    Route::get('/cpanel/school/add', [SchoolController::class, 'create_school'])->name('cpanel.school.add');
    Route::post('/cpanel/school/store', [SchoolController::class, 'store'])->name('cpanel.school.store');
    Route::get('/cpanel/school/{school:slug}/edit', [SchoolController::class, 'edit'])->name('cpanel.school.edit');
    Route::put('/cpanel/school/{school:slug}', [SchoolController::class, 'update'])->name('cpanel.school.update');
    Route::get('/cpanel/school/{id}/toggle-status', [SchoolController::class, 'toggleStatus'])->name('cpanel.school.toggleStatus');
    Route::delete('/cpanel/school/{school:slug}', [SchoolController::class, 'destroy'])->name('cpanel.school.delete');

});

Route::group(['middleware' => 'school'], function(){

    // School Admin
    Route::get('/cpanel/school_admin' , [SchoolAdminController::class , 'admin_school_list'])->name('cpanel.school.admin');
    Route::get('/cpanel/school_admin/add' , [SchoolAdminController::class , 'school_admin_create'])->name('cpanel.school.admin.add');
    Route::post('/cpanel/school_admin/store' , [SchoolAdminController::class , 'school_admin_store'])->name('cpanel.school.admin.store');
    Route::get('/cpanel/school_admin/{user:slug}/edit' , [SchoolAdminController::class , 'school_admin_edit'])->name('cpanel.school.admin.edit');
    Route::put('/cpanel/school_admin/{user:slug}' , [SchoolAdminController::class , 'school_admin_update'])->name('cpanel.school.admin.update');
    Route::get('/cpanel/school_admin/{id}/toggle-status', [SchoolAdminController::class, 'toggleStatus'])->name('cpanel.school.admin.toggleStatus');
    Route::delete('cpanel/school_admin/{user:slug}' , [SchoolAdminController::class , 'school_admin_delete'])->name('cpanel.school.admin.delete');

    // Teacher
    Route::get('/cpanel/teacher', [TeacherController::class , 'teacher_list'])->name('cpanel.teacher');
    Route::get('/cpanel/teacher/add' , [TeacherController::class , 'create_teacher'])->name('cpanel.teacher.add');
    Route::post('/cpanel/teacher/store' , [TeacherController::class , 'store'])->name('cpanel.teacher.store');
    Route::get('cpanel/teacher/{teacher:slug}/edit' , [TeacherController::class , 'edit'])->name('cpanel.teacher.edit');
    Route::put('/cpanel/teacher/{teacher:slug}' , [TeacherController::class , 'update'])->name('cpanel.teacher.update');
    Route::get('/cpanel/teacher/{id}/toggle-status', [TeacherController::class, 'toggleStatus'])->name('cpanel.teacher.toggleStatus');
    Route::delete('/cpanel/teacher/{teacher:slug}' , [TeacherController::class , 'destroy'])->name('cpanel.teacher.delete');
    Route::get('/cpanel/teacher/{teacher:slug}/assign-subject', [TeacherController::class, 'assignSubjectForm'])->name('teacher.assign.subject');
    Route::post('/cpanel/teacher/{teacher:slug}/assign-subject', [TeacherController::class, 'assignSubjectStore'])->name('teacher.assign.subject.store');
    Route::delete('/cpanel/teacher/assign-subject/{assign}',[TeacherController::class, 'assignSubjectDelete'])->name('cpanel.teacher.assign.delete');

    // Student
    Route::get('/cpanel/student', [StudentController::class , 'student_list'])->name('cpanel.student');
    Route::get('/cpanel/student/add' , [StudentController::class , 'create_student'])->name('cpanel.student.add');
    Route::post('/cpanel/student/store' , [StudentController::class , 'store'])->name('cpanel.student.store');
    Route::get('cpanel/student/{student:slug}/edit' , [StudentController::class , 'edit_student'])->name('cpanel.student.edit');
    Route::put('/cpanel/student/{student:slug}' , [StudentController::class , 'update'])->name('cpanel.student.update');
    Route::delete('/cpanel/student/{student:slug}' , [StudentController::class , 'destroy'])->name('cpanel.student.delete');
    Route::get('cpanel/ajax/classes-by-school/{school}',[StudentController::class, 'getClassesBySchool'])->name('ajax.classes.by.school');
    // Class
    Route::get('/cpanel/class' , [ClassController::class , 'class_list'])->name('cpanel.class');
    Route::get('/cpanel/class/add' , [ClassController::class , 'class_create'])->name('cpanel.class.add');
    Route::post('/cpanel/class/store' , [ClassController::class , 'store'])->name('cpanel.class.store');
    Route::get('/cpanel/class/{class:slgu}/edit' , [ClassController::class , 'edit_class'])->name('cpanel.class.edit');
    Route::put('/cpanel/class/{class:slug}' , [ClassController::class , 'update'])->name('cpanel.class.update');
    Route::get('/cpanel/class/{id}/toggle-status', [ClassController::class, 'toggleStatus'])->name('cpanel.class.toggleStatus');
    Route::delete('/cpanel/class/{class:slug}' , [ClassController::class , 'destroy'])->name('cpanel.class.delete');

    // Subject
    Route::get('/cpanel/subject' , [SubjectController::class , 'subject_list'])->name('cpanel.subject');
    Route::get('/cpanel/subject/add' , [SubjectController::class , 'subject_create'])->name('cpanel.subject.add');
    Route::post('/cpanel/subject/store' , [SubjectController::class , 'store'])->name('cpanel.subject.store');
    Route::get('/cpanel/subject/{subject:slgu}/edit' , [SubjectController::class , 'edit_subject'])->name('cpanel.subject.edit');
    Route::put('/cpanel/subject/{subject:slug}' , [SubjectController::class , 'update'])->name('cpanel.subject.update');
    Route::get('/cpanel/subject/{id}/toggle-status', [SubjectController::class, 'toggleStatus'])->name('cpanel.subject.toggleStatus');
    Route::delete('/cpanel/subject/{subject:slug}' , [SubjectController::class , 'destroy'])->name('cpanel.subject.delete');
});
