@if (Auth::check() && Auth::user()->ban == true)
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet">
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
@else
    <!DOCTYPE html>
    <html lang="en" prefix="og: http://ogp.me/ns#">
        <?php
        $user = Auth::user();
        $user = $user !== null ? collect($user->toArray())->only(["name", "photo", "id", "permisions"]) : 0;
        ?>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- <link rel="preload" href="{{asset("js/app.js")}}" as="script"> alternate -->
        <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet" onload="this.rel='stylesheet'">
        <link rel="stylesheet" href="{{asset("css/preloader.css")}}" onload="this.rel='stylesheet'">
        <link rel="stylesheet" href="{{asset("css/main.css?v0.0.0.2")}}" onload="this.rel='stylesheet'">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="canonicial" href="{{url()->full()}}"/>
        <meta name="keywords" content="@yield('keywords')">
        <meta name="description" content="@yield('description')">
        <meta name="google" content="nositelinkssearchbox">
        <meta name="google" content="notranslate">
        <meta name="subject" content="3d models Marketplace">
        <meta content="website">
        <meta name="msapplication-navbutton-color" content="#000">
        <meta name="theme-color" content="#000">
        <meta name="theme-color" content="#000">

        <!-- <script type="text/javascript">
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
         -->


        <style>
            .language__text {
                color: #fff !important
            }

            .language-item img {display: none}

            .language .p-4, .language .py-2  {
                padding: 0 !important;
            }
            .language .grid {
                display: block !important;
            }
            .language .m-4 {
                margin: 0 !important;
            }

            .language span {
                padding: 3px;
                display: flex;
                justify-content: center;
                text-align: center;
            }

            .banner {
                margin-top: -40px;
            }
        </style>
        <script></script>
        <script src="https://securepay.tinkoff.ru/html/payForm/js/tinkoff_v2.js"></script>
        <script type="application/ld+json">
            @yield("meta")
        </script>
        @yield("meta_data")
        <title translate="no">@yield("title")</title>
    </head>
    @if($_SERVER['REQUEST_URI'] == "/linker-basket")
        <body style="height: 100%">
        @else
            <body>
            @endif
            @php
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
                    ],
                    [
                        'type'=>'garanty',
                        'href'=>'/garanty',
                        'title'=>'Warranty obligations  '
                    ]
                ];
            @endphp

            @if($_SERVER['REQUEST_URI'] == "/linker-basket")
                <div class="container-fluid" id="app">

                    @php
                        if(!isset($_COOKIE['user']) && Auth::check()){
                            setcookie("user",true,strtotime( '+7 days' ));
                        }else{
                            $_COOKIE['user'] = false;
                        }
                    @endphp

                    <noscript>
                        <div class="alert alert-danger">
                            <h3>For the site to work correctly, you must enable JavaScript</h3>
                        </div>
                    </noscript>
                    @if(!isset($_COOKIE['acceptedCookies']))
                        <cookies-modal></cookies-modal/>
                    @endif
                    <report-form v-if="reportWrapper" @close-modal="reportWrapper = false"></report-form>
                    <div class="d-flex container content-right @if(!isset($challengeShow)) double-column @endif" style="  display: flex;
  align-items: center;
  justify-content: center;
  height: 100%;">
                        @yield('content')
                    </div>
                </div>
            @else
                <div class="container-fluid" id="app">

                    <vheader site-name="UnrealShop" :notifications-counts="{{json_encode($counts)}}" :user="{{$user}}"
                             links="{{json_encode($menu)}}"/>
                    </vheader>

                    @if (!Session::has("Preloader"))
                        <div class="preloader__wrapper">
                            <div class="preloader__inner">
                                <div class="preloader-logo__wrapper">
                                    <img src="/assets/logo-unreal.png" class="preloader-logo" alt="">
                                </div>
                                <div class="logo-title-wrapper">
                                    <p class="logo-title">UnrealShop<span>©</span></p>

                                </div>
                            </div>
                        </div>
                        @php
                            $lifetime = time() + 60 * 60 * 24 * 365;
                            Session::put('Preloader', $lifetime);
                        @endphp
                    @endif
                    <app @if(isset($avard)) :avard-page="{{json_encode($avard)}}" @endif></app>
                    <right-menu></right-menu>
                    @php
                        if(!isset($_COOKIE['user']) && Auth::check()){
                            setcookie("user",true,strtotime( '+7 days' ));
                        }else{
                            $_COOKIE['user'] = false;
                        }

                    @endphp

                    <noscript>
                        <div class="alert alert-danger">
                            <h3>For the site to work correctly, you must enable JavaScript</h3>
                        </div>
                    </noscript>
                    @if(!isset($_COOKIE['acceptedCookies']))
                        <cookies-modal></cookies-modal/>
                    @endif
                    <report-form v-if="reportWrapper" @close-modal="reportWrapper = false"></report-form>
                    <div class="d-flex container content-right @if(!isset($challengeShow)) double-column @endif">
                        @yield('content')
                        @if(!isset($challengeShow) || $challengeShow != false)
                            <challenge-banner ref="challenge"></challenge-banner>
                        @endif
                    </div>
                    <footer>
{{--                        <p style="color: white" id="languageText">ИП Козловский Дмитрий Александрович | ИНН 616601324444 | ОГРНИП 31318774600570607 | E-mail: info@arhiteach.com | Телефоны +74957770186</p>--}}
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                const selectedLanguage = localStorage.getItem('lang');
                                const languageTextElement = document.getElementById('languageText');

                                // if (selectedLanguage === 'RU') {
                                //     languageTextElement.textContent = 'ИП Козловский Дмитрий Александрович | ИНН 616601324444 | ОГРНИП 31318774600570607 | E-mail: info@arhiteach.com | Телефоны +78001000984, +74957770186';
                                // }
                            });
                        </script>
                        <ul class="list-unstyled">
                            <li><a href="/forum">Forum</a></li>
                            <li><a href="/blog">Blog</a></li>
                            <li><a href="/gallery">Gallery</a></li>
                            <li><a href="/store">Store</a></li>
                            @auth
                                <li @click.prevent.stop="reportWrapper = true"
                                    @click.prevent.stop="reportWrapper = true">
                                    <v-footer name="support" type="1" href="#"></v-footer>
                                </li>
                            @endauth
                            <li><a href="/faq">FAQ</a></li>
                        </ul>
                        <div class="d-flex terms  my-2 flex-lg-row flex-md-row flex-column">
                            <a href="#" onclick="openTermsPDF('/polytics/terms-of-use')">Terms of
                                use</a>
                            <a href="#" onclick="openTermsPDF('/polytics/pol')" target="_blank">Payment procedure</a>
                            <a href="#" onclick="openTermsPDF('/polytics/privacy-policy')" target="_blank">Privacy
                                policy</a>
                            <a href="#" onclick="openTermsPDF('/polytics/cookie-policy')" target="_blank">Cookie
                                policy</a>
                            <a href="#" onclick="openTermsPDF('/polytics/content-policy')" target="_blank">Content
                                policy</a>

                            <script>
                                function openTermsPDF(file) {
                                    let lang = localStorage.getItem('lang');
                                    if (lang === null) {
                                      lang = 'en';
                                    }

                                    var filePath = file + lang + '.pdf';
                                    console.log(filePath)
                                    window.open(filePath);
                                }

                            </script>


                        </div>
                        <div class="w-100 d-flex justify-content-center my-3 socials">
                            @php
                                $socilas = json_decode(\File::get(resource_path()."/local-settings/links.json"));
                            @endphp
                            {{--                            <a target="_blank" href="{{$socilas->instagram}}"><img--}}
                            {{--                                      src="{{asset('img/socials/inst.svg')}}" alt="instagram"></a>--}}
                            <a target="_blank" href="{{$socilas->youtube}}" class="mx-3"><img
                                    src="{{asset('img/socials/youtube.svg')}}" alt="youtube"></a>
                            {{--                            <a target="_blank" href="{{$socilas->facebook}}"><img--}}
                            {{--                                    src="{{asset('img/socials/facebook.svg')}}" alt="facebook"></a>--}}
                        </div>
                        <span class="copyright">© UnrealShop. All rights reserved</span>
                    </footer>
                </div>
            @endif
            <script type="text/javascript" src="{{asset('js/app.js?v1.0.0.19')}}" defer></script>
            </body>


    </html>
@endif
