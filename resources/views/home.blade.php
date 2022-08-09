@extends('layouts.app')
@section('content')
        <!-- Header-->
        <header class="masthead d-flex align-items-center">
            <div class="container px-4 px-lg-5 text-center">
                <h1 class="mb-1">Warnock Variety Show</h1>
                <h3 class="mb-5"><em>A Free Bootstrap Theme by Start Bootstrap</em></h3>
                <a class="btn btn-primary btn-xl" href="{!!action([\App\Http\Controllers\ShowController::class, 'show'], ['show' => \App\Models\Show::latest('date')->first()->id])!!}">Upcoming Show</a>
            </div>
        </header>
        <!-- About-->
        <section class="content-section bg-light" id="about">
            <div class="container px-4 px-lg-5 text-center">
                <div class="row gx-4 gx-lg-5 justify-content-center">
                    <div class="col-lg-10">
                        <h2>Stylish Portfolio is the perfect theme for your next project!</h2>
                        <p class="lead mb-5">
                            This theme features a flexible, UX friendly sidebar menu and stock photos from our friends at
                            <a href="https://unsplash.com/">Unsplash</a>
                            !
                        </p>
                        <a class="btn btn-dark btn-xl" href="#services">What We Offer</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Services-->
{{--        <section class="content-section bg-primary text-white text-center" id="services">--}}
{{--            <div class="container px-4 px-lg-5">--}}
{{--                <div class="content-section-heading">--}}
{{--                    <h3 class="text-secondary mb-0">Services</h3>--}}
{{--                    <h2 class="mb-5">What We Offer</h2>--}}
{{--                </div>--}}
{{--                <div class="row gx-4 gx-lg-5">--}}
{{--                    <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">--}}
{{--                        <span class="service-icon rounded-circle mx-auto mb-3"><i class="icon-screen-smartphone"></i></span>--}}
{{--                        <h4><strong>Responsive</strong></h4>--}}
{{--                        <p class="text-faded mb-0">Looks great on any screen size!</p>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-3 col-md-6 mb-5 mb-lg-0">--}}
{{--                        <span class="service-icon rounded-circle mx-auto mb-3"><i class="icon-pencil"></i></span>--}}
{{--                        <h4><strong>Redesigned</strong></h4>--}}
{{--                        <p class="text-faded mb-0">Freshly redesigned for Bootstrap 5.</p>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-3 col-md-6 mb-5 mb-md-0">--}}
{{--                        <span class="service-icon rounded-circle mx-auto mb-3"><i class="icon-like"></i></span>--}}
{{--                        <h4><strong>Favorited</strong></h4>--}}
{{--                        <p class="text-faded mb-0">--}}
{{--                            Millions of users--}}
{{--                            <i class="fas fa-heart"></i>--}}
{{--                            Start Bootstrap!--}}
{{--                        </p>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-3 col-md-6">--}}
{{--                        <span class="service-icon rounded-circle mx-auto mb-3"><i class="icon-mustache"></i></span>--}}
{{--                        <h4><strong>Question</strong></h4>--}}
{{--                        <p class="text-faded mb-0">I mustache you a question...</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </section>--}}
        <!-- Callout-->
{{--        <section class="callout">--}}
{{--            <div class="container px-4 px-lg-5 text-center">--}}
{{--                <h2 class="mx-auto mb-5">--}}
{{--                    Welcome to--}}
{{--                    <em>your</em>--}}
{{--                    next website!--}}
{{--                </h2>--}}
{{--                <a class="btn btn-primary btn-xl" href="https://startbootstrap.com/theme/stylish-portfolio/">Download Now!</a>--}}
{{--            </div>--}}
{{--        </section>--}}
        <!-- Portfolio-->
{{--        <section class="content-section" id="portfolio">--}}
{{--            <div class="container px-4 px-lg-5">--}}
{{--                <div class="content-section-heading text-center">--}}
{{--                    <h3 class="text-secondary mb-0">Portfolio</h3>--}}
{{--                    <h2 class="mb-5">Recent Projects</h2>--}}
{{--                </div>--}}
{{--                <div class="row gx-0">--}}
{{--                    <div class="col-lg-6">--}}
{{--                        <a class="portfolio-item" href="#!">--}}
{{--                            <div class="caption">--}}
{{--                                <div class="caption-content">--}}
{{--                                    <div class="h2">Stationary</div>--}}
{{--                                    <p class="mb-0">A yellow pencil with envelopes on a clean, blue backdrop!</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <img class="img-fluid" src="assets/img/portfolio-1.jpg" alt="..." />--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-6">--}}
{{--                        <a class="portfolio-item" href="#!">--}}
{{--                            <div class="caption">--}}
{{--                                <div class="caption-content">--}}
{{--                                    <div class="h2">Ice Cream</div>--}}
{{--                                    <p class="mb-0">A dark blue background with a colored pencil, a clip, and a tiny ice cream cone!</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <img class="img-fluid" src="assets/img/portfolio-2.jpg" alt="..." />--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-6">--}}
{{--                        <a class="portfolio-item" href="#!">--}}
{{--                            <div class="caption">--}}
{{--                                <div class="caption-content">--}}
{{--                                    <div class="h2">Strawberries</div>--}}
{{--                                    <p class="mb-0">Strawberries are such a tasty snack, especially with a little sugar on top!</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <img class="img-fluid" src="assets/img/portfolio-3.jpg" alt="..." />--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                    <div class="col-lg-6">--}}
{{--                        <a class="portfolio-item" href="#!">--}}
{{--                            <div class="caption">--}}
{{--                                <div class="caption-content">--}}
{{--                                    <div class="h2">Workspace</div>--}}
{{--                                    <p class="mb-0">A yellow workspace with some scissors, pencils, and other objects.</p>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <img class="img-fluid" src="assets/img/portfolio-4.jpg" alt="..." />--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </section>--}}
        <!-- Call to Action-->
{{--        <section class="content-section bg-primary text-white">--}}
{{--            <div class="container px-4 px-lg-5 text-center">--}}
{{--                <h2 class="mb-4">The buttons below are impossible to resist...</h2>--}}
{{--                <a class="btn btn-xl btn-light me-4" href="#!">Click Me!</a>--}}
{{--                <a class="btn btn-xl btn-dark" href="#!">Look at Me!</a>--}}
{{--            </div>--}}
{{--        </section>--}}
{{--        <!-- Map-->--}}
{{--        <div class="map" id="contact">--}}
{{--            <iframe src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Twitter,+Inc.,+Market+Street,+San+Francisco,+CA&amp;aq=0&amp;oq=twitter&amp;sll=28.659344,-81.187888&amp;sspn=0.128789,0.264187&amp;ie=UTF8&amp;hq=Twitter,+Inc.,+Market+Street,+San+Francisco,+CA&amp;t=m&amp;z=15&amp;iwloc=A&amp;output=embed"></iframe>--}}
{{--            <br />--}}
{{--            <small><a href="https://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=&amp;q=Twitter,+Inc.,+Market+Street,+San+Francisco,+CA&amp;aq=0&amp;oq=twitter&amp;sll=28.659344,-81.187888&amp;sspn=0.128789,0.264187&amp;ie=UTF8&amp;hq=Twitter,+Inc.,+Market+Street,+San+Francisco,+CA&amp;t=m&amp;z=15&amp;iwloc=A"></a></small>--}}
{{--        </div>--}}
@endsection
@push('scripts')
    <script>
        /*!
        * Start Bootstrap - Stylish Portfolio v6.0.5 (https://startbootstrap.com/theme/stylish-portfolio)
        * Copyright 2013-2022 Start Bootstrap
        * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-stylish-portfolio/blob/master/LICENSE)
        */
        window.addEventListener('DOMContentLoaded', event => {

            const sidebarWrapper = document.getElementById('sidebar-wrapper');
            let scrollToTopVisible = false;
            // Closes the sidebar menu
            const menuToggle = document.body.querySelector('.menu-toggle');
            menuToggle.addEventListener('click', event => {
                event.preventDefault();
                sidebarWrapper.classList.toggle('active');
                _toggleMenuIcon();
                menuToggle.classList.toggle('active');
            })

            // Closes responsive menu when a scroll trigger link is clicked
            var scrollTriggerList = [].slice.call(document.querySelectorAll('#sidebar-wrapper .js-scroll-trigger'));
            scrollTriggerList.map(scrollTrigger => {
                scrollTrigger.addEventListener('click', () => {
                    sidebarWrapper.classList.remove('active');
                    menuToggle.classList.remove('active');
                    _toggleMenuIcon();
                })
            });

            function _toggleMenuIcon() {
                const menuToggleBars = document.body.querySelector('.menu-toggle > .fa-bars');
                const menuToggleTimes = document.body.querySelector('.menu-toggle > .fa-xmark');
                if (menuToggleBars) {
                    menuToggleBars.classList.remove('fa-bars');
                    menuToggleBars.classList.add('fa-xmark');
                }
                if (menuToggleTimes) {
                    menuToggleTimes.classList.remove('fa-xmark');
                    menuToggleTimes.classList.add('fa-bars');
                }
            }

            // Scroll to top button appear
            document.addEventListener('scroll', () => {
                const scrollToTop = document.body.querySelector('.scroll-to-top');
                if (document.documentElement.scrollTop > 100) {
                    if (!scrollToTopVisible) {
                        fadeIn(scrollToTop);
                        scrollToTopVisible = true;
                    }
                } else {
                    if (scrollToTopVisible) {
                        fadeOut(scrollToTop);
                        scrollToTopVisible = false;
                    }
                }
            })
        })

        function fadeOut(el) {
            el.style.opacity = 1;
            (function fade() {
                if ((el.style.opacity -= .1) < 0) {
                    el.style.display = "none";
                } else {
                    requestAnimationFrame(fade);
                }
            })();
        };

        function fadeIn(el, display) {
            el.style.opacity = 0;
            el.style.display = display || "block";
            (function fade() {
                var val = parseFloat(el.style.opacity);
                if (!((val += .1) > 1)) {
                    el.style.opacity = val;
                    requestAnimationFrame(fade);
                }
            })();
        };
    </script>
@endpush
