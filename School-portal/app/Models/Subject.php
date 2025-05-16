<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'description', 'units', 'day', 'time', 'room', 'teacher_id','department_id','semester', 'year','prerequisite_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'grades', 'subject_id', 'student_id')
                    ->withPivot('grade', 'year_level', 'semester')
                    ->withTimestamps();
    }

        public function prerequisite()
    {
        return $this->belongsTo(Subject::class, 'prerequisite_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'subject_id');
    }


}
