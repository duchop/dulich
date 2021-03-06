
@extends('layout.layout')

@section('meta')
    <meta name="description" content=""/>
    <meta name="format-detection" content="telephone=no">
@endsection

@section('title'){!! 'Northern Vietnam Travel' !!}@endSection

@section('body_class'){!! 'page__list_tours' !!}@endSection

@section('content')
    <div class="page-head white-content">
        <div class="height50vh parallax-container" style="background-image: url({{ 'img/image-2.jpg' }});">
            <div class="page-head-wrap">
                <div class="display-r">
                    <div class="display-a">
                        <div class="container">
                            <div class="row justify-content-center animate" data-animation="fadeInUp" data-timeout="500">
                                <div class="col-md-12">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="home">Home</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">
                                                @if(isset($list_tours) and count($list_tours) > 0)
                                                    {{ $list_tours['0']->getCategoryTour['category_name'] }}
                                                @endif
                                            </li>
                                        </ol>
                                    </nav>
                                    <h1 class="big-title mt-60">
                                        @if(isset($list_tours) and count($list_tours) > 0)
                                            {{ $list_tours['0']->getCategoryTour['category_name'] }}
                                        @endif
                                    </h1>
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

    <div class="content mb-40 container">
        <div class="row">

            <div class="col-md-12 mt-40 mb-40">
                <!-- filter horizontal form -->
                <form class="filter-form" method="POST">
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label class="col-form-label">Depart city</label>
                            <select class="form-control custom-select">
                                <option>Paris</option>
                                <option>Berlin</option>
                                <option>New York</option>
                                <option>San Francisco</option>
                                <option>Minsk</option>
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label class="col-form-label">Destination</label>
                            <select class="form-control custom-select">
                                <option>UAE</option>
                                <option>Germany</option>
                                <option>USA</option>
                                <option>Spain</option>
                                <option>France</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-form-label">Resort</label>
                            <select class="form-control custom-select">
                                <option>Dubai</option>
                                <option>Sharjah</option>
                                <option>Abu Dhabi</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-form-label">Category</label>
                            <select class="form-control custom-select">
                                <option>Extreme</option>
                                <option>Fashion</option>
                                <option>Cultural</option>
                                <option>Honeymoon</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-form-label">Depart Date</label>
                            <input type="text" class="form-control" id="date3" placeholder="2017-11-05">
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-form-label">Adults</label>
                            <select class="form-control custom-select">
                                <option>1</option>
                                <option selected>2</option>
                                <option>3</option>
                                <option>4</option>
                            </select>
                        </div>

                    </div>
                    <div class="form-row">


                        <div class="form-group col-md-5">
                            <label class="col-form-label">Duaration (nights)</label>
                            <input id="date-depart-input" type="text" value="" data-slider-min="1" data-slider-max="60" data-slider-step="1" data-slider-value="[7,10]"/>
                            <span id="date-depart-1" class="slider-date">7 days</span> <span class="float-right slider-date" id="date-depart-2">10 days</span>
                        </div>

                        <div class="form-group col-md-5">
                            <label class="col-form-label">Your budget</label>
                            <input id="budget-input" type="text" value="" data-slider-min="50" data-slider-max="6000" data-slider-step="50" data-slider-value="[500,1600]"/>
                            <span id="budget-1" class="slider-date">$ 500</span> <span class="float-right slider-date" id="budget-2">$ 6000</span>
                        </div>

                        <div class="form-group col-md-2">
                            <button type="submit" name="search_btn" class="btn btn-1 mt-26 width100">Find</button>
                        </div>


                    </div>
                </form>
                <!-- / filter horizontal form -->
            </div>

            @foreach($list_tours as $tour)
                <div class="col-md-6 col-lg-3">
                    <div class="tour-item">
                        <a href="tour_detail?tour_id={{ $tour['tour_id'] }}">
                            <div class="img-wrap">
                                <img src="{{ $tour->imageRelation['0']->image['url'] }}" alt="">
                                @if(isset($tour['price']) and $tour['price'] != 0)
                                    <p class="price">{{ $tour['price'] }}$</p>
                                @else
                                    <p class="price">Contact</p>
                                @endif
                            </div>
                        </a>
                        <div class="caption">
                            <a href="tour_detail?tour_id={{ $tour['tour_id'] }}"><p class="title">{{ $tour['tour_name'] }}</p></a>
                            <p class="date"><span class="ti-calendar"></span>{{ date('F d, Y', strtotime($tour['update_datetime'])) }}</p>
                            <p class="time"><span class="ti-time"></span>{{ $tour['tour_time'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="col-md-12">
                <div class="pagination">
                    {!! $list_tours->appends(['category_tour_id' => $list_tours[0]['category_tour_id']])->links('pagination') !!}
                </div>
            </div>
        </div>
    </div>
@endsection

