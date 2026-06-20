<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * queue_number is scoped to (doctor_id, appointment_date) and is
     * computed in AppointmentObserver::creating() as
     * MAX(queue_number) + 1 for that doctor on that date, so it
     * naturally "resets" every new day per doctor without a cron job.
     *
     * status flow: waiting -> called -> done (see AppointmentController).
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('queue_number');
            $table->enum('status', ['waiting', 'called', 'done'])->default('waiting');
            $table->date('appointment_date');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // A doctor cannot have two appointments with the same queue
            // number on the same day.
            $table->unique(['doctor_id', 'appointment_date', 'queue_number'], 'doctor_date_queue_unique');
            $table->index(['doctor_id', 'appointment_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
