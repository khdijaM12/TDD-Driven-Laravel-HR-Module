<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class CompanySchedule extends Model
{
    use HasFactory;

    protected $fillable = [
       'company_id',
        'slug', 
        'weekend_days',
        'check_in_time',
        'check_out_time'
        ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    protected static function booted()
    {
        static::saving(function ($schedule) {
            $rules = [
                'slug' => 'required|unique:company_schedules,slug,' . $schedule->id,
            ];

            $validator = Validator::make($schedule->attributesToArray(), $rules);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        });
    }
}
