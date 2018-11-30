@extends('layout.layout')

@section('meta')
    <meta name="description" content=""/>
    <meta name="format-detection" content="telephone=no">
@endsection

@section('title'){!! 'ぐるなび Web Service - ユーザー情報 入力内容確認' !!}@endSection

@section('body_class'){!! 'page__user_change_conf' !!}@endSection

@section('content')
    <form action="?p=comp" method="post" class="form-group form-group-lg" data-submit="?p=comp" data-goback="?p=input">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="container padding-t--40 padding-b--40">
            <div class="padding-t--10 bg-white cx">
                <h2 class="hx-std">ユーザー情報 入力内容確認</h2>

                <div class="col-xs-10 col-xs-offset-1">
                    <p class="text-center">
                        以下の内容でよろしいでしょうか。よろしければ「変更する」ボタンを押してください。
                    </p>
                    <hr/>

                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th scope="row">ユーザーID</th>
                            <td class="vam">
{{ $postData['user_id'] or '' }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">パスワード</th>

                            <td class="vam">
                                <span>
{{ $postData['escapePass'] or '' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">お名前（漢字）</th>

                            <td class="vam">
{{ $postData['user_name1'] or '' }}&nbsp;{{ $postData['user_name2'] or '' }}
                            </td>

                        </tr>
                        <tr>
                            <th scope="row">メールアドレス</th>

                            <td class="vam">
{{ $postData['mail1'] or '' }}
                            </td>

                        </tr>
@if (isset($postData['user_type']))
    @if ($postData['user_type'] == 0)
                        <tr class="js--personal-use">
                            <th scope="row">郵便番号</th>

                            <td class="vam">
        @if (isset($postData['foreign_status_k']))
            @if ($postData['foreign_status_k'] == 0)
                {{ $postData['zip_k_1'] or '' }}
                                <span class="padding-l--3 padding-r--3">-</span>
                {{ $postData['zip_k_2'] or '' }}
            @elseif ($postData['foreign_status_k'] == 1)
                                海外
            @endif
        @endif
                            </td>
                        </tr>
    @elseif ($postData['user_type'] == 1)
                        <tr class="js--commercial-use">
                            <th scope="row">会社名</th>

                            <td class="vam">
                                <span>
        {{ $postData['corporation_name'] or '' }}
                                </span>
                            </td>
                        </tr>
                        <tr class="js--commercial-use">
                            <th scope="row">会社郵便番号</th>

                            <td class="vam">
        @if (isset($postData['foreign_status_h']))
            @if ($postData['foreign_status_h'] == 0)
                {{ $postData['zip_h_1'] or '' }}
                                <span class="padding-l--3 padding-r--3">-</span>
                {{ $postData['zip_h_2'] or '' }}
            @elseif ($postData['foreign_status_h'] == 1)
                                海外
            @endif
        @endif
                            </td>
                        </tr>
                        <tr class="js--commercial-use">
                            <th scope="row">会社住所</th>
                            <td>
                                <div class="form-inline">
                                    <div class="margin-b--10">
        @if (isset($postData['foreign_status_h']) and $postData['foreign_status_h'] == 0)
            {{ $pref_h or '' }}
        @endif
                                    </div>
                                    <div class="margin-b--10">
        @if (isset($postData['foreign_status_h']) and $postData['foreign_status_h'] == 0)
            {{ $postData['city_h'] or '' }}
        @endif
                                    </div>
                                    <div>
        @if (isset($postData['foreign_status_h']) and $postData['foreign_status_h'] == 0)
            {{ $postData['street_h'] or '' }}
        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="js--commercial-use">
                            <th scope="row">会社電話番号</th>

                            <td class="vam">
        @if (isset($postData['foreign_status_h']) and $postData['foreign_status_h'] == 0)
                                <span>
            {{ $postData['tel_h_1'] or '' }}
                                </span>
                                <span class="padding-l--3 padding-r--3">-</span>
                                <span>
            {{ $postData['tel_h_2'] or '' }}
                                </span>
                                <span class="padding-l--3 padding-r--3">-</span>
                                <span>
            {{ $postData['tel_h_3'] or '' }}
                                </span>
        @endif
                            </td>
                        </tr>
                        <tr class="js--commercial-use">
                            <th scope="row">担当部署</th>

                            <td class="vam">
                                <span>
        {{ $postData['department'] or '' }}
                                </span>
                            </td>
                        </tr>
    @endif
@endif
                        <tr>
                            <th scope="row">ぐるなびWebサービスメール</th>
                            <td class="vam">
                                <div>
                                    <span>
@if (isset($postData['accept_mail_magazine']) and $postData['accept_mail_magazine'])
                                        受け取る
@else
                                        受け取らない
@endif
                                    </span>
                                </div>
                            </td>
                        </tr>

                        </tbody>
                    </table>

                </div>

                <div class="col-xs-6 col-xs-offset-3 padding-b--20">
                    <button class="btn btn-info form-control js--submit" type="submit">
                        変更する
                    </button>
                </div>

                <div class="col-xs-6 col-xs-offset-3 padding-b--20">
                    <button class="btn btn-default form-control js--re-edit" type="submit">
                        戻る
                    </button>
                </div>
            </div>

        </div>
        <input type="hidden" name="user_id" value="{{ $postData['user_id'] or '' }}">
        <input type="hidden" name="user_name1" value="{{ $postData['user_name1'] or '' }}">
        <input type="hidden" name="user_name2" value="{{ $postData['user_name2'] or '' }}">
        <input type="hidden" name="pass1" value="{{ $postData['pass1'] or '' }}">
        <input type="hidden" name="pass2" value="{{ $postData['pass2'] or '' }}">
        <input type="hidden" name="mail1" value="{{ $postData['mail1'] or '' }}">
        <input type="hidden" name="mail2" value="{{ $postData['mail2'] or '' }}">
        <input type="hidden" name="user_type" value="{{ $postData['user_type'] or '' }}">
        <input type="hidden" name="accept_mail_magazine" value="{{ $postData['accept_mail_magazine'] or '' }}">
@if (isset($postData['user_type']))
    @if ($postData['user_type'] == 0)
        <input type="hidden" name="zip_k_1" value="{{ $postData['zip_k_1'] or '' }}">
        <input type="hidden" name="zip_k_2" value="{{ $postData['zip_k_2'] or '' }}">
        <input type="hidden" name="foreign_status_k" value="{{ $postData['foreign_status_k'] or '' }}">

    @elseif ($postData['user_type'] == 1)
        <input type="hidden" name="corporation_name" value="{{ $postData['corporation_name'] or '' }}">
        <input type="hidden" name="tel_h_1" value="{{ $postData['tel_h_1'] or '' }}">
        <input type="hidden" name="tel_h_2" value="{{ $postData['tel_h_2'] or '' }}">
        <input type="hidden" name="tel_h_3" value="{{ $postData['tel_h_3'] or '' }}">
        <input type="hidden" name="zip_h_1" value="{{ $postData['zip_h_1'] or '' }}">
        <input type="hidden" name="zip_h_2" value="{{ $postData['zip_h_2'] or '' }}">
        <input type="hidden" name="pref_h" value="{{ $postData['pref_h'] or '' }}">
        <input type="hidden" name="city_h" value="{{ $postData['city_h'] or '' }}">
        <input type="hidden" name="street_h" value="{{ $postData['street_h'] or '' }}">
        <input type="hidden" name="department" value="{{ $postData['department'] or '' }}">
        <input type="hidden" name="foreign_status_h" value="{{ $postData['foreign_status_h'] or '' }}">
    @endif
@endif
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
