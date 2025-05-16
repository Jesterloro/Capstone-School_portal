<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcements'; // Specify the table name

    protected $fillable = [
        'title',
        'description',
        'image', // Allow mass assignment for image path
    ];
}
