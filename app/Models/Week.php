<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    protected $table = 'weeks';

    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at',
    ];
}
