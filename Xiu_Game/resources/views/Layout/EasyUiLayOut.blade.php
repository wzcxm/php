@extends('Layout.WeUiLayout')
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/easyui/themes/bootstrap/easyui.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/easyui/themes/mobile.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/js/easyui/themes/icon.css') }}">
    @yield('easyui_style')
@endsection
@section('content')
    @yield('easyui_content')
@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('/js/easyui/jquery.easyui.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/easyui/jquery.easyui.mobile.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/easyui/locale/easyui-lang-zh_CN.js') }}"></script>
    @yield('easyui_script')
@endsection