<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectModel extends Model
{
    protected $table = 'subjects';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'code',
        'description',
        'credit',
        'status',
        'created_by_id',
        'is_delete',
    ];

    public function classes()
    {
        return $this->belongsToMany(
            SchoolClass::class,
            'class_subjects',
            'subject_id',
            'class_id'
        )->withPivot('grade')->withTimestamps();
    }

    public function teachers()
    {
        return $this->belongsToMany(
            User::class,
            'teacher_subjects',
            'subject_id',
            'teacher_id'
        )->withPivot('class_id')->withTimestamps();
    }
}
