<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guard_name = 'web';
    
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'employee_code',
        'designation',
        'image',
        'gender',
        'birth_date',
        'phone',
        'alternate_phone',
        'emergency_contact',
        'address',
        'office_address',
        'division_id',
        'district_id',
        'upazila_id',
        'postal_code',
        'country',
        'nid_number',
        'passport_number',
        'joining_date',
        'salary',
        'status',
        'last_login_at',
        'last_login_ip',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function customer() {
        return $this->hasOne(Customer::class);
    }

    public function division()
    {
        return $this->belongsTo(Location::class, 'division_id');
    }

    public function district()
    {
        return $this->belongsTo(Location::class, 'district_id');
    }

    public function upazila()
    {
        return $this->belongsTo(Location::class, 'upazila_id');
    }
}
