<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve(['title' => 'Admin Dashboard'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    <h3 class="mb-4">Admin Dashboard</h3>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-bg-primary">
                <div class="card-body">
                    <div class="small text-white-75">Total Patients</div>
                    <div class="fs-2 fw-bold"><?php echo e($stats['total_patients']); ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-success">
                <div class="card-body">
                    <div class="small text-white-75">Doctors on Staff</div>
                    <div class="fs-2 fw-bold"><?php echo e($stats['total_doctors']); ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-info">
                <div class="card-body">
                    <div class="small text-white-75">Appointments Today</div>
                    <div class="fs-2 fw-bold"><?php echo e($stats['appointments_today']); ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-warning">
                <div class="card-body">
                    <div class="small">Pending Lab Tests</div>
                    <div class="fs-2 fw-bold"><?php echo e($stats['pending_lab_tests']); ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Quick links</h5>
                    <div class="d-grid gap-2">
                        <a href="<?php echo e(route('patients.index')); ?>" class="btn btn-outline-primary">Manage Patients</a>
                        <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-outline-primary">View Daily Queue</a>
                        <a href="<?php echo e(route('lab-tests.index')); ?>" class="btn btn-outline-primary">View Lab Tests</a>
                        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-primary">Manage Staff Accounts</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Lab summary</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            Pending tests <span class="badge bg-warning text-dark"><?php echo e($stats['pending_lab_tests']); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Completed tests <span class="badge bg-success"><?php echo e($stats['completed_lab_tests']); ?></span>
                        </li>
                    </ul>
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
<?php /**PATH C:\Users\future\Desktop\hospital-management-system\hospital-management-system\resources\views/dashboard/admin.blade.php ENDPATH**/ ?>