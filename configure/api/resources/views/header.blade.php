
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
                <li><span class="ti-map-alt"></span>{{ $user_info->address_office }}</li>
                <li><span class="ti-mobile"></span> <a href="tel:1800-2345-5678">{{ $user_info->number_phone }}</a></li>
                <li><span class="ti-email"></span> <a href="mailto:info@voyagetime.com">{{ $user_info->email }}</a></li>
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
                        <a href="tel:0969115038">{{ $user_info->number_phone }}</a>
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
                                <li><a class="active" href="home">Home</a>
                                    <ul class="nav-dropdown">
                                        <li><a href="#">Daily tour</a></li>
                                        <li><a href="tour_list?category_tour_id=5">Halong on cruise</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Daily tour</a>
                                    <ul class="nav-dropdown">
                                        @foreach($ary_category_daily_tour as $category_daily_tour)
                                        <li><a href="tour_list?category_tour_id={{$category_daily_tour['category_tour_id']}}">{{ $category_daily_tour['category_name'] }}</a></li>
                                        @endforeach
                                    </ul>
                                <li><a href="tour_list?category_tour_id=5">Halong on cruise</a>
                                    <ul class="nav-dropdown">
                                        @foreach($ary_ha_long_tour as $ha_long_tour)
                                        <li><a href="tour_detail?tour_id={{ $ha_long_tour['tour_id'] }}">{{ $ha_long_tour['tour_name'] }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li><a href="#">Transportation</a>
                                    <ul class="nav-dropdown">
                                        <a href="transportation_list?transportation_category_id=2"><i class="fas fa-bus"></i></a>
                                        <li><a href="#">Airport Transportation</a></li>
                                        <li><a href="transportation_list?transportation_category_id=2">Bus Hanoi Sapa</a></li>
                                        <li><a href="transportation_list?transportation_category_id=3">Train HaNoi – Lao Cai</a></li>
                                    </ul>
                                </li>
                                <li><a href="#">Hotel</a>
                                    <ul class="nav-dropdown">
                                        @foreach($ary_category_hotel as $category_hotel)
                                        <li><a href="hotel_list?category_hotel_id={{ $category_hotel['hotel_category_id'] }}">{{ $category_hotel['hotel_category_name'] }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li><a href="contact">Contact</a></li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>