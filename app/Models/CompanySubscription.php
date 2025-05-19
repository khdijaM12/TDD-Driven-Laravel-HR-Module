<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CompanySubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'subscribe_start', 
        'subscribe_end', 
        'number_employees'
        ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

   protected static function booted()
{
    static::saving(function ($subscription) {
        Validator::make($subscription->toArray(), [
            'subscribe_start' => 'required|date',
            'subscribe_end' => 'required|date|after:subscribe_start',
        ])->validate();
    });
}

}
