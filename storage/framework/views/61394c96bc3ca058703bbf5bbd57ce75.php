<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve(['title' => 'Daily Queue'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Daily Queue</h3>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Appointment::class)): ?>
            <a href="<?php echo e(route('appointments.create')); ?>" class="btn btn-primary">+ Add to Queue</a>
        <?php endif; ?>
    </div>

    <form method="GET" action="<?php echo e(route('appointments.index')); ?>" class="row g-2 align-items-end mb-4">
        <div class="col-md-3">
            <label class="form-label small text-muted mb-1">Date</label>
            <input type="date" name="date" value="<?php echo e($date); ?>" class="form-control" onchange="this.form.submit()">
        </div>
    </form>

    <?php if($appointments->isEmpty()): ?>
        <div class="card">
            <div class="card-body text-muted">
                No appointments booked for <?php echo e(\Carbon\Carbon::parse($date)->format('d M Y')); ?>.
            </div>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php ($doctorAppointments = $appointments->get($doctor->id, collect())); ?>
                <?php if($doctorAppointments->isNotEmpty()): ?>
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-header bg-white fw-semibold">
                                Dr. <?php echo e($doctor->name); ?>

                                <span class="badge bg-secondary float-end"><?php echo e($doctorAppointments->count()); ?> today</span>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm align-middle mb-0 table-queue">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Patient</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $doctorAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="fw-bold"><?php echo e($appointment->queue_number); ?></td>
                                            <td>
                                                <a href="<?php echo e(route('patients.show', $appointment->patient)); ?>"><?php echo e($appointment->patient->name); ?></a>
                                            </td>
                                            <td>
                                                <?php if($appointment->isWaiting()): ?>
                                                    <span class="badge badge-status-waiting">Waiting</span>
                                                <?php elseif($appointment->isCalled()): ?>
                                                    <span class="badge badge-status-called">Called</span>
                                                <?php else: ?>
                                                    <span class="badge badge-status-done">Done</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <?php if($appointment->isWaiting()): ?>
                                                    <form method="POST" action="<?php echo e(route('appointments.destroy', $appointment)); ?>" class="d-inline" onsubmit="return confirm('Remove from queue?');">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>

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
<?php /**PATH C:\Users\future\Desktop\hospital-management-system\hospital-management-system\resources\views/appointments/index.blade.php ENDPATH**/ ?>