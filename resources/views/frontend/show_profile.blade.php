@extends('frontend.frontend_dashboard')
@section('meta')

<title>{{ $user->name }} | baanlist</title>
<meta name="description" content="{{$seo->description}}">
<meta name="keywords" content="{{$seo->keywords}}">

<!-- Open Graph for Facebook, LINE -->
<meta property="og:title" content="{{ $user->name }} | baanlist" />
<meta property="og:description" content="{{$seo->title3}}" />
<meta property="og:image" content="{{ asset($property[0]->detail->cover_photo ?? 'frontend/images/banner.jpg') }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:type" content="website" />

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $user->name }} | baanlsit" />
<meta name="twitter:description" content="{{$seo->title3}}" />
<meta name="twitter:image" content="{{ asset($property[0]->detail->cover_photo ?? 'frontend/images/banner.jpg') }}" />
{!! $seo->adsense_headtag !!}


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">


@endsection

@section('main')

<!-- Header Container
================================================== -->
@include('frontend.home.header')

<div class="clearfix"></div>
<!-- Titlebar
================================================== -->
<div id="titlebar" class="gradient">
    <div class="container margin-top-20">
        <div class="row">
            <div class="col-md-12">

                <div class="user-profile-titlebar">
                    <div class="user-profile-avatar"><img
                            src="{{ (!empty($user->photo)) ? url('upload/users/'.$user->id.'/'.$user->photo.'') : url('upload/users/boy.png') }}"
                            alt="">
                    </div>
                    <div class="user-profile-name">
                        <h2>{{ $user->name }}</h2>
                        
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<div class="container">
    <div class="row sticky-wrapper">


        <!-- Sidebar
		================================================== -->
        <div class="col-lg-4 col-md-4 margin-top-0">

            <!-- Contact -->
            <div class="boxed-widget margin-top-0 margin-bottom-30">
                <h3>ติดต่อ</h3>
                <ul class="listing-details-sidebar">

                    @if(trim($user->phone ?? '') !== '')
                    <li><i class="sl sl-icon-phone"></i> {{ format_thai_phone($user->phone) }}</li>
                    @endif
                    @if(trim($user->line ?? '') !== '')
                    <li style="position:relative;"><i class="bi bi-line"
                            style="font-size:18px;position:absolute;top:0;"></i>
                        {{ $user->line }}
                    </li>
                    @endif

                </ul>



                <!-- Reply to review popup -->
                <div id="small-dialog" class="zoom-anim-dialog mfp-hide">
                    <div class="small-dialog-header">
                        <h3>ส่งข้อความ</h3>
                    </div>
                    <div class="message-reply margin-top-0">
                        <textarea cols="40" rows="3" placeholder="Your message to Tom"></textarea>
                        <button class="button">ส่งข้อความ</button>
                    </div>
                </div>

                <a href="#small-dialog" class="send-message-to-owner button popup-with-zoom-anim"><i
                        class="sl sl-icon-envelope-open"></i> ส่งข้อความ</a>
            </div>
            <!-- Contact / End-->

            <!-- About -->
            <div class="boxed-widget margin-top-0 margin-bottom-50">
                <h3>เกี่ยวกับ</h3>
                <p>{{ $user->about }}</p>
            </div>
            <!-- About / End-->

            <!-- Ads -->
            <div class="boxed-widget margin-top-30 margin-bottom-30">
                <p>{!! $seo->adsense !!}</p>
            </div>
            <!-- Ads / End-->

        </div>
        <!-- Sidebar / End -->


        <!-- Content
		================================================== -->
        <div class="col-lg-8 col-md-8 padding-left-30 margin-bottom-50">



            <!-- Listings Container -->
            <div class="row">

                @forelse($property as $item)
                <!-- Listing Item -->
                <div class="col-lg-12 col-md-12">
                    <div class="listing-item-container list-layout">
                        <a href="{{ route('property.details', [$item->id, $item->detail->property_slug]) }}"
                            class="listing-item">
                            <!-- Image -->
                            <div class="listing-item-image">
                                <img src="{{ asset($item->detail->cover_photo) }}"
                                    alt="{{ $item->detail->property_name }}">
                            </div>
                            <!-- Content -->
                            <div class="listing-item-content">

                                <div class="listing-item-inner">
                                    <h3>{{ $item->detail->property_name }}</h3>
                                    <span>{{ $item->location?->subdistrict?->name_th }},
                                        {{ $item->location?->province?->name_th }}</span>
                                </div>
                                <span class="like-icon" data-id="{{ $item->id }}"></span>
                            </div>
                        </a>
                    </div>
                </div>
                <!-- Listing Item / End -->
                @empty
                <div class="col-12 text-center">
                    <p>ยังไม่มีประกาศ</p>
                </div>
                @endforelse


            </div>
            <!-- Listings Container / End -->
        </div>

    </div>
</div>



<!-- Footer
================================================== -->
@include('frontend.home.footer')
<!-- Footer / End -->


<!-- Back To Top Button -->
<div id="backtotop"><a href="#"></a></div>



@endsection