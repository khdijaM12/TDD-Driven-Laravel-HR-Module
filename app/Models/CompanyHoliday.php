<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;
use App\Models\Company;
use App\Models\CompanyHoliday;
use App\Models\CompanyBranch;

class CompanyHoliday extends Model
{
    use HasFactory;
    protected $fillable = [
       'company_id', 
       'occasion', 
       'date_from', 
       'date_to', 
       'branch_id'
        ];

    protected static function booted()
    {
        static::saving(function ($holiday) {
            Validator::make($holiday->attributesToArray(), [
                'date_from' => 'required|date',
                'date_to' => 'required|date|after_or_equal:date_from',
            ])->validate();
        });
    }



    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function branch()
    {
        return $this->belongsTo(CompanyBranch::class, 'branch_id');
    }
}
