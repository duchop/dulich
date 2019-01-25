@extends('layout.layout')

@section('meta')
    <meta name="robots" content="noindex,nofollow,noarchive"/>
@endsection

@section('title'){!! 'du lich viet nam' !!}@endSection

@section('body_class'){!! 'page__contact' !!}@endSection

@section('content')
    <div class="page-head white-content">
        <div class="height50vh parallax-container" style="background-image: url({{ 'img/image-4.jpg' }});">
            <div class="page-head-wrap">
                <div class="display-r">
                    <div class="display-a">
                        <div class="container">
                            <div class="row justify-content-center animate" data-animation="fadeInUp"
                                 data-timeout="500">
                                <div class="col-md-12">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="home">Home</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">Contact</li>
                                        </ol>
                                    </nav>
                                    <h1 class="big-title mt-60">Contact</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content mt-60 mb-40 container">
        <div class="row">
            <div class="col-md-8">
                <h3 class="mb-20">Welcome to Du lich Viet Nam</h3>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quia accusamus, architecto, minus placeat
                    libero assumenda, reiciendis recusandae accusantium, in repudiandae sit mollitia labore repellat
                    molestias. Pariatur natus eligendi molestias temporibus!</p>

                <h3 class="mb-30">Our Offices</h3>

                <div class="row">
                    <div class="col-md-6">
                        <div class="contact-info mb-30">
                            <h4>{{ $user_info->area }}</h4>
                            <p><span class="ti-map-alt"></span>{{ $user_info->address_office }}</p>
                            <p><span class="ti-headphone-alt"></span>{{ $user_info->number_phone }}</p>
                            <p><span class="ti-email"></span><a
                                        href="mailto:info@voyagetime.com">{{ $user_info->email }}</a></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <h3 class="mb-20">Our Social</h3>
                <div class="clearfix mb-40">
                    <ul class="contact-social">
                        <li><a href="#"><span class="ti-facebook"></span></a></li>
                        <li><a href="#"><span class="ti-instagram"></span></a></li>
                        <li><a href="#"><span class="ti-twitter"></span></a></li>
                        <li><a href="#"><span class="ti-youtube"></span></a></li>
                        <li><a href="#"><span class="ti-pinterest"></span></a></li>
                    </ul>
                </div>
                <div class="contact-mr">
                    <h3 class="mb-20">Get in Touch</h3>

                    <form id="contact-form" class="mt-20 form-block" novalidate="novalidate" method="POST">
                        <div class="form-row contact-form">
                            <div class="col-md-12">
                                <input type="email" class="form-control" name="email" id="email" placeholder="Email">
                            </div>
                            <div class="col-md-12">
                                <input type="text" class="form-control" name="subject" id="subject"
                                       placeholder="Subject">
                            </div>
                            <div class="col-md-12">
                                <textarea name="message" id="message" placeholder="Message"></textarea>
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-1" id="send_mail">Send Message</button>
                            </div>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>
@endsection

