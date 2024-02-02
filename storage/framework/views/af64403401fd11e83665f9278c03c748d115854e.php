


<?php $__env->startSection("title","Login"); ?>

<?php $__env->startSection("content"); ?>
    <div class="container">
        <login-form <?php if(Cookie::get("emailConfirm") !== null): ?> confirmed="0" <?php else: ?> confirmed="1" <?php endif; ?>></login-form>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("layouts.app", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Аяз\Desktop\unreal\resources\views/login.blade.php ENDPATH**/ ?>