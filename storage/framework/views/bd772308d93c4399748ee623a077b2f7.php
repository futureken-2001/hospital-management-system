<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Hospital Management System')); ?></title>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.scss', 'resources/js/app.js']); ?>
</head>
<body class="bg-primary d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="w-100" style="max-width: 420px;">
        <div class="text-center text-white mb-4">
            <h1 class="fw-bold">🏥 <?php echo e(config('app.name')); ?></h1>
            <p class="text-white-50 mb-0">Internal staff access only</p>
        </div>

        <div class="card shadow-lg border-0">
            <div class="card-body p-4">

                <?php if(session('status')): ?>
                    <div class="alert alert-success"><?php echo e(session('status')); ?></div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php echo e($slot); ?>

            </div>
        </div>
    </div>

</body>
</html>
<?php /**PATH C:\Users\future\Desktop\hospital-management-system\hospital-management-system\resources\views/components/guest-layout.blade.php ENDPATH**/ ?>