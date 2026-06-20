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
     * The guard name Spatie permission should use for this model.
     * Must match config/auth.php's default guard ("web").
     */
    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Appointments where this user is the attending doctor.
     */
    public function appointmentsAsDoctor()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /**
     * Lab tests this user (doctor) requested.
     */
    public function labTestsRequested()
    {
        return $this->hasMany(LabTest::class, 'doctor_id');
    }

    /**
     * Lab tests this user (lab_technician) is/was responsible for.
     */
    public function labTestsHandled()
    {
        return $this->hasMany(LabTest::class, 'lab_technician_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Role helpers
    |--------------------------------------------------------------------------
    | Thin convenience wrappers around the `role` enum column. These are
    | intentionally simple (not Spatie hasRole() calls) so Blade views and
    | controllers can do cheap, readable checks like $user->isDoctor().
    | Spatie's hasRole()/can() are still used for middleware + policies.
    */

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isDoctor(): bool
    {
        return $this->role === 'doctor';
    }

    public function isLabTechnician(): bool
    {
        return $this->role === 'lab_technician';
    }

    public function isReceptionist(): bool
    {
        return $this->role === 'receptionist';
    }

    /**
     * Doctors and super_admins both get full clinical access (per the
     * "Doctors Module: Role = admin/super_admin. Full CRUD on
     * everything" requirement).
     */
    public function hasFullClinicalAccess(): bool
    {
        return $this->isDoctor() || $this->isSuperAdmin();
    }
}
