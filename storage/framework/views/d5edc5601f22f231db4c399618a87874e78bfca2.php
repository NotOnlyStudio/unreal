

<?php $__env->startSection("title",$info->title); ?>

<?php
    $meta = json_decode($info->meta);
    $meta = $meta ? $meta : (Object)[
        "keywords"=>"",
        "description"=>"",
    ];
    $photo = $_SERVER['HTTP_HOST']."/storage/app/public/models/user-".$info->user_id."/".json_decode($info->photos)[0];
    $url = url()->current();
?>

<?php $__env->startSection("keywords", $meta->keywords); ?>
<?php $__env->startSection("description",$meta->description); ?>

<?php $__env->startSection("meta"); ?>
{
    "@context":"http://schema.org",
    "@type":"3DModel",
    "name":"<?php echo e($info->title); ?>",
    "uploadDate":"<?php echo e($info->created_at); ?>",
    "image":"<?php echo e($photo); ?>",
    "description":"<?php echo e($meta->description != "" ? $meta->description : json_decode($info->props)->description); ?>",
    "url":"<?php echo e($url); ?>",
    "author":{
        "@context":"http://schema.org",
        "@type":"Person",
        "name":"<?php echo e($info->users->name); ?>",
        "url":"<?php echo e($_SERVER['HTTP_HOST']."/user/".$info->users->id); ?>"
    },
    "aggregateRating":
    {
        "@type":"AggregateRating",
        "@context":"http://schema.org",
        "ratingValue":<?php echo e($info->likes_count); ?>,
        "ratingCount":<?php echo e($info->likes_count+$info->dislikes_count); ?>

    }
}
<?php $__env->stopSection(); ?>

<?php $__env->startSection("meta_data"); ?>

<link rel="icon" type="image/png" sizes="32x32" href="<?php echo e($photo); ?>">
<meta property="og:title" content="<?php echo e($info->title); ?>" />
<meta property="og:url" content="<?php echo e($url); ?>" />
<meta property="og:type" content="product" />
<meta property="og:determiner" content="the" />
<meta property="og:description" content="Доступна для скачивания, Платформа: <?php echo e(json_decode($info->props)->version); ?>, <?php echo e(json_decode($info->props)->bake? "bake": ""); ?> <?php echo e(json_decode($info->props)->rtx? ",rtx": ""); ?>" />
<meta property="og:locale" content="en_US" />
<meta property="og:locale:alternate" content="en_EN" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:image" content="https://<?php echo e($photo); ?>" />
<meta property="og:image:width" content="10"/>
<meta property="og:image:height" content="10"/>
<meta property="og:site_name" content="<?php echo e(config('app.name')); ?>" />
<meta property="og:image:alt" content="<?php echo e($info->title); ?>" />
<meta property="og:image" content="https://<?php echo e($photo); ?>" />

<?php $__env->stopSection(); ?>

<?php $__env->startSection("content"); ?>

<model :info="<?php echo e($info); ?>"></model>

<?php $__env->stopSection(); ?>

<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Аяз\Desktop\unreal\resources\views/Models/ModelPage.blade.php ENDPATH**/ ?>