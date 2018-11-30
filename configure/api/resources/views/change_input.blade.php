@extends('layout.layout')

@section('meta')
    <meta name="description" content=""/>
    <meta name="format-detection" content="telephone=no">
@endsection

@section('title'){!! 'ぐるなび Web Service - ユーザー情報変更' !!}@endSection

@section('body_class'){!! 'page__user_change' !!}@endSection

@section('content')
    <form action="?p=conf" method="post" class="form-group form-group-lg">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="container padding-t--40 padding-b--40">
            <div class="padding-t--10 bg-white cx">
                <h2 class="hx-std">ユーザー情報変更</h2>
                <div class="col-xs-10 col-xs-offset-1">
                    <p class="text-center">
                        変更したい情報を編集し、「上記規約に同意して変更確認画面へ」ボタンを押してください。
                    </p>
                    <hr/>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th scope="row">ユーザーID <span class="label label-danger">必須</span></th>
                            <td>
                                <div>
                                    <div class="margin-b--5 color--red">
@if ($errors->has('user_id'))
    {!! $errors->first('user_id') !!}
@endif
                                    </div>
                                    <div class="font-bold margin-b--5">【半角英数6〜50文字以内】</div>
                                    <input type="text" class="form-control" placeholder="" name="user_id" value="{{ $user_data['user_id'] or '' }}" maxlength="50"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">パスワード
                                <span class="label label-danger">必須</span></th>
                            <td>
                                <div class="font-bold margin-b--5">【半角英数6〜16文字以内】</div>
                                <div class="form-inline">
                                    <div class="margin-b--10">
                                        <div class="margin-b--5 color--red">
@if ($errors->has('pass1'))
    {!! $errors->first('pass1') !!}
@endif
                                        </div>
                                        <input type="password" class="form-control width--270" placeholder="" name="pass1" value="{{ $user_data['pass1'] or '' }}" maxlength="16"/>
                                    </div>
                                    <div>
                                        <div class="margin-b--5 color--red">
@if ($errors->has('pass2'))
    {!! $errors->first('pass2') !!}
@endif
                                        </div>
                                        <input type="password" class="form-control width--270" placeholder="" name="pass2" value="{{ $user_data['pass2'] or '' }}" maxlength="16"/>
                                        <span class="padding-l--5">確認用</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">お名前（漢字） <span class="label label-danger">必須</span></th>
                            <td>
                                <div class="form-inline">
                                    <div class="margin-b--5 color--red">
@if ($errors->has('user_name1'))
    {!! $errors->first('user_name1') !!}
@elseif ($errors->has('user_name2'))
    {!! $errors->first('user_name2') !!}
@endif
                                    </div>
                                    <span class="font-size--16 padding-r--5">　姓</span>
                                    <input type="text" class="form-control width--135" placeholder="" name="user_name1" value="{{ $user_data['user_name1'] or '' }}" maxlength="20"/>
                                    <span class="font-size--16 padding-r--5">　名</span>
                                    <input type="text" class="form-control width--135" placeholder="" name="user_name2" value="{{ $user_data['user_name2'] or '' }}" maxlength="20"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">メールアドレス <span class="label label-danger">必須</span></th>
                            <td>
                                <div class="form-inline">
                                    <div class="margin-b--10">
                                        <div class="margin-b--5 color--red">
@if ($errors->has('mail1'))
    {!! $errors->first('mail1') !!}
@endif
                                        </div>
                                        <input type="text" class="form-control width--400" placeholder="" name="mail1" value="{{ $user_data['mail1'] or '' }}" maxlength="60"/>
                                    </div>
                                    <div>
                                        <div class="margin-b--5 color--red">
@if ($errors->has('mail2'))
    {!! $errors->first('mail2') !!}
@endif
                                        </div>
                                        <input type="text" class="form-control width--400" placeholder="" name="mail2" value="{{ $user_data['mail2'] or '' }}" maxlength="60"/>
                                        <span class="padding-l--5">確認用</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
@if (isset($user_data['user_type']))
    @if ($user_data['user_type'] == 0)
                        <tr class="js--personal-use">
                            <th scope="row">郵便番号 <span class="label label-danger">必須</span></th>
                            <td>
                                <div class="font-bold margin-b--5">【半角数字】</div>
                                <div class="form-inline">
                                    <div class="margin-b--5 color--red">
        @if ($errors->has('zip_k_1'))
            {!! $errors->first('zip_k_1') !!}
        @elseif ($errors->has('zip_k_2'))
            {!! $errors->first('zip_k_2') !!}
        @endif

                                    </div>
                                    <div class="">
                                        <input type="text" class="form-control width--90  js--disable_state_abroad_from_personal" placeholder="" name="zip_k_1" value="{{ $user_data['zip_k_1'] or '' }}" maxlength="3"/>
                                        <span class="padding-l--3 padding-r--3">―</span>
                                        <input type="text" class="form-control width--120  js--disable_state_abroad_from_personal" placeholder="" name="zip_k_2" value="{{ $user_data['zip_k_2'] or '' }}" maxlength="4"/>
                                        <div class="checkbox padding-l--3">
                                            <label>
                                                <input name="foreign_status_k" type="checkbox" value='1' @if (isset($user_data['foreign_status_k']) and $user_data['foreign_status_k'] > 0) checked="checked" @endif class="js--check_company_is_abroad_personal">
                                                <span>海外の場合はこちらをチェック</span>
                                            </label>
                                        </div>

                                    </div>
                                </div>
                            </td>
                        </tr>
    @elseif ($user_data['user_type'] == 1)
                        <tr class="js--commercial-use">
                            <th scope="row">会社名 <span class="label label-danger">必須</span></th>
                            <td>
                                <div class="margin-b--5 color--red">
        @if ($errors->has('corporation_name'))
            {!! $errors->first('corporation_name') !!}
        @endif
                                </div>
                                <div>
                                    <input type="text" class="form-control" placeholder="" name="corporation_name" value="{{ $user_data['corporation_name'] or '' }}" maxlength="50"/>
                                </div>
                            </td>
                        </tr>
                        <tr class="js--commercial-use">
                            <th scope="row">会社郵便番号 <span class="label label-danger">必須</span></th>
                            <td>
                                <div class="font-bold margin-b--5">【半角数字】</div>
                                <div class="form-inline">
                                    <div class="margin-b--5 color--red">
        @if ($errors->has('zip_h_1'))
            {!! $errors->first('zip_h_1') !!}
        @elseif ($errors->has('zip_h_2'))
            {!! $errors->first('zip_h_2') !!}
        @endif
                                    </div>
                                    <div class="">
                                        <input type="text" class="form-control width--90 js--disable_state_abroad_from_commercial" placeholder="" name="zip_h_1" value="{{ $user_data['zip_h_1'] or '' }}" maxlength="3"/>
                                        <span class="padding-l--3 padding-r--3">―</span>
                                        <input type="text" class="form-control width--120 js--disable_state_abroad_from_commercial" placeholder="" name="zip_h_2" value="{{ $user_data['zip_h_2'] or '' }}" maxlength="4"/>
                                        <div class="checkbox padding-l--3">

                                                <input name="foreign_status_h" type="checkbox" value='1' @if (isset($user_data['foreign_status_h']) and $user_data['foreign_status_h'] > 0) checked="checked" @endif class="js--check_company_is_abroad_commercial">
                                                 <label><span>海外の場合はこちらをチェック</span></label>

                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="js--commercial-use">
                            <th scope="row">会社住所 <span class="label label-danger">必須</span></th>
                            <td>
                                <div class="form-inline">
                                    <div class="margin-b--5 color--red">
        @if ($errors->has('pref_h'))
            {!! $errors->first('pref_h') !!}
        @endif
                                    </div>
                                    <div class="margin-b--10">
                                        <span>　都道府県</span>
                                        <select class="form-control  js--disable_state_abroad_from_commercial" name="pref_h">
                                            <option label="都道府県選択" value="0">都道府県選択</option>
                                            @foreach($prefList as $value)
                                                <option label="{{ $value->PREF_NAME }}" value="{{ $value->PREF_CODE }}" @if(isset($user_data['pref_h']) and $value->PREF_CODE == $user_data['pref_h']) {!! 'selected' !!} @endif>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="margin-b--5 color--red">
        @if ($errors->has('city_h'))
            {!! $errors->first('city_h') !!}
        @endif
                                    </div>
                                    <div class="margin-b--10">
                                        <span class="padding-r--5">　市区町村</span>
                                        <input type="text" class="form-control width--400  js--disable_state_abroad_from_commercial" placeholder="" name="city_h" value="{{ $user_data['city_h'] or '' }}" maxlength="50"/>
                                        <div class="margin-b--5 color--red">
        @if ($errors->has('street_h'))
            {!! $errors->first('street_h') !!}
        @endif
                                        </div>
                                    </div>
                                    <div>
                                        <span class="padding-r--5">番地・ビル</span>
                                        <input type="text" class="form-control width--400  js--disable_state_abroad_from_commercial" placeholder="" name="street_h" value="{{ $user_data['street_h'] or '' }}" maxlength="50"/>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="js--commercial-use">
                            <th scope="row">会社電話番号 <span class="label label-danger">必須</span></th>
                            <td>
                                <div class="form-inline">
                                    <div class="margin-b--5 color--red">
        @if ($errors->has('tel_h_1'))
           {!! $errors->first('tel_h_1') !!}
        @elseif ($errors->has('tel_h_2'))
           {!! $errors->first('tel_h_2') !!}
        @elseif ($errors->has('tel_h_3'))
           {!! $errors->first('tel_h_3') !!}
        @endif
                                    </div>
                                    <input type="text" class="form-control width--120 js--disable_state_abroad_from_commercial" placeholder="" name="tel_h_1" value="{{ $user_data['tel_h_1'] or '' }}" maxlength="5"/>
                                    <span class="padding-l--3 padding-r--3">―</span>
                                    <input type="text" class="form-control width--120 js--disable_state_abroad_from_commercial" placeholder="" name="tel_h_2" value="{{ $user_data['tel_h_2'] or '' }}" maxlength="5"/>
                                    <span class="padding-l--3 padding-r--3">―</span>
                                    <input type="text" class="form-control width--120 js--disable_state_abroad_from_commercial" placeholder="" name="tel_h_3" value="{{ $user_data['tel_h_3'] or '' }}" maxlength="5"/>
                                </div>
                            </td>
                        </tr>
                        <tr class="js--commercial-use">
                            <th scope="row">担当部署 <span class="label label-danger">必須</span></th>
                            <td>
                                <div class="margin-b--5 color--red">
        @if ($errors->has('department'))
            {!! $errors->first('department') !!}
        @endif
                                </div>
                                <div>
                                    <input type="text" class="form-control" placeholder="" name="department" value="{{ $user_data['department'] or '' }}" maxlength="50"/>

                                </div>
                            </td>
                        </tr>
    @endif
@endif
                        </tbody>
                    </table>
                </div>

                <div class="col-xs-10 col-xs-offset-1 padding-t--5">
                    <div class="margin-b--20">
                        <label class="weight-normal">
                            <input type="checkbox" name="accept_mail_magazine" @if (isset($user_data['accept_mail_magazine']) and $user_data['accept_mail_magazine']) {!! 'checked="checked"' !!} @endif value="1"/>
                            <span class="padding-l--5">ぐるなびWebサービスからのメールを受け取る。（バージョン情報更新のお知らせなどをお送りします）</span></label>
                    </div>
                    <div class="shic-area margin-b--40 padding--20">
                        <div class="shic-area__title">■個人情報の取り扱いについて</div>
                        <p class="shic-area__p">
                            ご登録いただいた個人情報は、本サービスの運営、お問い合わせ対応、メール配信（希望者のみ）の目的で利用いたします。なお、お客様の同意なくお知らせした以外の目的での利用、第三者提供はいたしません。<br/>
                            その他の個人情報の取り扱いに関しては、当社の<a href="https://corporate.gnavi.co.jp/policy/" target="_blank">プライバシーポリシー</a>をご参照ください。
                        </p>
                    </div>
                </div>

                <div class="col-xs-6 col-xs-offset-3 padding-b--20">
                    <button class="btn btn-info form-control" type="submit">
                        上記規約に同意して変更確認画面へ
                    </button>
                </div>

                <div class="col-xs-6 col-xs-offset-3 padding-b--20">
                    <a href="../mypage/" class="btn btn-default form-control">
                        戻る
                    </a>
                </div>
            </div>
        </div>
        <input type="hidden" name="user_type" value="{{ $user_data['user_type'] or '' }}">
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

