<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/TeacherSubject.php
class TeacherSubject extends Model
{
    protected $table = 'teacher_subjects';

    protected $fillable = [
        'teacher_id',
        'class_id',
        'subject_id'
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(SubjectModel::class, 'subject_id');
    }
}
