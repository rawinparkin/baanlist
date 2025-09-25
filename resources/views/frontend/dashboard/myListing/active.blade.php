@extends('frontend.frontend_dashboard')


@section('meta')
<title>ประกาศของฉัน | baanlist</title>
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
                    <h2>ประกาศของฉัน</h2>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs">
                        <ul>
                            <li><a href="{{ route('home.index') }}">หน้าหลัก</a></li>
                            <li><a href="{{ route('dashboard') }}">แผงควบคุม</a></li>
                            <li>ประกาศของฉัน</li>
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
                    @if($property->count())
                    <h4>ประกาศทั้งหมด</h4>
                    @else
                    <h4>คุณยังไม่มีประกาศ!</h4>
                    @endif
                    <ul>


                        @foreach($property as $key => $item)
                            @php
                                $original = $item->detail->cover_photo;
                                $thumbnail = preg_replace('#(upload/property/\d+/)([^/]+)$#', '$1thumbnails/$2', $original);
                            @endphp
                        <li class="{{ $item->status == 0 ? 'bg-hidden-listing' : '' }}">
                            <div class="list-box-listing">
                                <div class="list-box-listing-img"><a
                                        href="{{ route('property.details', ['id' => $item->id, 'slug' => $item->detail->property_slug]) }}">
                                        <img 
                                    src="{{ asset($thumbnail) }}" 
                                    onerror="this.onerror=null;this.src='{{ asset($original) }}';" 
                                    alt="{{ $item->detail->property_name }}" 
                                    loading="lazy"></a>
                                </div>

                                <div class="list-box-listing-content">
                                    <div class="inner">
                                        <h3><a
                                                href="{{ route('property.details', ['id' => $item->id, 'slug' => $item->detail->property_slug]) }}">{{ $item['detail']['property_name'] }}</a>
                                        </h3>
                                        <span>{{ $item->location->subdistrict->name_th.', '.$item->location->province->name_th }}</span>


                                        @if(!empty($item->detail->bedrooms) && !empty($item->detail->bathrooms))
                                        <div>{{ $item->detail->bedrooms }} ห้องนอน •
                                            {{ $item->detail->bathrooms }} ห้องน้ำ
                                        </div>
                                        @else
                                        <div>ที่ดิน {{ $item->detail->land_size }} ตร.ม.</div>
                                        @endif
                                        <div>
                                            @if($item->status == 0)
                                            <span style="color:red;">หมดอายุแล้ว</span>
                                            @else
                                            หมดอายุ: {{ thaiDate($item->expire_date) }}
                                            @endif
                                        </div>




                                    </div>
                                </div>
                            </div>
                            <div class="buttons-to-right">
                                <a href="{{route('edit.listing', $item->id) }}" class="button gray"><i
                                        class="sl sl-icon-note"></i> แก้ไข</a>
                                <a href="{{route('user.delete.property', $item->id)}}" class="button gray delete-link" data-id="{{ $item->id }}><i class="sl sl-icon-close"></i> ลบ</a>
                                
                            </div>
                        </li>



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
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent the default link behavior

            const href = this.getAttribute('href');

            Swal.fire({
                title: "คุณแน่ใจหรือไม่?",
                text: "คุณต้องการลบประกาศนี้",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#ff4f58",
                cancelButtonColor: "#aaa", 
                confirmButtonText: "ลบเลย",
                cancelButtonText: "ยกเลิก",
                customClass: {
                    popup: "swal-wide"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect manually to the delete URL
                    window.location.href = href;
                }
            });
        });
    });
});
</script>
@endsection