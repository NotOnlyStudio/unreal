

<?php $__env->startSection("title","Unreal Shop"); ?>

<?php
    $meta_info = json_decode(\Illuminate\Support\Facades\File::get(resource_path()."/local-settings/meta.txt"));
?>

<?php $__env->startSection("description",$meta_info->description); ?>
<?php $__env->startSection("keywords",$meta_info->keywords); ?>

<?php $__env->startSection("meta_data"); ?>
    <meta property="og:description" content="<?php echo e($meta_info->description); ?>" />
    <meta property="og:image:width" content="1200"/>
    <meta property="og:image:height" content="630"/>
    <meta property="og:site_name" content="<?php echo e(config('app.name')); ?>" />
    <meta property="og:image" content="https://<?php echo e($_SERVER['HTTP_HOST']); ?>/assets/logo-gray.png" />
    <meta property="og:image:alt" content="https://<?php echo e($_SERVER['HTTP_HOST']); ?>/assets/logo-gray.png" />
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <app-index :products-data="<?php echo e(json_encode($products)); ?>" :gallery-data="<?php echo e(json_encode($gallery)); ?>" :blogs-data="<?php echo e(json_encode($blog)); ?>"></app-index>
<?php $__env->stopSection(); ?>


<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Аяз\Desktop\unreal\resources\views/index.blade.php ENDPATH**/ ?>