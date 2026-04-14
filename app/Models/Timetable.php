<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    protected $table = 'timetable';

    protected $fillable = [
        'id',
        'class_id',
        'subject_id',
        'week_id',
        'start_time',
        'end_time',
        'room_number',
        'created_at',
        'updated_at',
    ];
}
