

<?php $__env->startSection("title","Purchases"); ?>
<?php $__env->startSection("content"); ?>
<div class="d-flex flex-column container">
   <div class="search__in d-flex w-100 justify-content-between mt-2">
      <form class="search__form user-search search-sm">
         <svg xmlns="http://www.w3.org/2000/svg" width="23.206" height="23.206" viewBox="0 0 23.206 23.206">
            <g id="Group_16" data-name="Group 16" transform="translate(-41589.794 -6523)">
               <g id="Ellipse_2" data-name="Ellipse 2" transform="translate(41595 6523)" fill="none" stroke="#707070" stroke-width="1">
                  <circle cx="9" cy="9" r="9" stroke="none"></circle>
                  <circle cx="9" cy="9" r="8.5" fill="none"></circle>
               </g>
               <line id="Line_2" data-name="Line 2" y1="7" x2="8" transform="translate(41590.5 6538.5)" fill="none" stroke="#707070" stroke-linecap="round" stroke-width="1"></line>
            </g>
         </svg>
         <input type="text" name="searchBy" placeholder="Search" value="<?php echo e(isset($_GET['searchBy']) ? $_GET['searchBy'] : ''); ?>">
      </form>
      <b-dropdown id="dropdown-1" text="Sort by" variant="red">
        <b-dropdown-item href="?order=new">Newest</b-dropdown-item>
        <b-dropdown-item href="?order=old">Oldest</b-dropdown-item>
        <b-dropdown-item href="?order=com">Comments</b-dropdown-item>
    </b-dropdown>
   </div>
   <purchases-page  :products="<?php echo e(json_encode($products)); ?>"></purchases-page>

    <?php if(isset($purchases)): ?>
        <?php echo e($purchases->links()); ?>

    <?php else: ?>
        <?php echo e($products->links()); ?>

    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make("layouts.cabinet", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Аяз\Desktop\unreal\resources\views/cabinet/purchases.blade.php ENDPATH**/ ?>