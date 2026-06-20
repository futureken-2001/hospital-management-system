<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve(['title' => 'Lab Tests'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Lab Tests</h3>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\LabTest::class)): ?>
            <a href="<?php echo e(route('lab-tests.create')); ?>" class="btn btn-primary">+ Request Lab Test</a>
        <?php endif; ?>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('lab-tests.index')); ?>" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Status</label>
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">All statuses</option>
                        <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                        <option value="in_progress" <?php echo e(request('status') === 'in_progress' ? 'selected' : ''); ?>>In progress</option>
                        <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>Completed</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if($labTests->isEmpty()): ?>
                <p class="text-muted mb-0">No lab tests found.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                        <tr>
                            <th>Requested</th>
                            <th>Patient</th>
                            <th>Test</th>
                            <th>Doctor</th>
                            <th>Lab Tech</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $labTests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="small text-muted"><?php echo e($test->requested_at->format('d M Y H:i')); ?></td>
                                <td>
                                    <a href="<?php echo e(route('patients.show', $test->patient)); ?>"><?php echo e($test->patient->name); ?></a>
                                    <div class="text-muted small"><?php echo e($test->patient->patient_number); ?></div>
                                </td>
                                <td><?php echo e($test->test_name); ?></td>
                                <td>Dr. <?php echo e($test->doctor->name); ?></td>
                                <td><?php echo e($test->labTechnician->name ?? '—'); ?></td>
                                <td>
                                    <span class="badge badge-status-<?php echo e($test->status); ?>"><?php echo e(str_replace('_', ' ', ucfirst($test->status))); ?></span>
                                </td>
                                <td class="text-end">
                                    <a href="<?php echo e(route('lab-tests.show', $test)); ?>" class="btn btn-sm btn-outline-primary">View</a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('updateResult', $test)): ?>
                                        <a href="<?php echo e(route('lab-tests.edit', $test)); ?>" class="btn btn-sm btn-outline-secondary">Update</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <?php echo e($labTests->links()); ?>

                </div>
            <?php endif; ?>
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
<?php /**PATH C:\Users\future\Desktop\hospital-management-system\hospital-management-system\resources\views/lab_tests/index.blade.php ENDPATH**/ ?>