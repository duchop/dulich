@extends('layout.layout')

@section('meta')
    <meta name="description" content=""/>
    <meta name="format-detection" content="telephone=no">
@endsection

@section('title'){!! 'Du lịch việt nam' !!}@endSection

@section('body_class'){!! 'page_hotel_detail' !!}@endSection

@section('content')
    <!-- page-head -->

    <div class="page-head white-content">
        <div class="height80vh parallax-container" style="background-image: url(img/image-5.jpg);">
            <div class="page-head-wrap">
                <div class="display-r">
                    <div class="display-a">
                        <div class="container">
                            <div class="row justify-content-center animate" data-animation="fadeInUp" data-timeout="500">
                                <div class="col-md-12">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                            <li class="breadcrumb-item"><a href="#">Tours</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Tour Single 2</li>
                                        </ol>
                                    </nav>
                                    <h1 class="big-title mt-60">Tour Single 2</h1>
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
                        <p>{{ date('F d, Y', strtotime($tour['update_datetime'])) }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="tour-single-sidebar-info-item">
                        <span class="ti-timer"></span>
                        <p>{{ $tour['tour_time'] }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="tour-single-sidebar-info-item">
                        <span class="ti-location-pin"></span>
                        <p>{{ $tour['from'] }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="tour-single-sidebar-info-item">
                        <span class="ti-credit-card"></span>
                        @if(isset($tour['price']) and isset($tour['price_promotion']))
                            <p class="price"><i>{{ $tour['price'] }} $</i>{{ $tour['price_promotion'] }} $</p>
                        @elseif(isset($tour['price']) and $tour['price'] != 0)
                            <p class="price">{{ $tour['price'] }} $</p>
                        @else
                            <p class="price">Contact</p>
                        @endif
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
                            <li class="to-section"><a href="#highlights">Highlights</a></li>
                            <li class="to-section"><a href="#itinerary">Itinerary</a></li>
                            <li class="to-section"><a href="#map">Map</a></li>
                            <li class="to-section"><a href="#gallery">Gallery</a></li>
                        </ul>

                        <div class="section section-padding" id="highlights">
                            <h2>Highlights</h2>
                            <hr>
                            <img src="img/distination-1.jpeg" alt="Image">
                            <h3>The Italian Dream</h3>
                            <p>Start and end in Rome! With the in-depth cultural tour The Italian Dream, you have a 8 day tour package taking you through Rome, Italy and 7 other destinations in Italy. The Italian Dream includes accommodation in a hotel as well as an expert guide, meals, transport and more.</p>
                            <ul class="list-1">
                                <li>Follow the steps of amazing Michelangelo and Giotto</li>
                                <li>Lunch in Tuscan restaurant and indulge in Tuscan wine</li>
                                <li>Discover Bologna’s impressive historical city center</li>
                                <li>Visit the stunning Basilica of Saint Anthony of Padua</li>
                                <li>Tour St. Peters’ Basilica and the Sistine Chapel</li>
                            </ul>
                        </div>

                        <div class="section section-padding" id="itinerary">
                            <h2>Itinerary</h2>
                            <hr>
                            <img src="img/distination-2.jpeg" alt="Image">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus assumenda excepturi tempora itaque illo totam, magnam iste distinctio accusamus eveniet est cum minima autem sequi deserunt consectetur! Et, reprehenderit, quos.</p>
                            <!-- accordion-1 -->
                            <ul class="accordion-element accordion-2">
                                <li>
                                    <a class="toggle" href="javascript:void(0);" data-item="item-1">Introduction</a>
                                    <p class="inner">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tempus placerat fringilla. Duis a elit et dolor laoreet volutpat. Aliquam ultrices mauris id mattis imperdiet. Aenean cursus ultrices justo et varius. Suspendisse aliquam orci id dui dapibus
                                        blandit. In hac habitasse platea dictumst. Sed risus velit, pellentesque eu enim ac, ultricies pretium felis.
                                    </p>
                                </li>

                                <li>
                                    <a class="toggle" href="javascript:void(0);" data-item="item-2">Day 1: Rome</a>
                                    <p class="inner">
                                        As long as the inner element has inner as one of its classes then it will be toggled.
                                    </p>
                                </li>

                                <li>
                                    <a class="toggle" href="javascript:void(0);" data-item="item-3">Day 2: Rome - Assisi - Siena - Florence</a>
                                    <p class="inner">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tempus placerat fringilla. Duis a elit et dolor laoreet volutpat. Aliquam ultrices mauris id mattis imperdiet. Aenean cursus ultrices justo et varius. Suspendisse aliquam orci id dui dapibus
                                        blandit. In hac habitasse platea dictumst. Sed risus velit, pellentesque eu enim ac, ultricies pretium felis.
                                    </p>
                                </li>
                                <li>
                                    <a class="toggle" href="javascript:void(0);" data-item="item-3">Day 3: Venice - Montepulciano (Tuscany wine region) - Rome</a>
                                    <p class="inner">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tempus placerat fringilla. Duis a elit et dolor laoreet volutpat. Aliquam ultrices mauris id mattis imperdiet. Aenean cursus ultrices justo et varius. Suspendisse aliquam orci id dui dapibus
                                        blandit. In hac habitasse platea dictumst. Sed risus velit, pellentesque eu enim ac, ultricies pretium felis.
                                    </p>
                                </li>
                                <li>
                                    <a class="toggle" href="javascript:void(0);" data-item="item-3">Day 4: Florence - Bologna - Padova - Venice</a>
                                    <p class="inner">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tempus placerat fringilla. Duis a elit et dolor laoreet volutpat. Aliquam ultrices mauris id mattis imperdiet. Aenean cursus ultrices justo et varius. Suspendisse aliquam orci id dui dapibus
                                    </p>
                                </li>
                                <li>
                                    <a class="toggle" href="javascript:void(0);" data-item="item-3">Day 5: Finish Rome</a>
                                    <p class="inner">
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas tempus placerat fringilla. Duis a elit et dolor laoreet volutpat. Aliquam ultrices mauris id mattis imperdiet. Aenean cursus ultrices justo et varius. Suspendisse aliquam orci id dui dapibus
                                        blandit. In hac habitasse platea dictumst. Sed risus velit, pellentesque eu enim ac, ultricies pretium felis.
                                    </p>
                                </li>
                            </ul>

                            <!-- /.accordion-1 -->
                        </div>

                        <div class="section section-padding" id="map">
                            <h2>Map</h2>
                            <hr>
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1010290.7975268234!2d114.51105816599815!3d-8.455697404655568!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd22f7520fca7d3%3A0x2872b62cc456cd84!2sBali!5e0!3m2!1sen!2sby!4v1535545548975" width="600" height="450" style="border:0" allowfullscreen></iframe>
                        </div>

                        <div class="section section-padding" id="gallery">
                            <h2>Gallery</h2>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="image-grid-item">
                                        <a href="img/image-7.jpg" class="image-link">
                                            <img src="img/image-7.jpg" alt="Image">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="image-grid-item">
                                        <a href="img/image-16.jpg" class="image-link">
                                            <img src="img/image-16.jpg" alt="Image">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="image-grid-item">
                                        <a href="img/image-17.jpg" class="image-link">
                                            <img src="img/image-17.jpg" alt="Image">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="image-grid-item">
                                        <a href="img/image-18.jpg" class="image-link">
                                            <img src="img/image-18.jpg" alt="Image">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="image-grid-item">
                                        <a href="img/image-15.jpg" class="image-link">
                                            <img src="img/image-15.jpg" alt="Image">
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="image-grid-item">
                                        <a href="img/image-14.jpg" class="image-link">
                                            <img src="img/image-14.jpg" alt="Image">
                                        </a>
                                    </div>
                                </div>
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

