<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class Student extends Authenticatable
{
    use HasFactory;

    // Define the table name (optional if it follows Laravel's naming convention)
    protected $table = 'students';

    // Define the primary key
    protected $primaryKey = 'student_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // Allow mass assignment for these fields
    protected $fillable = [
        'student_id', 'last_name', 'first_name', 'middle_name', 'age', 'sex', 'bdate', 'bplace',
        'civil_status',  'father_last_name', 'father_first_name', 'father_middle_name',
        'mother_last_name', 'mother_first_name', 'mother_middle_name', 'cell_no', 'email',
        'elem_school_name', 'hs_school_name', 'tertiary_school_name', 'elem_grad_year',
        'hs_grad_year', 'tertiary_grad_year',
        'address',
        'department_id', 'year_level', 'semester',
        'password', 'otp', // Added for OTP authentication
        'otp_expires_at',
        'profile_picture',
        'regular', // Added for regular student
        'enrolled', // Added for enrolled student
        'suffix',
        'section',
        'is_enrolled', //
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'otp_expires_at' => 'datetime',
    ];

    // Format bdate to MM-DD-YYYY when retrieving and store as YYYY-MM-DD
    protected function bdate(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('m-d-Y'),
            set: fn ($value) => Carbon::parse($value)
        );
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id', 'student_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'grades', 'student_id', 'subject_id')
                    ->withPivot('grade', 'year_level', 'semester')
                    ->withTimestamps();
    }

}
