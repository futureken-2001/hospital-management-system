<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve(['title' => 'Patients'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Patients</h3>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', \App\Models\Patient::class)): ?>
            <a href="<?php echo e(route('patients.create')); ?>" class="btn btn-primary">+ Register Patient</a>
        <?php endif; ?>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('patients.index')); ?>" class="row g-2 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small text-muted mb-1">Search by name, patient number, or phone</label>
                    <input type="text" name="q" value="<?php echo e($q); ?>" class="form-control" placeholder="e.g. P-0001 or Jane Doe">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Registered on</label>
                    <input type="date" name="date" value="<?php echo e($date); ?>" class="form-control">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-grow-1">Filter</button>
                    <?php if($q || $date): ?>
                        <a href="<?php echo e(route('patients.index')); ?>" class="btn btn-outline-secondary">Clear</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?php if($patients->isEmpty()): ?>
                <p class="text-muted mb-0">No patients match your search.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                        <tr>
                            <th>Patient #</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Residence</th>
                            <th>Phone</th>
                            <th>Registered</th>
                            <th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="fw-semibold"><?php echo e($patient->patient_number); ?></td>
                                <td><?php echo e($patient->name); ?></td>
                                <td><?php echo e($patient->age); ?></td>
                                <td><?php echo e($patient->residence); ?></td>
                                <td><?php echo e($patient->phone); ?></td>
                                <td class="small text-muted"><?php echo e($patient->created_at->format('d M Y')); ?></td>
                                <td class="text-end">
                                    <a href="<?php echo e(route('patients.show', $patient)); ?>" class="btn btn-sm btn-outline-primary">View</a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $patient)): ?>
                                        <a href="<?php echo e(route('patients.edit', $patient)); ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <?php echo e($patients->links()); ?>

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
<?php /**PATH C:\Users\future\Desktop\hospital-management-system\hospital-management-system\resources\views/patients/index.blade.php ENDPATH**/ ?>