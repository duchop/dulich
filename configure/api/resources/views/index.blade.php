@extends('layout.layout')

@section('meta')
    <meta name="description" content=""/>
    <meta name="format-detection" content="telephone=no">
@endsection

@section('title'){!! 'Northern Vietnam Travel' !!}@endSection

@section('body_class'){!! 'page_index' !!}@endSection

@section('content')

    <div class="search-relative">

        <!-- slider -->
        <div class="owl-carousel" id="fullscreen-slider">
            <div class="item height100vh" style="background-image: url({{ 'img/slider-1.jpg' }});">
                <div class="page-head-wrap">
                    <div class="page-head-inner">
                        <div class="page-head-caption container text-left">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-11">
                                        <p class="animate" data-animation="fadeInLeft" data-timeout="1200">Tour Du Lịch
                                            Hạ Long</p>
                                        <h2 class="big-title mb-10 animate" data-animation="fadeInRight"
                                            data-timeout="1200">Hạ Long</h2>
                                        <p class="animate mb-20" data-animation="fadeInLeft" data-timeout="1200">
                                            Vịnh Hạ Long là địa điểm du lịch nổi tiếng của Việt Nam, nằm ở phần bờ Tây
                                            vịnh Bắc Bộ tại khu vực biển Đông Bắc Việt Nam. Với hàng nghìn hòn đảo kỳ
                                            vĩ, thành quả kì diệu của tạo hóa, vịnh Hạ Long được UNESCO nhiều lần công
                                            nhận là di sản thiên nhiên của thế giới.</p>
                                        <a href="#" class="btn btn-4 animate" data-animation="fadeInUp"
                                           data-timeout="900">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item height100vh" style="background-image: url({{ 'img/slider-2.jpg' }});">
                <div class="page-head-wrap">
                    <div class="page-head-inner">
                        <div class="page-head-caption container text-right">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-11">
                                        <p class="animate" data-animation="bounceIn" data-timeout="900">Tour Du Lịch
                                            Sapa</p>
                                        <h2 class="big-title mb-10 animate" data-animation="fadeInDown"
                                            data-timeout="1200">Sapa</h2>
                                        <p class="animate mb-20" data-animation="fadeInLeft" data-timeout="1200">
                                            Du lịch Sapa - thành phố của mây. Trải nghiệm tour đi du lịch Sapa du khách
                                            sẽ được hòa mình vào phong cảnh thiên nhiên của Sapa - cảnh đẹp kết hợp với
                                            sức sáng tạo của con người cùng với địa hình của núi đồi, màu xanh của
                                            rừng.</p>
                                        <a href="#" class="btn btn-4 animate" data-animation="fadeInUp"
                                           data-timeout="900">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="item height100vh" style="background-image: url({{ 'img/slider-3.jpg' }});">
                <div class="page-head-wrap">
                    <div class="page-head-inner">
                        <div class="page-head-caption container text-left">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <div class="col-11">
                                        <p class="animate clearfix" data-animation="fadeInUp" data-timeout="1000">
                                            Du Lịch Mộc Châu</p>
                                        <h2 class="big-title mb-10 animate" data-animation="fadeInUp"
                                            data-timeout="1200">Mộc Châu</h2>
                                        <p class="animate mb-20" data-animation="fadeInLeft" data-timeout="1200">
                                            Mộc Châu được ví như Đà Lạt của Tây Bắc, đi du lịch Mộc Châu vào mùa hè hay
                                            mùa đông, ngày sương mù trắng núi hay ngày nắng trải vàng mơ trên rừng đều
                                            khiến du khách ngẩn ngơ về vẻ đẹp thiên nhiên của Mộc Châu.</p>
                                        <a href="#" class="btn btn-4 animate" data-animation="fadeInUp"
                                           data-timeout="1200">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- / slider -->

        <!-- order form -->

        <div class="order-form animate" data-animation="fadeInUp" data-timeout="1000">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-11">
                        <!-- filter horizontal form -->
                        <form class="filter-form filter-form-slider mobile-none" method="POST" action="searchTour">
                            <div class="form-row">

                                <div class="form-group col-md-2">
                                    <label class="col-form-label">Category</label>
                                    <select class="form-control custom-select">
                                        <option>UAE</option>
                                        <option>Germany</option>
                                        <option>USA</option>
                                        <option>Spain</option>
                                        <option>France</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="col-form-label">Depart Date</label>
                                    <input type="text" class="form-control" id="date3" placeholder="2017-11-05">
                                </div>
                                <div class="form-group col-md-2">
                                    <label class="col-form-label">Guest</label>
                                    <select class="form-control custom-select">
                                        <option>1</option>
                                        <option selected>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-2">
                                    <label class="col-form-label">Duration (nights)</label>
                                    <input id="date-depart-input" type="text" value="" data-slider-min="1"
                                           data-slider-max="60" data-slider-step="1" data-slider-value="[7,10]"/>
                                    <span id="date-depart-1" class="slider-date">7 days</span> <span
                                            class="float-right slider-date" id="date-depart-2">10 days</span>
                                </div>

                                <div class="form-group col-md-2">
                                    <label class="col-form-label">Your budget</label>
                                    <input id="budget-input" type="text" value="" data-slider-min="50"
                                           data-slider-max="6000" data-slider-step="50" data-slider-value="[500,1600]"/>
                                    <span id="budget-1" class="slider-date">$ 500</span> <span
                                            class="float-right slider-date" id="budget-2">$ 6000</span>
                                </div>

                                <div class="form-group col-md-2">
                                    <button type="submit" name="search_btn" class="btn btn-1 mt-26 width100">Find
                                    </button>
                                </div>

                            </div>
                        </form>
                        <!-- / filter horizontal form -->
                    </div>
                </div>
            </div>
        </div>

        <!-- / order form -->

    </div> <!-- / search-relative -->


    <!-- BLOCK popular -->
    @if(! empty($ary_daily_tour))
        <div class="main-block">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center block width100 mb-50 block-title">
                            <h2>Tour</h2>
                            <div class="separator"><span>Сhoose the tour yourself and get a 5% discount!</span></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @foreach($ary_daily_tour as $daily_tour)
                        <div class="col-md-6 col-lg-4">
                            <div class="tour-item">
                                <a href="tour_detail?tour_id={{ $daily_tour['tour_id'] }}">
                                    <div class="img-wrap">
                                        <img src="{{ $daily_tour->imageRelation['0']->image['url'] }}" alt="">
                                        @if(isset($daily_tour['price']) and $daily_tour['price'] != 0)
                                            <p class="price">{{ $daily_tour['price'] }}$</p>
                                        @else
                                            <p class="price">Contact</p>
                                        @endif
                                    </div>
                                </a>
                                <div class="caption">
                                    <a href="tour_detail?tour_id={{ $daily_tour['tour_id'] }}"><p
                                                class="title">{{ $daily_tour['tour_name'] }}</p></a>
                                    <p class="date"><span
                                                class="ti-calendar"></span>{{ date('F d, Y', strtotime($daily_tour['update_datetime'])) }}
                                    </p>
                                    <p class="time"><span class="ti-time"></span>{{ $daily_tour['tour_time'] }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif


    <!-- BLOCK / popular -->

    <div class="ctoa text-center parallax-container color-white"
         style="background-image: url({{ 'img/image-2.jpg' }})">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="mb-30">Get 15% Off on your first travel</h2>
                <p class="mb-40">Necessitatibus enim corrupti ullam voluptatum provident deserunt natus
                    reprehenderit,
                    inventore, tempore aut neque cupiditate, aspernatur! Quibusdam aliquid dolor a culpa, officiis
                    quisquam.</p>
                <a class="btn btn-1" href="contact">Contact Us</a>
            </div>
        </div>
    </div>

    <!-- BLOCK popular -->
    @if(! empty($ary_ha_long_tour))
        <div class="main-block">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center block width100 mb-50 block-title">
                            <h2>Ha Long On Cruise</h2>
                            <div class="separator"><span>Сhoose the the most popular destinations in Ha Long</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach($ary_ha_long_tour as $ha_long_tour)
                        <div class="col-md-6 col-lg-4">
                            <div class="category-item effect-1">
                                <img src="{{ $ha_long_tour->imageRelation['0']->image['url'] }}" alt="img12">
                                <div class="caption">
                                    <div>
                                        <p class="title">{{ $ha_long_tour['tour_name'] }}</p>
                                        <p class="description">Plan your adventures in Ha Long with our tours</p>
                                    </div>
                                    <a href="tour_detail?tour_id={{ $ha_long_tour['tour_id'] }}">View more</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    <!-- BLOCK / popular -->

    <!-- BLOCK advantage -->

    <div class="main-block bg-gray">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center block width100 mb-10 block-title">
                        <h2>TRANSPORTATION</h2>
                    </div>
                </div>
            </div>
            <div class="row">

                <!-- Infography item style 1 -->
                <div class="col-md-4">
                    <div class="infography infography-1">
                        <div class="infography-icon">
                            <i class="fas fa-plane"></i>
                        </div>
                        <div class="infography-text">
                            <h4>Airport Transportation</h4>
                            <p>Northern Vietnam Travel associates with partners that are famous in tourism transport
                                company. We always try our best to provide the customers with the most modern
                                transportations. We listen to the customers, perfect ourselves and try hard to
                                provide
                                the best and safest services.
                            </p>
                        </div>
                    </div>
                </div>
                <!-- / Infography item style 1 -->

                <!-- Infography item style 1 -->
                <div class="col-md-4">
                    <div class="infography infography-1">
                        <div class="infography-icon">
                            <a href="transportation_list?transportation_category_id=2"><i
                                        class="fas fa-bus"></i></a>
                        </div>
                        <div class="infography-text">
                            <a href="transportation_list?transportation_category_id=2"><h4>Bus Hanoi Sapa</h4></a>
                            <p>Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur,
                                adipisci
                                velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore
                                magnam.</p>
                        </div>
                    </div>
                </div>
                <!-- / Infography item style 1 -->

                <!-- Infography item style 1 -->
                <div class="col-md-4">
                    <div class="infography infography-1">
                        <div class="infography-icon">
                            <a href="transportation_list?transportation_category_id=3"><i class="fas fa-train"></i></a>
                        </div>
                        <div class="infography-text">
                            <a href="transportation_list?transportation_category_id=3"><h4>Train HaNoi – Lao
                                    Cai</h4>
                            </a>
                            <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                                consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
                        </div>
                    </div>
                </div>
                <!-- / Infography item style 1 -->


            </div>
        </div>
    </div>

    <!-- BLOCK / advantage -->

    <!-- BLOCK news -->
    @if(! empty($ary_hotel))
        <div class="main-block">
            <div class="container">

                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center block width100 mb-50 block-title">
                            <h2>Hotel</h2>
                            <div class="separator"><span>Check out our latest list of hotels</span></div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @foreach($ary_hotel as $hotel)
                        <div class="col-md-6 col-lg-4">
                            <div class="blog-item effect-1">
                                <a class="block" href="hotel_detail?hotel_id={{ $hotel['hotel_id'] }}">
                                    <img src="{{ $hotel->imageRelation['0']->image['url'] }}" alt="img12">
                                </a>
                                <div class="caption clearfix">
                                    <a href="hotel_detail?hotel_id={{ $hotel['hotel_id'] }}">
                                        <p class="title">{{ $hotel['hotel_name'] }}</p>
                                    </a>
                                    <p class="date"><span
                                                class="ti-calendar"></span>{{ date('F d, Y', strtotime($hotel['update_datetime'])) }}
                                    </p>
                                    <p class="author"><span class="ti-user"></span>By TravelUser</p>
                                    <ul class="tags">
                                        <li><a href="#">View Detail</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- BLOCK / news -->
@endsection
