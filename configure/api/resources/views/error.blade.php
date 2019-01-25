@extends('layout.layout')

@section('meta')
    <meta name="robots" content="noindex,nofollow,noarchive"/>
@endsection

@section('title'){!! 'ぐるなび Web Service - 新規アカウント発行/設定変更 エラー' !!}@endSection

@section('body_class'){!! 'page__subscribe_comp' !!}@endSection

@section('content')
    <!-- content -->
    <div class="order-form animate" data-animation="fadeInUp" data-timeout="1000">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    {{ 'đây là trang lỗi' }}
                    <a href="home">Quay lại trang chủ</a>
                </div>
            </div>
        </div>
    </div>

@endsection
