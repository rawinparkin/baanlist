@extends('frontend.frontend_dashboard')


@section('meta')
<title>แพ็กเกจ | baanlist</title>
<meta name="description" content="{{$seo->description}}">
<meta name="keywords" content="{{$seo->keywords}}">

<!-- Open Graph -->
<meta property="og:title" content="แพ็กเกจ | baanlist" />
<meta property="og:description" content="{{$seo->title3}}" />
<meta property="og:image" content="{{ asset('frontend/images/banner.jpg') }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:type" content="website" />

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="แพ็กเกจ | baanlist" />
<meta name="twitter:description" content="{{$seo->title3}}" />
<meta name="twitter:image" content="{{ asset('frontend/images/banner.jpg') }}" />


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
                    <h2>ซื้อแพ็กเกจ</h2>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs">
                        <ul>
                            <li><a href="{{ route('home.index') }}">หน้าหลัก</a></li>
                            <li><a href="{{ route('dashboard') }}">แผงควบคุม</a></li>
                            <li>ซื้อแพ็กเกจ</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Notice -->
        @if(session('notification'))
        <div class="row">
            <div class="col-md-12">
                <div class="notification {{ session('notification_class', 'success') }} closeable margin-bottom-30">
                    <p>{{ session('notification') }}</p>
                    <a class="close" href="#"></a>
                </div>
            </div>
        </div>
        @endif

        <!-- Row / Start -->
        <div class="row">

            <div class="col-md-12">
                <div class="pricing-container margin-top-30">

                    @foreach($plan as $item)
                    <div class="plan {{ $item->id == 2 ? 'featured' : '' }}">
                        @if($item->id == 2)
                        <div class="listing-badge">
                            <span class="featured">แนะนำ</span>
                        </div>
                        @endif

                        <div class="plan-price">
                            <h3>{{ $item->package_name }}</h3>
                            <span class="value">{{ $item->package_cost }}</span>
                            <span class="period">{{ $item->cost_desc ?? '' }}</span>
                        </div>

                        <div class="plan-features">
                            <ul class="list-3 color">
                                <li> {{ $item->package_credits }} ประกาศ</li>
                                <li> ใช้งานได้ {{ $item->validity_days }} วัน
                                </li>
                                @if(!empty($item->feature_desc))
                                <li> {{ $item->feature_desc }}</li>
                                @endif
                                @if(!empty($item->description))
                                <li> {{ $item->description }}</li>
                                @endif
                            </ul>


                                {{-- OMISE Payment
                            <a class="button {{ $item->id == 2 ? '' : 'border' }}"
                                href="{{ route('choose.plan', $item->id) }}">
                                เลือก
                            </a> --}}

                            <a class="button {{ $item->id == 2 ? '' : 'border' }}"
                                href="{{ route('check.out', $item->id) }}">
                                เลือก
                            </a>

                        </div>
                    </div>
                    @endforeach





                </div>
            </div>
        </div>
        <!-- Row / End -->

    </div>
    <!-- Content / End -->



</div>
<!-- Dashboard / End -->



@endsection
@section('scripts')
@if(session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: "{{ session('error') }}",
            confirmButtonColor: '#84c015',
            customClass: {
                    popup: "swal-wide"
                },
        });
    </script>
@endif
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'ข้อมูล',
            text: "{{ session('success') }}",
            confirmButtonColor: '#84c015',
            customClass: {
                    popup: "swal-wide"
                },
        });
    </script>
@endif


@endsection