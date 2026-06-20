<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * status flow: pending -> in_progress -> completed.
     * requested_at is stamped when the doctor creates the test;
     * completed_at is stamped when the lab_technician saves a result.
     */
    public function up(): void
    {
        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('lab_technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('test_name');
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->text('result')->nullable();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'requested_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lab_tests');
    }
};
