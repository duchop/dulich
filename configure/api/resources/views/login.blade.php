@extends('layout.layout')

@section('meta')
    <meta name="robots" content="noindex,nofollow,noarchive"/>
@endsection

@section('title'){!! 'ぐるなび Web Service - アカウント情報変更 ログイン' !!}@endSection

@section('body_class'){!! 'page__login' !!}@endSection

@section('content')
    <form action="/api/login/" method="post" class="form-group form-group-lg">
        <div class="container padding-t--40 padding-b--40">
            <div class="padding-t--10 bg-white cx">
                <h2 class="hx-std">アカウント情報変更 ログイン</h2>
                <div class="col-xs-10 col-xs-offset-1">
                    <p class="text-center">
                        ユーザー情報は、適宜最新の情報に更新をお願いいたします。<br/>アカウント登録いただいたメールアドレス及びパスワードを入力の上、次へボタンを押してください。
                        <br/>パスワードをお忘れの方は
                        <a href="https://ssl.gnavi.co.jp/api/send_pass/">こちら</a>。
                    </p>
                    <hr/>
                </div>
                <div class="col-xs-10 col-xs-offset-1 padding-t--5">
                    <div class="margin-b--5 color--red">
@if ($errors->any())
    @foreach ($errors->all() as $error)
        {!! $error !!}
    @endforeach
@endif
                    </div>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th scope="row">メールアドレス</th>
                            <td>
                                <div>
                                    <input type="text" class="form-control" placeholder="" name="mail" value="{{ $_POST['mail'] or ''}}"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">パスワード</th>
                            <td>
                                <div>
                                    <input type="password" class="form-control" placeholder="" name="pass" value="{{ $_POST['pass'] or '' }}" maxlength="60"/>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-6 col-xs-offset-3 padding-b--40">
                    <button class="btn btn-info form-control send-query" type="submit">
                        次へ
                    </button>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script')
<!--
    <div style="display:none;height:0;position:relative;visibility:hidden;width:0;">
        <script src="//x.gnst.jp/s.js"></script>
        <script>
            ('localhost' !== location.hostname) && document.write(unescape("%3Cscript src='//site.gnavi.co.jp/analysis/sc_" + getScSubdom() + ".js'%3E%3C/script%3E"));
        </script>
    </div>
-->
@endsection
