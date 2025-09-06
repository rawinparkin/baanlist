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

<link rel="stylesheet" href="{{ asset('frontend/css/add2.css') }}">

<style>
    .google-ads {
        height: 150px
    }
</style>

@endsection
@section('main')



<!-- Header Container
================================================== -->
@include('frontend.property.body.header2')
<!-- Header Container / End -->




<div class="container">

    <!-- Row / Start -->
    <div class="row">

        <div class="col-md-12">
            <div class="pricing-container margin-top-70 margin-bottom-70">

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



                        <a class="button {{ $item->id == 2 ? '' : 'border' }}"
                            href="{{ route('choose.plan', $item->id) }}">
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








<!-- Footer
================================================== -->
@include('frontend.home.footer')
<!-- Footer / End -->

<!-- Back To Top Button -->
<div id="backtotop"><a href="#"></a></div>








@endsection

@section('scripts')
@vite(['resources/js/app.js'])





@endsection