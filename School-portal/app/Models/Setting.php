<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['current_semester', 'current_school_year', 'start_date', 'end_date'];

    public static function getSemester()
    {
        return self::first()->current_semester ?? 1;
    }
    public static function getSchoolYear()
    {
        return self::first()->current_school_year ?? date('Y');
    }

    public static function incrementSchoolYear($startDate = null, $endDate = null)
    {
        $setting = self::first();

        if ($setting) {
            // Increment the school year
            $setting->current_school_year += 1;

            // Save the start date if provided
            if ($startDate) {
                $setting->start_date = $startDate;  // Save the start date to the database
            }
            if ($endDate) {
                $setting->end_date = $endDate;  // Save the end date to the database
            }
            $setting->save();
        } else {
            self::create([
                'current_semester' => 1,
                'current_school_year' => date('Y') + 1,
                'start_date' => $startDate,  // Set the start date during creation if provided
                'end_date' => $endDate,      // Set the end date during creation if provided
            ]);
        }
    }



}

