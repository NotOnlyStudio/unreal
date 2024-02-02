<?php if(Auth::check() && Auth::user()->ban == true): ?>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?php echo e(asset('/css/bootstrap.min.css')); ?>" rel="stylesheet">
        <title>Was banned</title>
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    </head>
    <body>
    <div style="height: 100vh"
         class="container-fluid bg-dark d-flex flex-column align-items-center justify-content-center">
        <div class="alert alert-danger">
            <h1>Your account was banned.</h1>
        </div>
    </div>
    </body>
    </html>
<?php else: ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?php echo e(asset('/css/bootstrap.min.css')); ?>" rel="stylesheet">
        <link rel="stylesheet" href="<?php echo e(asset("css/preloader.css")); ?>">
        <link rel="stylesheet" href="<?php echo e(asset("css/main.css?1")); ?>">
        <title><?php echo $__env->yieldContent("title"); ?></title>
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    </head>
    <body>
    <div class="container-fluid" id="app">
        <?php
            $user = 0;
            $counts = [];
            if(Auth::check()){
                $user = Auth::user();
                $counts = \App\Models\NotificationCounter::getCountsData($user->id);
            }
            $menu = [
                [
                    'type'=>'button',
                    'buttonType'=>"buy_models",
                    'href'=>'/basket',
                    'title'=>'Buy models'
                ],
                [
                    'type'=>'button',
                    'buttonType'=>"default",
                    'href'=>'/upload-model',
                    'title'=>'Upload Model'
                ],
                [
                    'type'=>'link',
                    'href'=>'/forum',
                    'title'=>'Forum'
                ],
                [
                    'type'=>'link',
                    'href'=>'/blog',
                    'title'=>'Blog'
                ],
                [
                    'type'=>'link',
                    'href'=>'/gallery',
                    'title'=>'Gallery'
                ],
                [
                    'type'=>'link',
                    'href'=>'/store',
                    'title'=>'Store'
                ]
            ];
        ?>
        <vheader site-name="UnrealShop" :notifications-counts="<?php echo e(json_encode($counts)); ?>" :user="<?php echo e($user); ?>"
                 links="<?php echo e(json_encode($menu)); ?>"/>
        </vheader>
        <banner
            site-name="UnrealShop"
            site-description="models for Unreal Engine"
        ></banner>
        <?php if(!Session::has("Preloader")): ?>
            <div class="preloader__wrapper">
                <div class="preloader__inner">
                    <div class="preloader-logo__wrapper">
                        <img src="image.png" class="preloader-logo" alt="">
                    </div>
                    <div class="logo-title-wrapper">
                        <p class="logo-title">UnrealShop<span>©</span></p>
                    </div>
                </div>
            </div>
            <?php
                $lifetime = time() + 60 * 60 * 24 * 365;
                Session::put('Preloader', $lifetime);
            ?>
        <?php endif; ?>
        <div class="content-right <?php if($_SERVER['REQUEST_URI'] != "/cabinet/profit"): ?> double-column <?php endif; ?>">
            <cabinet-header-user <?php if($_SERVER['REQUEST_URI'] == "/cabinet/profit"): ?> columns="0" <?php endif; ?>
            :user-info="<?php echo e(\App\Models\User::where('id','=',$userNoOwner)->with('rang:id,name')->get()); ?>"
            <?php if(isset($userNoOwner) && !is_null($userNoOwner)): ?>:user-no-owner="<?php echo e($userNoOwner); ?>"<?php endif; ?>
            >
                <?php echo $__env->yieldContent('errors'); ?>
            </cabinet-header-user>

            <?php echo $__env->yieldContent("content"); ?>
            <challenge-banner></challenge-banner>
        </div>

        <report-form v-if="reportWrapper" @close-modal="reportWrapper = false"></report-form>

        <footer>
        <p style="color: white" id="languageText">ИП Козловский Дмитрий Александрович | ИНН 616601324444</p>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const selectedLanguage = localStorage.getItem('lang');
                    const languageTextElement = document.getElementById('languageText');

                    if (selectedLanguage === 'RU') {
                        languageTextElement.textContent = 'ИП Козловский Дмитрий Александрович | ИНН 616601324444';
                    }
                });
            </script>
            <ul class="list-unstyled">
                <li><a href="/forum">Forum</a></li>
                <li><a href="/blog">Blog</a></li>
                <li><a href="/gallery">Gallery</a></li>
                <li><a href="/store">Store</a></li>
                <!-- <li><a href="/cabinet/notifications">Account</a></li> -->
                <?php if(auth()->guard()->check()): ?>
                    <li><a href="#" @click.prevent.stop="reportWrapper = true">Support</a></li>
                <?php endif; ?>
                <li><a href="/faq">FAQ</a></li>
            </ul>
            <div class="d-flex terms  my-2 flex-lg-row flex-md-row flex-column">
                            <a href="#" onclick="openTermsPDF('/polytics/terms-of-use')">Terms of
                                use</a>
                            <a href="#" onclick="openTermsPDF('/polytics/privacy-policy')" target="_blank">Privacy
                                policy</a>
                            <a href="#" onclick="openTermsPDF('/polytics/cookie-policy')" target="_blank">Cookie
                                policy</a>
                            <a href="#" onclick="openTermsPDF('/polytics/content-policy')" target="_blank">Content
                                policy</a>

                                <script>
                                    function openTermsPDF(file) {
                                        var lang = localStorage.getItem('lang');
                                        var filePath = file + lang + '.pdf';
                                        console.log(filePath)
                                        window.open(filePath);
                                    }
                                </script>
                        </div>
            <div class="w-100 d-flex justify-content-center my-3 socials">
                <?php
                    $socilas = json_decode(\File::get(resource_path()."/local-settings/links.json"));
                ?>


                <a target="_blank" href="<?php echo e($socilas->youtube); ?>" class="mx-3"><img
                        src="<?php echo e(asset('img/socials/youtube.svg')); ?>" alt="youtube"></a>


            </div>
            <span class="copyright">© UnrealShop. All rights reserved</span>
        </footer>
    </div>
    <script src="<?php echo e(asset('js/cabinet.js')); ?>"></script>

    <script type="text/javascript">
            function googleTranslateElementInit() {
                new google.translate.TranslateElement(
                    { pageLanguage: 'en', autoDisplay: false },
                    'app'
                );
            }
        </script>
        <script
            type="text/javascript"
            src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"
        ></script>
        <script
            type="text/javascript"
            src="https://cdn.jsdelivr.net/gh/lewis-kori/vue-google-translate@main/src/utils/translatorRegex.js"
        ></script>
</body>
    </html>
<?php endif; ?>
<?php /**PATH C:\Users\Аяз\Desktop\unreal\resources\views/layouts/user.blade.php ENDPATH**/ ?>