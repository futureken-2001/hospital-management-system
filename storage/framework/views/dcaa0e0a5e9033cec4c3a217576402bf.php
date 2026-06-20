<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e($title ?? config('app.name', 'Hospital Management System')); ?></title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.scss', 'resources/js/app.js']); ?>
</head>
<body class="bg-light" <?php if(auth()->guard()->check()): ?> data-user-id="<?php echo e(auth()->id()); ?>" data-user-role="<?php echo e(auth()->user()->role); ?>" <?php endif; ?>>

<?php if(auth()->guard()->check()): ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-semibold" href="<?php echo e(route('dashboard')); ?>">
                🏥 <?php echo e(config('app.name')); ?>

            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">Dashboard</a>
                    </li>

                    <?php if(in_array(auth()->user()->role, ['receptionist', 'super_admin', 'doctor', 'lab_technician'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('patients.*') ? 'active' : ''); ?>" href="<?php echo e(route('patients.index')); ?>">Patients</a>
                        </li>
                    <?php endif; ?>

                    <?php if(in_array(auth()->user()->role, ['receptionist', 'super_admin', 'doctor'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('appointments.*') ? 'active' : ''); ?>" href="<?php echo e(route('appointments.index')); ?>">Queue</a>
                        </li>
                    <?php endif; ?>

                    <?php if(in_array(auth()->user()->role, ['doctor', 'super_admin', 'lab_technician'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('lab-tests.*') ? 'active' : ''); ?>" href="<?php echo e(route('lab-tests.index')); ?>">Lab Tests</a>
                        </li>
                    <?php endif; ?>

                    <?php if(auth()->user()->isSuperAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>" href="<?php echo e(route('users.index')); ?>">Staff</a>
                        </li>
                    <?php endif; ?>
                </ul>

                <ul class="navbar-nav align-items-lg-center">
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle position-relative" href="#" id="notifBell" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            🔔
                            <span id="notifBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">
                                0
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-2" id="notifList" style="min-width: 320px; max-height: 400px; overflow-y: auto;" aria-labelledby="notifBell">
                            <li class="dropdown-item text-muted small" id="notifEmpty">No notifications yet.</li>
                        </ul>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo e(auth()->user()->name); ?>

                            <span class="badge bg-light text-primary text-uppercase ms-1"><?php echo e(str_replace('_', ' ', auth()->user()->role)); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>">My Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item">Log out</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<?php endif; ?>

<main class="py-4">
    <div class="container-fluid">

        <?php if(session('status')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('status')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php echo e($slot); ?>

    </div>
</main>

<!-- Real-time / popup notification toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
    <div id="liveToast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body" id="liveToastBody"></div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    </div>
</div>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\future\Desktop\hospital-management-system\hospital-management-system\resources\views/components/app-layout.blade.php ENDPATH**/ ?>