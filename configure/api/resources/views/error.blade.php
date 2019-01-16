@extends('layout.layout')

@section('meta')
    <meta name="robots" content="noindex,nofollow,noarchive" />
@endsection

@section('title'){!! 'ぐるなび Web Service - 新規アカウント発行/設定変更 エラー' !!}@endSection

@section('body_class'){!! 'page__subscribe_comp' !!}@endSection

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
    </div>
@endsection
