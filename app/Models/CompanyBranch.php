<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
         'name_en', 
         'name_ar'
        ];

        public function company()
        {
            return $this->belongsTo(Company::class);
        }

        public function holidays()
        {
            return $this->hasMany(CompanyHoliday::class, 'branch_id');
        }

        public static function getOptionsByCompanyId(?int $companyId)
        {
        if (!$companyId) {
            return collect(); 
        }

        return self::where('company_id', $companyId)->pluck('name_en', 'id');
        }
}
