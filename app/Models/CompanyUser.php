<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\ValidationException;
use App\Traits\ValidatesCompanySubscription;

class CompanyUser extends Authenticatable
{
    use Notifiable, ValidatesCompanySubscription;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

protected static function booted()
    {
        static::creating(function ($companyUser) {
            if (!$companyUser->company_id) {
                throw ValidationException::withMessages([
                    'company_id' => 'Please select a company.',
                ]);
            }

            $companyUser->validateCompanySubscription($companyUser->company_id);
        });
    }
}
