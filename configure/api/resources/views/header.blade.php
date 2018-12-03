
<!-- pageloader -->
<div id="loader">
    <div class="loader">
        <div class="sk-child sk-dot1"></div>
        <div class="sk-child sk-dot2"></div>
    </div>
</div>
<!-- / #pageloader -->

<nav id="contact-menu" class="navigation navigation-hidden navigation-portrait">
    <div class="nav-menus-wrapper">
        <div class="header-info-item clearfix">
            <p class="header-info-title">Contact Us</p>
            <ul>
                <li><span class="ti-map-alt"></span> 610 Broadway New York, NY 10012</li>
                <li><span class="ti-headphone-alt"></span> <a href="tel:1800-2345-5677">1800-2345-5677</a></li>
                <li><span class="ti-mobile"></span> <a href="tel:1800-2345-5678">1800-2345-5678</a></li>
                <li><span class="ti-email"></span> <a href="mailto:info@voyagetime.com">info@voyagetime.com</a></li>
            </ul>
        </div>
        <div class="header-info-item clearfix">
            <p class="header-info-title">Work Time</p>
            <ul>
                <li><span class="ti-alarm-clock"></span> Working Days  9AM - 9PM</li>
                <li><span class="ti-alarm-clock"></span> Saturday  10AM - 8PM</li>
                <li><span class="ti-alarm-clock"></span> Sunday  Closed</li>
            </ul>
        </div>
        <div class="header-info-item clearfix">
            <p class="header-info-title">Follow Us</p>
            <ul class="header-social">
                <li><a href="#"><span class="ti-facebook"></span></a></li>
                <li><a href="#"><span class="ti-instagram"></span></a></li>
                <li><a href="#"><span class="ti-twitter"></span></a></li>
                <li><a href="#"><span class="ti-youtube"></span></a></li>
                <li><a href="#"><span class="ti-pinterest"></span></a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- / header -->
<header>
    <div class="main-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-5 col-4">
                    <div class="main-header-logo">
                        <a href="home"><img width="130" src="img/logo-white.png" alt="Site logo"></a>
                    </div>
                </div>
                <div class="col-md-7 col-8">
                    <div class="clearfix float-right contact-head">
                        <a href="tel:1800-2345-5677">1800-2345-5677</a>
                        <button class="btn-show navbar-toggler float-right btn-contact"><span class="ti-menu"></span></button>
                    </div>
                    <nav id="navigation" class="navigation mt-10">
                        <div class="nav-toggle">Menu</div>
                        <div class="nav-search">
                            <div class="nav-search-button">
                                <span class="ti-search"></span>
                            </div>
                            <form>
                                <div class="nav-search-inner">
                                    <input type="search" name="search" placeholder="Type and hit ENTER"/>
                                </div>
                            </form>
                        </div>

                        <div class="nav-menus-wrapper">

                            <ul class="nav-menu align-to-right">
                                <li><a class="active" href="#">Home</a>
                                    <ul class="nav-dropdown">
                                        <li><a href="index.html">Daily tour</a></li>
                                        <li><a href="home-style-2.html">Halong on cruise</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Daily tour</a>
                                    <ul class="nav-dropdown">
                                        @foreach($ary_category_daily_tour as $category_daily_tour)
                                        <li><a href="tour-list-1.html">{{ $category_daily_tour['category_name'] }}</a></li>
                                        @endforeach
                                    </ul>
                                <li><a href="#">Halong on cruise</a>
                                    <ul class="nav-dropdown">
                                        @foreach($ary_ha_long_tour as $ha_long_tour)
                                        <li><a href="tour_detail?tour_id={{ $ha_long_tour['tour_id'] }}">{{ $ha_long_tour['tour_name'] }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li><a href="#">Transportation</a>
                                    <ul class="nav-dropdown">
                                        <li><a href="#">Airport Transportation</a></li>
                                        <li><a href="#">Bus </a></li>
                                        <li><a href="#">Train</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Hotel</a>
                                    <ul class="nav-dropdown">
                                        @foreach($ary_category_hotel as $category_hotel)
                                        <li><a href="blog-page-1.html">{{ $category_hotel['hotel_category_name'] }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li><a href="#">Elements</a>
                                    <div class="megamenu-panel">
                                        <div class="megamenu-lists">
                                            <ul class="megamenu-list list-col-3">
                                                <li><a href="elements-heading.html">Headings</a></li>
                                                <li><a href="elements-blockquotes.html">Blockquotes</a></li>
                                                <li><a href="elements-dropcaps.html">Dropcaps</a></li>
                                                <li><a href="elements-seporators.html">Seporators</a></li>

                                            </ul>
                                            <ul class="megamenu-list list-col-3">
                                                <li><a href="elements-icons.html">Icons</a></li>
                                                <li><a href="elements-buttons.html">Buttons</a></li>
                                                <li><a href="elements-infography.html">Infography</a></li>
                                                <li><a href="elements-call-to-action.html">Call-to-action</a></li>
                                            </ul>
                                            <ul class="megamenu-list list-col-3">
                                                <li><a href="elements-testimonials.html">Testimonials</a></li>
                                                <li><a href="elements-tabs&accordions.html">Tabs & Accordions</a></li>
                                                <li><a href="elements-team.html">Team</a></li>
                                                <li><a href="elements-section-title.html">Section Title</a></li>
                                            </ul>

                                        </div>
                                    </div>
                                </li>
                                <li><a href="contact.html">Contact</a></li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>