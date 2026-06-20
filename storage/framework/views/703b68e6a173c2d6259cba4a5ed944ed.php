<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve(['title' => 'Patient Record'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h3 class="mb-0"><?php echo e($patient->name); ?></h3>
            <span class="text-muted"><?php echo e($patient->patient_number); ?></span>
        </div>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('patients.print-card', $patient)); ?>" target="_blank" class="btn btn-outline-secondary">🖨 Print Card</a>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $patient)): ?>
                <a href="<?php echo e(route('patients.edit', $patient)); ?>" class="btn btn-outline-primary">Edit</a>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Appointment::class)): ?>
                <a href="<?php echo e(route('appointments.create', ['patient_id' => $patient->id])); ?>" class="btn btn-primary">+ Add to Queue</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Patient Information</h5>
                    <dl class="row mb-0 small">
                        <dt class="col-5">Patient #</dt>
                        <dd class="col-7"><?php echo e($patient->patient_number); ?></dd>

                        <dt class="col-5">Age</dt>
                        <dd class="col-7"><?php echo e($patient->age); ?></dd>

                        <dt class="col-5">Residence</dt>
                        <dd class="col-7"><?php echo e($patient->residence); ?></dd>

                        <dt class="col-5">Phone</dt>
                        <dd class="col-7"><?php echo e($patient->phone); ?></dd>

                        <dt class="col-5">Registered</dt>
                        <dd class="col-7"><?php echo e($patient->created_at->format('d M Y, H:i')); ?></dd>

                        <?php if($patient->createdBy): ?>
                            <dt class="col-5">Registered by</dt>
                            <dd class="col-7"><?php echo e($patient->createdBy->name); ?></dd>
                        <?php endif; ?>
                    </dl>
                </div>
            </div>

            <?php if($patient->auditLogs->isNotEmpty()): ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Audit Trail</h5>
                        <ul class="list-unstyled small mb-0" style="max-height: 280px; overflow-y: auto;">
                            <?php $__currentLoopData = $patient->auditLogs->sortByDesc('created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="border-bottom py-2">
                                    <span class="badge bg-light text-dark text-capitalize"><?php echo e($log->action); ?></span>
                                    by <?php echo e($log->user->name ?? 'System'); ?>

                                    <div class="text-muted"><?php echo e($log->created_at->format('d M Y, H:i')); ?></div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Appointment History</h5>

                    <?php if($patient->appointments->isEmpty()): ?>
                        <p class="text-muted mb-0">No appointments recorded yet.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Doctor</th>
                                    <th>Queue #</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $patient->appointments->sortByDesc('appointment_date'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($appointment->appointment_date->format('d M Y')); ?></td>
                                        <td>Dr. <?php echo e($appointment->doctor->name); ?></td>
                                        <td><?php echo e($appointment->queue_number); ?></td>
                                        <td>
                                            <?php if($appointment->isWaiting()): ?>
                                                <span class="badge badge-status-waiting">Waiting</span>
                                            <?php elseif($appointment->isCalled()): ?>
                                                <span class="badge badge-status-called">Called</span>
                                            <?php else: ?>
                                                <span class="badge badge-status-done">Done</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="card-title mb-0">Lab Tests</h5>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\LabTest::class)): ?>
                            <a href="<?php echo e(route('lab-tests.create', ['patient_id' => $patient->id])); ?>" class="btn btn-sm btn-primary">+ Request Test</a>
                        <?php endif; ?>
                    </div>

                    <?php if($patient->labTests->isEmpty()): ?>
                        <p class="text-muted mb-0">No lab tests recorded yet.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-sm align-middle">
                                <thead>
                                <tr>
                                    <th>Test</th>
                                    <th>Requested by</th>
                                    <th>Status</th>
                                    <th>Result</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__currentLoopData = $patient->labTests->sortByDesc('requested_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo e(route('lab-tests.show', $test)); ?>"><?php echo e($test->test_name); ?></a>
                                        </td>
                                        <td>Dr. <?php echo e($test->doctor->name); ?></td>
                                        <td>
                                            <span class="badge badge-status-<?php echo e($test->status); ?>"><?php echo e(str_replace('_', ' ', ucfirst($test->status))); ?></span>
                                        </td>
                                        <td class="small"><?php echo e($test->result ? \Illuminate\Support\Str::limit($test->result, 60) : '—'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\Users\future\Desktop\hospital-management-system\hospital-management-system\resources\views/patients/show.blade.php ENDPATH**/ ?>