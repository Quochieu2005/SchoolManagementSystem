<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $casts = [
        'date_of_birth'    => 'date',
        'date_of_joining' => 'date',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getCreatedBy()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(
            SubjectModel::class,
            'teacher_subjects',
            'teacher_id',
            'subject_id'
        )->withPivot('class_id')->withTimestamps();
    }

    public function teachingAssignments()
    {
        return $this->hasMany(TeacherSubject::class, 'teacher_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
