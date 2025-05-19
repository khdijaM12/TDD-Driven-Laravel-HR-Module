<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyJob extends Model
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
}
