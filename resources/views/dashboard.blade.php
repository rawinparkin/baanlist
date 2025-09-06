@php
$seo = App\Models\Seo::first();
@endphp

@extends('frontend.frontend_dashboard')
@section('meta')
<title>แผงควบคุม - {{$seo->title1}}</title>
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
@include('frontend.dashboard.body.header')
<!-- Header Container / End -->

<!-- Dashboard -->
<div id="dashboard">

    <!-- Navigation
================================================== -->

    @include('frontend.dashboard.body.sidebar')

    <!-- Content
================================================== -->

    @php

    $id = Auth::user()->id;
    $userData = App\Models\User::find($id);
    $setting = App\Models\SiteSetting::find(1);
    $wishlist = App\Models\Wishlist::where('user_id', $id)->limit(4)->get();
    @endphp



    <div class="dashboard-content">

        <!-- Titlebar -->
        <div id="titlebar">
            <div class="row">
                <div class="col-md-12">
                    <h2>สวัสดี {{$userData->name}}!</h2><span> {{ $userData->credit }} เครดิต<i class="tip"
                                            data-tip-content="จำนวนประกาศที่สามารถลงได้"></i></span>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs">
                        <ul>
                            <li><a href="{{ route('home.index') }}">หน้าหลัก</a></li>
                            <li><a href="{{ route('dashboard') }}">แผงควบคุม</a></li>
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


        <!-- Content -->
        <div class="row">

            <!-- Item -->
            <div class="col-lg-3 col-md-6">
                <div class="dashboard-stat color-1">
                    <div class="dashboard-stat-content">
                        <h4>{{ $userData->property->count() }}</h4> <span>ประกาศ</span>
                    </div>
                    <div class="dashboard-stat-icon"><i class="im im-icon-Map2"></i></div>
                    
                </div>
            </div>

             <div class="col-lg-3 col-md-6">
                <div class="dashboard-stat color-4">
                    <div class="dashboard-stat-content">
                        <h4>{{ $userData->wishlist->count() }}</h4> <span>ประกาศที่ชอบ</span>
                    </div>
                    <div class="dashboard-stat-icon"><i class="im im-icon-Heart"></i></div>
                </div>
            </div>

            <!-- Item -->
            <div class="col-lg-3 col-md-6">
                <div class="dashboard-stat color-2">
                    <div class="dashboard-stat-content">
                        <h4>{{ $userData->property->sum('views') }}</h4> <span>คนเห็นประกาศ</span>
                    </div>
                    <div class="dashboard-stat-icon"><i class="im im-icon-Line-Chart"></i></div>
                </div>
            </div>


            <!-- Item -->
            <div class="col-lg-3 col-md-6">
                <div class="dashboard-stat color-3">
                    <div class="dashboard-stat-content">
                        <h4>{{ $userData->messages->count() }}</h4> <span>ข้อความ</span>
                    </div>
                    <div class="dashboard-stat-icon"><i class="im im-icon-Add-UserStar"></i></div>
                </div>
            </div>

            <!-- Item -->
           
        </div>


        <div class="row">

            <!-- Recent Activity -->
            <div class="col-lg-6 col-md-12">
                <div class="dashboard-list-box with-icons margin-top-20">
                    <h4>ประกาศที่ชอบ</h4>
                    <ul>

                    @if($wishlist->count())
                        @foreach($wishlist as $item)
                            <li>
                                <i class="list-box-icon sl sl-icon-heart"></i> <strong><a
                                        href="{{ route('property.details', ['id' => $item->property->id, 'slug' => $item->property->detail->property_slug]) }}" 
                                        alt="{{ $item->property->detail->property_name }}">{{ $item->property->detail->property_name }}</a></strong> 
                                        <a href="javascript:void(0);" class="close-list-item delete-wishlist" data-id="{{ $item->id }}"><i class="fa fa-close"></i></a>           
                            </li>
                        @endforeach
                        @else
                        <li><i class="list-box-icon sl sl-icon-heart"></i> ยังไม่มีประกาศที่ชอบ<a href="#" class="close-list-item"><i class="fa fa-close"></i></a></li>
                    @endif

                        
                    </ul>
                </div>
            </div>

            <!-- Invoices -->
            <div class="col-lg-6 col-md-12">
                <div class="dashboard-list-box invoices with-icons margin-top-20">
                    <h4>ข้อมูลส่วนตัว</h4>
                    <ul>

                        <li><i class="list-box-icon sl sl-icon-phone"></i>
                            <strong>เบอร์โทรศัพท์</strong>
                            <ul>
                                <li>{{ $userData->phone ? $userData->phone : 'ยังไม่มีข้อมูล' }}</li>
                                <div class="buttons-to-right">
								    <a href="{{route('user.profile')}}" class="button gray"><i class="sl sl-icon-pencil"></i> แก้ไข</a>
							    </div>
                            </ul>
                            
                        </li>

                        <li><i class="list-box-icon sl sl-icon-doc"></i>
                            <strong>ไลน์ไอดี</strong>
                            <ul>
                                <li>{{ $userData->line ? $userData->line : 'ยังไม่มีข้อมูล' }}</li>
                                <div class="buttons-to-right">
								    <a href="{{route('user.profile')}}" class="button gray"><i class="sl sl-icon-pencil"></i> แก้ไข</a>
							    </div>
                            </ul>
                            
                        </li>

                   

                      

                    </ul>
                </div>
            </div>


            <!-- Copyrights -->
            <div class="col-md-12">
                <div class="copyrights">{{ $setting->copyright}}</div>
            </div>
        </div>

    </div>
    <!-- Content / End -->

     


</div>
<!-- Dashboard / End -->

@endsection

@section('scripts')
<script>
$(document).on("click", ".delete-wishlist", function(e) {
    e.preventDefault();
    const itemId = $(this).data("id");
    deleteFromWishList(
        itemId,
        "{{ url('user/wishlist/delete') }}",
        "{{ csrf_token() }}"
    );
});
</script>

@if(request()->query('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'ข้อมูล',
        text: "{{ request()->query('success') }}",
        confirmButtonColor: '#84c015',
        customClass: {
            popup: "swal-wide"
        },
    });

    // ล้างพารามิเตอร์หลังจากแสดง SweetAlert
    if (window.location.search.includes("success=")) {
        history.replaceState(null, "", window.location.pathname);
    }
</script>
@endif

@endsection