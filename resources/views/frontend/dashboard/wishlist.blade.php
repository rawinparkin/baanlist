@extends('frontend.frontend_dashboard')


@section('meta')
<title>ประกาศที่ชอบ | baanlist</title>
<meta name="description" content="{{$seo->description}}">
<meta name="keywords" content="{{$seo->keywords}}">




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
                    <h2>ประกาศที่ชอบ</h2>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs">
                        <ul>
                            <li><a href="{{ route('home.index') }}">หน้าหลัก</a></li>
                            <li><a href="{{ route('dashboard') }}">แผงควบคุม</a></li>
                            <li>ประกาศที่ชอบ</li>
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

        <div class="row">

            <!-- Listings -->
            <div class="col-lg-12 col-md-12">
                <div class="dashboard-list-box margin-top-0">
                    <h4>ประกาศทั้งหมด</h4>
                    <ul>
                        @foreach($wishlist as $item)
                        @php
                                $original = $item->property->detail->cover_photo;
                                $thumbnail = preg_replace('#(upload/property/\d+/)([^/]+)$#', '$1thumbnails/$2', $original);
                            @endphp
                        @if ($item->property && $item->property->detail && $item->property->location)
                        <li>
                            <div class="list-box-listing">
                                <div class="list-box-listing-img">
                                    <a
                                        href="{{ route('property.details', ['id' => $item->property->id, 'slug' => $item->property->detail->property_slug]) }}">
                                        <img 
                                    src="{{ asset($thumbnail) }}" 
                                    onerror="this.onerror=null;this.src='{{ asset($original) }}';" 
                                    alt="{{ $item->property->detail->property_name }}" 
                                    loading="lazy">
                                    </a>
                                </div>

                                <div class="list-box-listing-content">
                                    <div class="inner">
                                        <h3>
                                            <a
                                                href="{{ route('property.details', ['id' => $item->property->id, 'slug' => $item->property->detail->property_slug]) }}">
                                                {{ $item->property->detail->property_name }}
                                            </a>
                                        </h3>

                                        <span>
                                            {{ $item->property->location->property_address }},
                                            {{ $item->property->location->province->name_th }}
                                        </span>

                                        @if(!empty($item->property->detail->bedrooms) &&
                                        !empty($item->property->detail->bathrooms))
                                        <div>
                                            {{ $item->property->detail->bedrooms }} ห้องนอน •
                                            {{ $item->property->detail->bathrooms }} ห้องน้ำ
                                        </div>
                                        @else
                                        <div>
                                            ที่ดิน {{ $item->property->detail->land_size }} ตร.ม.
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="buttons-to-right">
                                <a href="javascript:void(0);" class="button gray delete-wishlist"
                                    data-id="{{ $item->id }}">
                                    <i class="sl sl-icon-close"></i> ลบ
                                </a>
                            </div>
                        </li>
                        @endif
                        @endforeach
                    </ul>

                </div>
            </div>



            <!-- Copyrights -->
            <div class="col-md-12">
                <div class="copyrights">© 2021 baanlist. All Rights Reserved.</div>
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

@endsection