@extends('frontend.frontend_dashboard')
@section('meta')
<title>เงื่อนไขการใช้งาน | baanlist</title>
<meta name="description" content="{{$seo->description}}">
<meta name="keywords" content="{{$seo->keywords}}">

<!-- Open Graph -->
<meta property="og:title" content="นโยบายความเป็นส่วนตัว | baanlist" />
<meta property="og:description" content="{{$seo->title3}}" />
<meta property="og:image" content="{{ asset('frontend/images/banner.jpg') }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:type" content="website" />

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="นโยบายความเป็นส่วนตัว | baanlist" />
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

                <h2 class="margin-bottom-40">เงื่อนไขการใช้งาน</h2>
                <div class="clear "></div>
                <div>
                {!!$setting->terms_of_service!!}
                </div>

                





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


@endsection