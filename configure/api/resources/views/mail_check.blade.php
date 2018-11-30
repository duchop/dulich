@extends('layout.layout')

@section('meta')
    <meta name="robots" content="noindex,nofollow,noarchive"/>
@endsection

@section('title'){!! 'ぐるなび Web Service - パスワードをお忘れの方' !!}@endSection

@section('body_class'){!! 'page__lost-password' !!}@endSection

@section('content')
    <form action="" method="post" class="form-group form-group-lg">
        <div class="container padding-t--40 padding-b--40">
            <div class="padding-t--10 bg-white cx">
                <h2 class="hx-std">パスワードをお忘れの方</h2>
                <div class="col-xs-10 col-xs-offset-1">
                    <p class="text-center">
                        アカウント登録いただいたメールアドレスを入力の上、「次へ」ボタンを押してください。<br/>ご登録いただいたメールアドレスにパスワードを送信します。
                    </p>
                    <hr/>
                </div>
                <div class="col-xs-10 col-xs-offset-1 padding-t--5">
                    <div class="margin-b--5 color--red">
@if ($errors->has('user_id'))
    {!! $errors->first('user_id') !!}
@endif
@if ($errors->has('mail'))
    {!! $errors->first('mail') !!}
@endif
{!! $msg or '' !!}
                    </div>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th scope="row">ユーザ ID</th>
                            <td>
                                <div>
                                    <input type="text" class="form-control" placeholder="" name="user_id" value="{{ $user_id or '' }}" maxlength="50"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">メールアドレス</th>
                            <td>
                                <div>
                                    <input type="text" class="form-control" placeholder="" name="mail" value="{{ $mail or '' }}" maxlength="60"/>
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
<div style="display:none;height:0;position:relative;visibility:hidden;width:0;">
    <script src="//x.gnst.jp/s.js"></script>
    <script>
        ('localhost' !== location.hostname) && document.write(unescape("%3Cscript src='//site.gnavi.co.jp/analysis/sc_" + getScSubdom() + ".js'%3E%3C/script%3E"));
    </script>
</div>
@endsection
