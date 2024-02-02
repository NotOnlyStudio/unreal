

<?php $__env->startSection("title","Models"); ?>

<?php $__env->startSection("content"); ?>
    <div class="container">
        <models-page :server-data="<?php echo e(json_encode([$moderated,$moderation])); ?>"></models-page>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.user", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Аяз\Desktop\unreal\resources\views/user/models.blade.php ENDPATH**/ ?>