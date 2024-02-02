


<?php $__env->startSection("title","Basket"); ?>


<?php $__env->startSection("content"); ?>
    <div class="container">
        <p class="breadcrumbs">
            <a href="/" style="color: #b3b3b3; text-decoration: none">UnrealShop</a> >
            Basket
        </p>
        <p class="block-title block-title__untransformed">Cart</p>
        <basket-wrapper standart-price="<?php echo e(\Config::get('app.payments.price')); ?>" :course="<?php echo e($course); ?>" :auth="<?php echo e(Auth::check() ? Auth::user() : 0); ?>"></basket-wrapper>
    </div>

    <script type="text/javascript" src="https://js.bepaid.tech/widget/be_gateway.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Аяз\Desktop\unreal\resources\views/Basket/basket.blade.php ENDPATH**/ ?>