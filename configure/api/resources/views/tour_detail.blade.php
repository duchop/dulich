@extends('layout.layout')

@section('meta')
    <meta name="description" content=""/>
    <meta name="format-detection" content="telephone=no">
@endsection

@section('title'){!! 'Du lịch việt nam' !!}@endSection

@section('body_class'){!! 'page_tour_detail' !!}@endSection

@section('content')

    <div class="page-head white-content">
        <div class="height50vh parallax-container" style="background-image: url({{ 'img/image-5.jpg' }});">
            <div class="page-head-wrap">
                <div class="display-r">
                    <div class="display-a">
                        <div class="container">
                            <div class="row justify-content-center animate" data-animation="fadeInUp" data-timeout="500">
                                <div class="col-md-12">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="home">Home</a></li>
                                            <li class="breadcrumb-item"><a href="#">{{ $tour->getCategoryTour['category_name'] }}</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">{{ $tour['tour_name'] }}</li>
                                        </ol>
                                    </nav>
                                    <h1 class="big-title mt-60">Tour Detail</h1>
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

    <div class="content mt-60 mb-40 container">

        <div class="row">
            <div class="col-md-8">
                <div class="blog-single-info">
                    <div class="blog-single-info-img">
                        <img src="{{ $tour->imageRelation['0']->image['url'] }}" alt="Tour Image">
                        <span class="blog-item-date">{{ date('F d, Y', strtotime($tour['update_datetime'])) }}</span>
                    </div>
                    <div class="caption">
                        <span class="blog-item-author">By <a href="#">VoyageTime</a></span>
                        <div class="blog-item-comment">
                            <span class="ti-comment-alt"></span><a href="#">3</a>
                        </div>

                    </div>
                </div>
                @if(! empty($tour['introductions']))
                    <h4>Introduction</h4>
                    <p>{{ $tour['introductions'] }}</p>
                @endif
                @if(isset($tour->getItinerary) and count($tour->getItinerary) > 0)
                    <hr>
                    <h4>Itinerary</h4>
                    <ul class="list-2">
                        @foreach($tour->getItinerary as $itinerary)
                            @if(isset($itinerary['time_start']) and isset($itinerary['time_end']))
                                <li>
                                    <strong>{{ $itinerary['time_start'] . ' - ' . $itinerary['time_end'] }}</strong>
                                    {!! $itinerary['itinerary_content'] !!}
                                </li>
                            @elseif(isset($itinerary['time_start']))
                                <li>
                                    <strong>{{ $itinerary['time_start'] }}</strong>
                                    {!! $itinerary['itinerary_content'] !!}
                                </li>
                            @else
                                <li>
                                    {!! $itinerary['itinerary_content'] !!}
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif

                @if(isset($tour->getInclude) and count($tour->getInclude))
                    <hr>
                    <h4>Included</h4>
                    <ul class="list-3">
                        @foreach($tour->getInclude as $include)
                            @if(isset($include['include_name']) and $include['include_type'] == 0)
                                <li>{!! $include['include_name'] !!} </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
                @if(isset($tour->getInclude) and count($tour->getInclude))
                    <hr>
                    <h4>Excluded</h4>
                    <ul class="list-3">
                        @foreach($tour->getInclude as $include)
                            @if(isset($include['include_name']) and $include['include_type'] == 1)
                                <li>{!! $include['include_name'] !!} </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
                @if(isset($tour['price']) and $tour['price'] > 0)
                    <hr>
                    <h4>Price</h4>
                    <strong style="color: #ff6224">{{ $tour['price'] }} $</strong>
                @else
                    <hr>
                    <h4>Price</h4>
                    <strong style="color: #ff6224">Contact</strong>
                @endif
                <div class="blog-comments mt-50">
                    <div class="fb-comments" data-href="http://dulichvn.com?tour_id={{ $tour->tour_id }}" data-numposts="15"></div>
                </div> <!-- / .blog-comments -->
            </div>

            <div class="col-md-4">
                <div class="sidebar">

                    <div class="sidebar-item mb-30">
                        <form method="POST">
                            <div class="input-group">
                                <input type="text" name="search" id="search" placeholder="Search..." class="form-control">
                                <span class="input-group-btn">
                      <button type="submit" class="btn btn-3"><span class="ti-search"></span></button>
                   </span>
                            </div>
                        </form>
                    </div>
                    @if(isset($list_tour) and count($list_tour) > 0)
                        <div class="sidebar-item mb-40">
                            <p class="sidebar-title">Newest</p>
                            @foreach($list_tour as $tour_other)
                                <div class="sidebar-post-inner mb-10">

                                    <div class="sidebar-post-content">
                                        <h6><a href="tour_detail?tour_id={{ $tour_other['tour_id'] }}">{{ $tour_other['tour_name'] }}</a></h6>
                                        <span>{{ date('F d, Y', strtotime($tour_other['update_datetime'])) }}</span>
                                    </div>
                                    <div class="sidebar-post-img">
                                        <a href="tour_detail?tour_id={{ $tour_other['tour_id'] }}">
                                            <img src="{{ $tour_other->imageRelation['0']->image['url'] }}">
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div class="sidebar-item mb-40">
                        @if(isset($list_category_tours) and count($list_category_tours) > 0)
                            <p class="sidebar-title">Categories</p>
                            <ul class="categories">
                                @foreach($list_category_tours as $category_tour)
                                    <li>
                                        <a href="tour_list?category_tour_id={{$category_tour['category_tour_id']}}">{{ $category_tour['category_name'] }}</a>
                                        <span>({{ $category_tour['count_tour'] }})</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="sidebar-item mb-40">
                        <p class="sidebar-title">Tags</p>
                        <ul class="tags mt-20">
                            <li><a href="#">Photos</a></li>
                            <li><a href="#">Trip</a></li>
                            <li><a href="#">Cultural tourism‎</a></li>
                            <li><a href="#">Fashion tourism</a></li>
                            <li><a href="#">Extreme tourism</a></li>
                            <li><a href="#">Honeymoon</a></li>
                        </ul>
                    </div>

                    <div class="sidebar-item mb-40">
                        <a href="#"><img src="img/distination-6.jpeg" alt=""></a>
                    </div>

                    <div class="sidebar-item mb-40 icons-big">
                        <p class="sidebar-title">Follow Us</p>
                        <ul class="social">
                            <li><a href="#"><span class="ti-facebook"></span></a></li>
                            <li><a href="#"><span class="ti-instagram"></span></a></li>
                            <li><a href="#"><span class="ti-twitter"></span></a></li>
                            <li><a href="#"><span class="ti-youtube"></span></a></li>
                            <li><a href="#"><span class="ti-pinterest"></span></a></li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v3.2';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
@endsection

