@extends('layout.layout')

@section('meta')
    <meta name="description" content=""/>
    <meta name="format-detection" content="telephone=no">
@endsection

@section('title'){!! 'ぐるなび Web Service - マイページ' !!}@endSection

@section('body_class'){!! 'page__mypage' !!}@endSection

@section('content')
    <div class="container form-group">
        <div class="row">
            <div class="padding-t--40 padding-b--40">
                <div class="row bg-white">
                    <div class="col-xs-10 col-xs-offset-1 padding-t--10">
                        <div class="row">
                            <h2 class="hx-std">マイページ</h2>
                        </div>
                        <div class="row margin-t--20n">
                            <h3>ユーザー情報</h3>
                        </div>
                        <div class="row margin-t--10">

                            <table class="table table-bordered --not-editable">
                                <tbody>
                                <tr>
                                    <th scope="row">ユーザーID</th>
                                    <td class="vam">
{{ $userData['user_id'] or '' }}
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">お名前（漢字）</th>
                                    <td class="vam">
                                        <span>
{{ $userData['user_name1'] or '' }}&nbsp;{{ $userData['user_name2'] or '' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">メールアドレス</th>
                                    <td class="vam">
                                        <span>
{{ $userData['mail1'] or '' }}
                                        </span>
                                    </td>
                                </tr>

@if ($userData['user_type'] == 0)
                                <tr class="js--personal-use">
                                    <th scope="row">郵便番号</th>
                                    <td class="vam">
    @if ($userData['foreign_status_k'] == 0)
        {{ $userData['zip_k_1'] or '' }}
                                        <span class="padding-l--3 padding-r--3">-</span>
        {{ $userData['zip_k_2'] or '' }}
    @elseif ($userData['foreign_status_k'] == 1)
                                        海外
    @endif
                                    </td>
                                </tr>
@elseif ($userData['user_type'] == 1)
                                <tr class="js--commercial-use">
                                    <th scope="row">会社名</th>
                                    <td class="vam">
                                        <span>
    {{ $userData['corporation_name'] }}
                                        </span>
                                    </td>
                                </tr>
                                <tr class="js--commercial-use">
                                    <th scope="row">会社郵便番号</th>
                                    <td class="vam">
    @if ($userData['foreign_status_h'] == 0)
        {{ $userData['zip_h_1'] }}
                                        <span class="padding-l--3 padding-r--3">-</span>
        {{ $userData['zip_h_2'] }}
    @elseif ($userData['foreign_status_h'] == 1)
                                        海外
    @endif
                                    </td>
                                </tr>
                                <tr class="js--commercial-use">
                                    <th scope="row">会社住所</th>
                                    <td>
                                        <div class="form-inline">
    @if ($userData['foreign_status_h'] == 0)
                                            <div class="margin-b--10">
        {{ $userData['pref_h'] }}
                                            </div>
                                            <div class="margin-b--10">
        {{ $userData['city_h'] }}
                                            </div>
                                            <div>
        {{ $userData['street_h'] }}
                                            </div>
    @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr class="js--commercial-use">
                                    <th scope="row">会社電話番号</th>
                                    <td class="vam">
    @if ($userData['foreign_status_h'] == 0)
        {{ $userData['tel_h_1'] }}
                                        <span class="padding-l--3 padding-r--3">-</span>
        {{ $userData['tel_h_2'] }}
                                        <span class="padding-l--3 padding-r--3">-</span>
        {{ $userData['tel_h_3'] }}
    @endif
                                    </td>
                                </tr>
                                <tr class="js--commercial-use">
                                    <th scope="row">担当部署</th>
                                    <td class="vam">
                                        <span>
    {{ $userData['department'] }}
                                        </span>
                                    </td>
                                </tr>
@endif
                                <!-- 個人・法人区分：法人の場合のみ表示 ここまで -->
                                <tr>
                                    <th scope="row">ぐるなびWebサービスメール</th>
                                    <td class="vam">
                                        <div>
                                            <span>
@if ($userData['accept_mail_magazine'])
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
                        <div class="row">
                            <div class="col-xs-2 col-xs-offset-10  padding-r--0">
                                <form action="/api/change/?p=input" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="btn btn-info form-control pull-right">変 更</button>
                                </form>
                            </div>
                        </div>
                        <!-- アプリケーション情報(アプリ登録可能時) -->
@if ($userData['contents_num'] < 10)
                        <div class="row">
                            <h3 class="margin-t--30">アプリケーション情報</h3>
    @if ($userData['contents_num'] >= 1)
                            <p>
                                新規で登録される場合は「アプリケーション追加」ボタンを押してください。<br>
                                変更、削除をされる場合は、各アプリケーション情報下の「変更」「削除」ボタンを押してください。<br>
                                アクセスキーの利用期限を延長する場合は、アクセスキーの利用期限欄の「利用期限延長」ボタンを押してください。
                            </p>
    @else
                            <p>
                                新規で登録される場合は「アプリケーション追加」ボタンを押してください。
                            </p>
    @endif
                        </div>
                        <div class="row display-table margin-t--5 margin-b--10n">
                            <div class="col-xs-3 display-table-cell-vam padding-l--0">
                                <form action="/api/app_regist/?p=input" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button class="btn btn-info form-control" type="submit">アプリケーション追加</button>
                                </form>
                            </div>
                            <div class="col-xs-9 display-table-cell-vam padding-l--0"></div>
                        </div>
@elseif ($userData['contents_num'] >= 10)
                        <div class="row">
                            <h3 class="margin-t--30">アプリケーション情報</h3>
                            <p>
                                新規で登録される場合は「アプリケーションの追加」ボタンを押してください。<br>
                                変更、削除をされる場合は、各アプリケーション情報下の「変更」「削除」ボタンを押してください。<br>
                                アクセスキーの利用期限を延長する場合は、アクセスキーの利用期限欄の「利用期限延長」ボタンを押してください。
                            </p>
                        </div>
                        <div class="row display-table margin-t--5 margin-b--10n">
                            <div class="col-xs-3 display-table-cell-vam padding-l--0">
                                <form action="/api/app_regist/?p=input" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button class="btn btn-default form-control disabled" type="submit" disabled>
                                        アプリケーション追加
                                    </button>
                                </form>
                            </div>
                            <div class="col-xs-9 display-table-cell-vam padding-l--0">
                                <small>登録できるアプリケーション数は最大１０件です。</small>
                            </div>
                        </div>
@endif

@if (is_array($appData))
    @foreach($appData as $aD)
        @if ($aD['service_status'] == 0 or $aD['service_status'] == 1 or $aD['service_status'] == 2)
                        <div class="row margin-t--30">
                            <table class="table table-bordered --not-editable">
                                <tbody>
                                <tr>
                                    <th scope="row">利用用途</th>
                                    <td class="vam">
                                        <div>
            @if ($aD['service_status'] == 1)
                                            試しに利用（一時利用）
            @elseif ($aD['service_status'] == 2)
                                            ハッカソン等の開発イベントで利用
            @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">サービス状況</th>
                                    <td class="vam">
                                        <div></div>
                                    </td>
                                </tr>
        @elseif ($aD['service_status'] == 3)
                        <div class="row margin-t--30">
                            <table class="table table-bordered --not-editable">
                                <tbody>
                                <tr>
                                    <th scope="row">利用用途</th>
                                    <td class="vam">
                                        <span>独自サービス用のアプリケーションで利用</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">サービス状況</th>
                                    <td class="vam">
                                        <div>
            @if ($aD['service_in'] == 0)
                                                    サービス開始前 （開発前/開発段階）
            @elseif ($aD['service_in'] == 1)
                                                    サービス中
            @endif
                                        </div>
                                    </td>
                                </tr>
        @endif
                                <tr>
                                    <th scope="row">アプリケーション名</th>
                                    <td class="vam">
                                        <div>
        @if ($aD['contents_name'] != '')
            {{ $aD['contents_name'] }}
        @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">アプリケーション種類</th>
                                    <td class="vam">
                                        <div>
        @if ($aD['contents_type'] == 1)
                                                    サーバサイド
        @elseif ($aD['contents_type'] == 2)
                                                    クライアントサイド
        @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">URL</th>
                                    <td class="vam wb">
                                        <div>
        @if ($aD['up_url'] != '')
            {{ $aD['up_url'] }}
        @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">概要</th>
                                    <td class="vam">
                                        <div>
        @if ($aD['contents_description'] != '')
            {{ $aD['contents_description'] }}
        @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row">アクセスキー</th>
                                    <td class="vam">
                                        <div>
        {{ $aD['access_key'] }}
                                        </div>
                                    </td>
                                </tr>
                                <th scope="row">アクセスキーの利用期限</th>
                                <td class="vam">
        @if ($aD['service_status'] == 0 or $aD['service_status'] == 1 or $aD['service_status'] == 2 or $aD['service_status'] == 3 and $aD['service_in'] == 0)
                                    <span>
            {!! date_format(date_create($aD['expire_date']), 'Y/m/d') !!}
                                    </span>

                                    <form action="/api/mypage/" method="post" class="inline-block margin-l--5">
                                        <!-- 有効期限まで31日以上 -->
                                        @if (strtotime('30 day') <= strtotime($aD['expire_date']))
                                            <button class="btn form-control btn-default disabled" type="submit" disabled>
                                              利用期限の延長
                                            </button>
                                            <input type="hidden" name="access_key" value="{{ $aD['access_key'] }}">
                                            <!-- 有効期限まで30日以内 -->
                                        @else
                                            <!-- 有効期限内 -->
                                            @if (strtotime(date_format(now(), 'Y-m-d')) <= strtotime($aD['expire_date']) and $aD['expire_reason_1'] == 0 and $aD['expire_reason_2'] == 0 and $aD['expire_reason_3'] == 0 and $aD['expire_reason_4'] == 0 and $aD['expire_reason_5'] == 0 and $aD['service_status'] != 0)
                                                <button class="btn form-control btn-info" type="submit" name="submit" value="extension">
                                                利用期限の延長
                                                </button>
                                                <input type="hidden" name="extension" value="extension">
                                                <input type="hidden" name="access_key" value="{{ $aD['access_key'] }}">
                                            @else
                                                <!--有効期限切れ又は無効 -->
                                                <button class="btn form-control btn-default disabled" type="submit" disabled>
                                                利用期限の延長
                                                </button>
                                                <input type="hidden" name="access_key" value="{{ $aD['access_key'] }}">
                                            @endif
                                        @endif
                                    </form>
        @else
                                    <span>
                                        なし
                                    </span>
        @endif
                                </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row margin-t--15">
                            <div class="col-xs-9 text-right padding--0">
        @if ($aD['expire_reason_1'] == 1)
                                <span class="color--red">
                                    一定期間以上APIの利用が確認できなかったためアクセスキーが無効となっています。
                                </span>
        @endif
        @if ($aD['expire_reason_2'] == 1 or $aD['expire_reason_3'] == 1 or $aD['expire_reason_4'] == 1)
                                <span class="color--red">
                                    利用規約に準じていないためアクセスキーが無効となっています。
                                </span>
        @endif
        @if ($aD['expire_status'] == 1)
            @if (($aD['expire_reason_5'] == 1 or strtotime(date_format(now(), 'Y-m-d')) > strtotime($aD['expire_date'])) and $aD['expire_reason_1'] == 0 and $aD['expire_reason_2'] == 0 and $aD['expire_reason_3'] == 0 and $aD['expire_reason_4'] == 0)
                                <span class="color--red">
                                    アクセスキーの利用期限が切れています。
                                </span>
            @endif
        @endif
                            </div>
                            <div class="col-xs-2 padding-r--0">
                                <form action="/api/app_change/?p=input" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
        @if (($aD['expire_status'] == 0 or strtotime(date_format(now(), 'Y-m-d')) <= strtotime($aD['expire_date'])) and $aD['expire_reason_1'] == 0 and $aD['expire_reason_2'] == 0 and $aD['expire_reason_3'] == 0 and $aD['expire_reason_4'] == 0 and $aD['expire_reason_5'] == 0)
                                    <button class="btn form-control btn-info" type="submit">変 更</button>
                                    <input type="hidden" name="access_key" value="{{ $aD['access_key'] or '' }}">
                                    <input type="hidden" name="service_status" value="{{ $aD['service_status'] or '' }}">
                                    <input type="hidden" name="user_id" value="{{ $userData['user_id'] or '' }}">
                                    <input type="hidden" name="my_page" value="my_page">
        @else
                                    <button class="btn form-control btn-default disabled" disabled
                                            type="submit">変 更
                                    </button>
        @endif
                                </form>
                            </div>
                            <div class="col-xs-1 padding-r--0">
                                <form action="/api/app_delete/?p=conf" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button class="btn btn-default btn-trash form-control" type="submit">
                                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                    </button>
                                    <input type="hidden" name="access_key" value="{{ $aD['access_key'] or '' }}">
                                    <input type="hidden" name="service_status" value="{{ $aD['service_status'] or '' }}">
                                    <input type="hidden" name="user_id" value="{{ $userData['user_id'] or '' }}">
                                </form>
                            </div>
                        </div>
    @endforeach
@endif
                        <div class="row">
                            <hr>
                        </div>
                        <div class="row margin-b--30">
                            <form action="/api/unsubscribe/?p=input" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button class="btn-anchor padding--0 pull-right" type="submit">退会したい場合はこちら
                                </button>
                                <input type="hidden" name="user_id" value="{{ $userData['user_id'] or '' }}">
                                <input type="hidden" name="user_type" value="{{ $userData['user_type'] or '' }}">
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
