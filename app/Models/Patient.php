<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    /** @use HasFactory<\Database\Factories\PatientFactory> */
    use HasFactory;

    /**
     * patient_number is generated automatically by PatientObserver,
     * so it is intentionally left OUT of $fillable to prevent a
     * receptionist (or any form) from overriding it.
     */
    protected $fillable = [
        'name',
        'age',
        'residence',
        'phone',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'age' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function labTests(): HasMany
    {
        return $this->hasMany(LabTest::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * All audit_log entries recorded for this patient. Polymorphic-style
     * relation without needing Eloquent's morph helpers, since AuditLog
     * stores plain auditable_type / auditable_id columns.
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'auditable_id')
            ->where('auditable_type', static::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Query scopes — used by the patient search + filter feature
    |--------------------------------------------------------------------------
    */

    public function scopeSearch($query, ?string $term)
    {
        if (blank($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('patient_number', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%");
        });
    }

    public function scopeRegisteredOn($query, ?string $date)
    {
        if (blank($date)) {
            return $query;
        }

        return $query->whereDate('created_at', $date);
    }
}
