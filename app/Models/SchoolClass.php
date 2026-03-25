<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table = 'class';

    protected $fillable = [
        'name',
        'slug',
        'status',
        'grade',
        'created_by_id',
        'is_delete',
    ];

    public function subjects()
    {
        return $this->belongsToMany(
            SubjectModel::class,
            'class_subjects',
            'class_id',
            'subject_id'
        )->withPivot('grade')->withTimestamps();
    }

    public function teachers()
    {
        return $this->belongsToMany(
            User::class,
            'teacher_subjects',
            'class_id',
            'teacher_id'
        )->withPivot('subject_id')->withTimestamps();
    }
    
}
