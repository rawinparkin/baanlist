@extends('frontend.frontend_dashboard')
@section('meta')
<title>ข้อความ | baanlist</title>
<meta name="description" content="baanlist เว็บไซต์ค้นหาอสังหาริมทรัพย์ ซื้อขายบ้าน คอนโด ที่ดิน ง่ายและรวดเร็ว พร้อมข้อมูลครบถ้วน ใกล้คุณ">
<meta name="keywords" content="บ้าน, คอนโด, ที่ดิน, ขายบ้าน, หาบ้าน, ซื้อบ้าน, ขายคอนโด, อสังหาริมทรัพย์">




@endsection
@section('main')



<!-- Header Container
================================================== -->
@include('frontend.dashboard.body.header')
<!-- Header Container / End -->

<!-- Dashboard -->
<div id="dashboard">

    <!-- Navigation
================================================== -->

    @include('frontend.dashboard.body.sidebar')

    <!-- Content
	================================================== -->
    <div class="dashboard-content">

        <!-- Titlebar -->
        <div id="titlebar">
            <div class="row">
                <div class="col-md-12">
                    <h2>ข้อความ</h2>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs">
                        <ul>
                            <li><a href="{{ route('home.index') }}">หน้าหลัก</a></li>
                            <li><a href="{{ route('dashboard') }}">แผงควบคุม</a></li>
                            <li>ข้อความ</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>



        <!-- Listings -->





        <div id="app">
            <chat-message>
            </chat-message>
        </div>














        <!-- Copyrights -->
        <div class="col-md-12">
            <div class="copyrights">{{$settings->copyright}}</div>
        </div>
    </div>

</div>
<!-- Content / End -->



</div>
<!-- Dashboard / End -->

@endsection

@section('scripts')
@vite(['resources/js/app.js'])
@endsection