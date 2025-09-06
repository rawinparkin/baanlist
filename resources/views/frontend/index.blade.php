@extends('frontend.frontend_dashboard')
@section('meta')
<title>{{$seo->title1}}</title>
<meta name="description" content="{{$seo->description}}">
<meta name="keywords" content="{{$seo->keywords}}">

<!-- Open Graph -->
<meta property="og:title" content="{{$seo->title2}}" />
<meta property="og:description" content="{{$seo->title3}}" />
<meta property="og:image" content="{{ asset('frontend/images/banner.jpg') }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:type" content="website" />

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{$seo->title2}}" />
<meta name="twitter:description" content="{{$seo->title3}}" />
<meta name="twitter:image" content="{{ asset('frontend/images/banner.jpg') }}" />
@endsection


@section('main')

<!-- Header Container
================================================== -->
@include('frontend.home.header')

<!-- Header Container / End -->
<div class="clearfix"></div>

<!-- Banner
================================================== -->
@include('frontend.home.banner')


<!-- Content
================================================== -->
@include('frontend.home.category')
<!-- Category Boxes / End -->

<!-- Listings -->
@include('frontend.home.listing')
<!-- Listings / End -->


<!-- Recent Blog Posts -->
@include('frontend.home.blog')
<!-- Recent Blog Posts / End -->

<!-- Footer
================================================== -->
@include('frontend.home.footer')
<!-- Footer / End -->


<!-- Back To Top Button -->
<div id="backtotop"><a href="#"></a></div>

<!-- Sticky Bottom Bar -->
{{-- <div id="sticky-bar">
    <a href="#sign-in-dialog" class="sticky-btn">เข้าสู่ระบบ</a>
    <a href="dashboard-add-listing.html" class="sticky-btn sticky-listing">ลงประกาศ</a>
</div> --}}




@endsection
