<!--

=========================================================
* Impact Design System - v1.0.0
=========================================================

* Product Page: https://www.creative-tim.com/product/impact-design-system
* Copyright 2010 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://github.com/creativetimofficial/impact-design-system/blob/master/LICENSE.md)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

-->
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Primary Meta Tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('argonfront') }}/img/apple-icon.png">
    <link rel="icon" type="image/png" href="{{ asset('argonfront') }}/img/favicon.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:image" content="{{ config('global.site_logo') }}">
    <title>{{ config('global.site_name','QRTiger') }}</title>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    @if (config('settings.google_analytics'))
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo config('settings.google_analytics'); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', '<?php echo config('settings.google_analytics'); ?>');
        </script>
    @endif

    @yield('head')
    @laravelPWA

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">


    <!-- Fontawesome -->
    <link type="text/css" href="{{ asset('impactfront') }}/vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">


    <!-- Nucleo icons -->
    <link rel="stylesheet" href="{{ asset('impactfront') }}/vendor/nucleo/css/nucleo.css" type="text/css">

    <!-- Front CSS -->
    <link type="text/css" href="{{ asset('impactfront') }}/css/front.min.css" rel="stylesheet">

    <!-- Custom CSS by mobidonia -->
    <link type="text/css" href="{{ asset('custom') }}/css/custom_qr.css" rel="stylesheet">

    <!-- Custom CSS defined by admin -->
    <link type="text/css" href="{{ asset('byadmin') }}/front.css" rel="stylesheet">
</head>

<body>

    <header class="header-global">
        @include('qrsaas.partials.nav')
    </header>

    <main>

         <!-- Loader -->
        <div class="preloader bg-soft flex-column justify-content-center align-items-center">
            <div class="loader-element">
                <span class="loader-animated-dot"></span>
                <img src="{{ config('global.site_logo') }}" height="40" alt="logo">
            </div>
        </div>

        <!-- Hero 1 -->
        @include('qrsaas.partials.hero')

        <!-- Product -->
        @include('qrsaas.partials.product')

        <!-- Pricing -->
        @include('qrsaas.partials.pricing')

        <!-- Testimonials -->
        @include('qrsaas.partials.testimonials')

        <!-- DEMO -->
        @include('qrsaas.partials.demo')

        <!-- Footer -->
        @include('qrsaas.partials.footer')

    </main>

    <!-- Core -->
    <script src="{{ asset('impactfront') }}/vendor/jquery/dist/jquery.min.js"></script>
    <script src="{{ asset('impactfront') }}/vendor/popper.js/dist/umd/popper.min.js"></script>
    <script src="{{ asset('impactfront') }}/vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('impactfront') }}/vendor/headroom.js/dist/headroom.min.js"></script>

    <!-- Vendor JS -->
    <script src="{{ asset('impactfront') }}/vendor/onscreen/dist/on-screen.umd.min.js"></script>
    <script src="{{ asset('impactfront') }}/vendor/waypoints/lib/jquery.waypoints.min.js"></script>
    <script src="{{ asset('impactfront') }}/vendor/jarallax/dist/jarallax.min.js"></script>
    <script src="{{ asset('impactfront') }}/vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <!-- Impact JS -->
    <script src="{{ asset('impactfront') }}/js/front.js"></script>

    <!-- Custom JS defined by admin -->
    <?php echo file_get_contents(base_path('public/byadmin/front.js')) ?>

    <!-- Notify JS -->
    <script src="{{ asset('custom') }}/js/notify.min.js"></script>

    <!-- CKEditor -->
    <script src="{{ asset('ckeditor') }}/ckeditor.js"></script>
    <script>
        var USER_ID = '{{  auth()->user()&&auth()->user()?auth()->user()->id:"" }}';
    </script>

    

    <script>

    window.onload = function () {

        var ifUser = {!! json_encode(  auth()->user()&&auth()->user() && auth()->user()->hasRole('admin') ? true : false) !!};

        if(ifUser){
            initializeCKEditor();

            changeContentEditable(true);

            showEditBtns();
            //$(".ckedit_edit").show();

        }else{
            changeContentEditable(false);

            //$(".ckedit_edit").hide();
        }

        CKEDITOR.on('instanceReady', function(event) {
            var editor = event.editor;

            editor.on('blur', function(e) {
                //console.log(editor.getSnapshot());
                var html=editor.getSnapshot()
                var dom=document.createElement("DIV");
                dom.innerHTML=html;
                var plain_text=(dom.textContent || dom.innerText);

                var APP_URL = {!! json_encode(url('/')) !!}

                var locale = {!! json_encode(Config::get('app.locale')) !!}

                changeContent(APP_URL, locale, editor.name, plain_text)
            });
        });

        function showEditBtns(){
            $('.ckedit_btn').each(function(i, obj) {
                $(this).show();
            });
        }

        function initializeCKEditor(){
            var elements = CKEDITOR.document.find('.ckedit'),
            i = 0,
            element;

            while ( ( element = elements.getItem( i++ ) ) ) {
                //CKEDITOR.inline(element);
                CKEDITOR.inline(element, {
                    removePlugins: 'link, image',
                    removeButtons: 'Bold,Italic,Underline,Strike,Subscript,Superscript,RemoveFormat,Scayt,SpecialChar,About,Styles,Cut,Copy,Undo,Redo,Outdent,Indent,Table,HorizontalRule,NumberedList,BulletedList,Blockquote,Format'
                } );
            }
        }

        $(".ckedit_btn").click(function() {
            var next = $(this).next().attr('key');

            var editor = CKEDITOR.instances[next];
            editor.focus();
        });

        function changeContentEditable(bool){
            $('.ckedit').each(function(i, obj) {
                $(this).attr("contenteditable",bool);
            });
        }

        function changeContent(APP_URL, locale, key, value){
            var isDemo={!! config('settings.is_demo')|config('settings.is_demo') !!};
            if(!isDemo){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type:'POST',
                    url: APP_URL+'/admin/languages/'+locale,
                    dataType: 'json',
                    data: {
                        group: "qrlanding",
                        key: key,
                        language: locale,
                        value: value
                    },
                    success:function(response){
                        if(response){
                            var msg = {!! json_encode(__('qrlanding.success')) !!}

                            notify(msg);
                        }
                    }, error: function (response) {
                    //alert(response.responseJSON.errMsg);
                }
            })

            }else{
              //ok
              notify("Changing strings not allowed in demo mode.");
            }



        }

        
    }
    </script>

</body>

</html>
