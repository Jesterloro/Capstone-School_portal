<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Teacher extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'department',
        'phoneNumber',
        'department_id', // FK from Dept table
        'otp', // Added for OTP authentication
        'otp_expires_at',
        'login_attempts',
        'cooldown_until',
        'last_failed_login',
        'penalty_time',
        ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'otp_expires_at' => 'datetime',
    ];
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'teacher_id', 'id');
    }
}
