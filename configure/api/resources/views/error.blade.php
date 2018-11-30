@extends('layout.layout')

@section('meta')
    <meta name="robots" content="noindex,nofollow,noarchive" />
@endsection

@section('title'){!! 'ぐるなび Web Service - 新規アカウント発行/設定変更 エラー' !!}@endSection

@section('body_class'){!! 'page__subscribe_comp' !!}@endSection

@section('content')
    <div class="container padding-t--40 padding-b--40">
        <div class="padding-t--10 bg-white cx">
            <h2 class="hx-std">{!! $title !!}</h2>
            <div class="col-xs-10 col-xs-offset-1 padding-t--10 padding-b--30">
                <p>{!! $msg !!}</p>
            </div>
        </div>
    </div>
@endsection
