<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'logo',
        'website',
        'status',
    ];

        public function jobs()
    {
        return $this->hasMany(CompanyJob::class);
    }
}
