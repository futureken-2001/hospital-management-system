<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Polymorphic accessor to whichever model this log entry belongs
     * to (Patient, Appointment, LabTest, ...). Read-only convenience;
     * writes always go through auditable_type / auditable_id directly.
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Convenience helper used throughout the app (controllers,
     * observers) to write a single audit entry without repeating
     * boilerplate everywhere:
     *
     *   AuditLog::record('created', $patient);
     *   AuditLog::record('updated', $patient, $oldValues, $newValues);
     */
    public static function record(string $action, Model $model, ?array $old = null, ?array $new = null): self
    {
        return static::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'auditable_type' => $model::class,
            'auditable_id' => $model->getKey(),
            'old_values' => $old,
            'new_values' => $new,
            'created_at' => now(),
        ]);
    }
}
