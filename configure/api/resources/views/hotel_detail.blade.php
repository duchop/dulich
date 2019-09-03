@extends('layout.layout')

@section('meta')
    <meta name="description" content=""/>
    <meta name="format-detection" content="telephone=no">
@endsection

@section('title'){!! 'Northern Vietnam Travel' !!}@endSection

@section('body_class'){!! 'page_hotel_detail' !!}@endSection

@section('content')
    <!-- page-head -->

    <div class="page-head white-content">
        <div class="height80vh parallax-container" style="background-image: url({{'img/image-5.jpg'}});">
            <div class="page-head-wrap">
                <div class="display-r">
                    <div class="display-a">
                        <div class="container">
                            <div class="row justify-content-center animate" data-animation="fadeInUp" data-timeout="500">
                                <div class="col-md-12">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="home">Home</a></li>
                                            <li class="breadcrumb-item"><a href="#">Hotel</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">{{ $hotel['hotel_name'] }}</li>
                                        </ol>
                                    </nav>
                                    <h1 class="big-title mt-60">{{ $hotel['hotel_name'] }}</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- / page-head -->

    <!-- content -->

    <div class="tour-single-info">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="tour-single-sidebar-info-item">
                        <span class="ti-calendar"></span>
                        <p>{{ date('F d, Y', strtotime($hotel['update_datetime'])) }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="tour-single-sidebar-info-item">
                        <span class="ti-star"></span>
                        <p><strong style="font-size: 15px">{{ $hotel['hotel_type'] }}</strong></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="tour-single-sidebar-info-item">
                        <span class="ti-location-pin"></span>
                        <p>{{ $hotel->getCategoryHotel['hotel_category_name'] }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="tour-single-sidebar-info-item">
                        <span class="ti-home"></span>
                        <p>{{ $hotel['address'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content mt-40 mb-40">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="section-to-block">
                        <ul class="nav-menu section-to-block-menu">
                            <li class="to-section"><a href="#introduction">Introduction</a></li>
                            <li class="to-section"><a href="#typeofroom">Type of room</a></li>
                            <li class="to-section"><a href="#map">Map</a></li>
                            <li class="to-section"><a href="#photos">Photo</a></li>
                        </ul>

                        <div class="section section-padding" id="introduction">
                            <h2>Introduction</h2>
                            <hr>
                            <img src="{{ $hotel->imageRelation['0']->image['url'] }}" alt="Image">
                            <h4>{{ $hotel['hotel_name'] }}</h4>
                            <p style="font-size: 15px">{!! str_replace($hotel['hotel_name'], '<strong>'. $hotel['hotel_name'] . '</strong>', $hotel['introduction']) !!}</p>
                        </div>

                        <div class="section section-padding" id="typeofroom">
                            <h2>Type of room & Rate </h2>
                            <hr>
                            <!-- accordion-1 -->
                            <ul class="accordion-element accordion-2">
                                @foreach($hotel->getListRoom as $room)
                                    <li>
                                        <a class="toggle" href="javascript:void(0);" data-item="item-1">{{ $room['room_name'] }}</a>
                                        <p class="inner">
                                            <span>{{ $room['room_introduction'] }}</span><br>
                                            @foreach($room->getRoomIncludes as $room_include)
                                                @if(!empty($room_include['room_include_content']))
                                                    <span>{{ $room_include['room_include_name'] . ':' . $room_include['room_include_content']}}</span><br>
                                                @endif
                                            @endforeach
                                            <span>Price : </span>
                                            @if(empty($room['price']))
                                                <strong>Contact</strong>
                                            @else
                                                <strong>{{ $room['price'] . '$' }}</strong>
                                            @endif
                                        </p>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="section section-padding" id="map">
                            <h2>Map</h2>
                            <hr>
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1010290.7975268234!2d114.51105816599815!3d-8.455697404655568!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd22f7520fca7d3%3A0x2872b62cc456cd84!2sBali!5e0!3m2!1sen!2sby!4v1535545548975" width="600" height="450" style="border:0" allowfullscreen></iframe>
                        </div>

                        <div class="section section-padding" id="photos">
                            <h2>Photos</h2>
                            <hr>
                            <div class="row">
                                @foreach($hotel->imageRelation as $image_relation)
                                <div class="col-md-6">
                                    <div class="image-grid-item">
                                        <a href="{{ $image_relation->image['url'] }}" class="image-link">
                                            <img src="{{ $image_relation->image['url'] }}" alt="Image">
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="tour-single-sidebar-main">

                        <div class="tour-single-sidebar mb-30 tour-single-sidebar-padding">
                            <div class="tour-slingle-sidebar-form">

                                <form id="modal-book" class="form-block" method="POST">
                                    <div class="form-group col-md-12">
                                        <label class="col-form-label">Your Name</label>
                                        <input class="form-control" name="name" type="text" value="" placeholder="Your name" />
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="col-form-label">Your Email</label>
                                        <input class="form-control" type="email" name="email" value="" placeholder="Your email" />
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="col-form-label">Depart Date</label>
                                        <input type="text" class="form-control" name="depart" id="date3" placeholder="2017-11-05">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label class="col-form-label">Person(s)</label>
                                        <input class="form-control" type="text" name="person" value="" placeholder="Person(s)" />
                                    </div>
                                    <div class="form-group col-md-12">
                                        <button type="submit" name="search_btn" class="btn btn-1 mt-20 width100">Book tour</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                        <div class="tour-single-sidebar mb-30 tour-single-sidebar-padding">
                            <h4>24/7 Custumer Support</h4>
                            <ul class="support">
                                <li><span class="ti-time"></span>24-hour support service of customers</li>
                                <li><span class="ti-headphone-alt"></span>(+123) 234-567-891</li>
                                <li><span class="ti-email"></span><a href="mailto:info@voyagetime.com">info@voyagetime.com</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

@endsection

