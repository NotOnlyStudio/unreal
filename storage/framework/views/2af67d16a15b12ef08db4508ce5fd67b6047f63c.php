

<?php $__env->startSection("title","Notifications"); ?>



<?php $__env->startSection("content"); ?>

<div class="container">
    <notifications :notifications="<?php echo e($notification); ?>"></notifications>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make("layouts.cabinet", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Projects\Website\unreal\resources\views/cabinet/notifications.blade.php ENDPATH**/ ?>