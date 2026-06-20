<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    /** @use HasFactory<\Database\Factories\AppointmentFactory> */
    use HasFactory;

    /**
     * queue_number is computed by AppointmentObserver, never set
     * directly by a controller/form.
     */
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'status',
        'appointment_date',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Status helpers — encode the waiting -> called -> done flow
    |--------------------------------------------------------------------------
    */

    public function isWaiting(): bool
    {
        return $this->status === 'waiting';
    }

    public function isCalled(): bool
    {
        return $this->status === 'called';
    }

    public function isDone(): bool
    {
        return $this->status === 'done';
    }

    /**
     * The only status this appointment is allowed to move to next,
     * or null if it's already at the end of the flow. Used to keep
     * the "advance queue" button safe against skipping states.
     */
    public function nextStatus(): ?string
    {
        return match ($this->status) {
            'waiting' => 'called',
            'called' => 'done',
            default => null,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeForDoctorToday($query, int $doctorId)
    {
        return $query->where('doctor_id', $doctorId)
            ->whereDate('appointment_date', now()->toDateString());
    }

    public function scopeNotDone($query)
    {
        return $query->where('status', '!=', 'done');
    }
}
