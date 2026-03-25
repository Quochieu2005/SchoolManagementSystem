<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSubject extends Model
{
    protected $table = 'subject_class';

    protected $fillable = [
        'created_by_id',
        'class_id',
        'subject_id',
        'status',
        'is_delete'
    ];

    public function subject()
    {
        return $this->belongsTo(SubjectModel::class, 'subject_id');
    }
    
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
