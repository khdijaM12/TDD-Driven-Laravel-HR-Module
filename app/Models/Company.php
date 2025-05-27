<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'website',
        'status',
    ];

    protected $casts = [
    'name' => 'array',
    ];

        public function jobs()
    {
        return $this->hasMany(CompanyJob::class);
    }

    public function getNameEnAttribute()
    {
        return $this->name['en'] ?? '';
    }

    public function getNameArAttribute()
    {
        return $this->name['ar'] ?? '';
    }
}
