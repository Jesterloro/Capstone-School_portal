<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'department_id'];

    protected $table = 'classes';

    // Relationship: A class belongs to one department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
