@extends('frontend.frontend_dashboard')
@section('meta')
<title>{{ $property->detail->property_name }} | baanlist</title>
<meta name="description" content="{{$seo->description}}">
<meta name="keywords" content="{{$seo->keywords}}">

@php
    $original = $property->detail->cover_photo;
    $thumbnail = preg_replace('#(upload/property/\d+/)([^/]+)$#', '$1thumbnails/$2', $original);
@endphp 
<!-- Open Graph for Facebook, LINE -->
<meta property="og:title" content="{{ $property->detail->property_name }} | baanlist" />
<meta property="og:description" content="{{$seo->title3}}" />
<meta property="og:image" content="{{ asset($thumbnail) }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:type" content="website" />

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $property->detail->property_name }} | baanlist" />
<meta name="twitter:description" content="{{$seo->title3}}" />
<meta name="twitter:image" content="{{ asset($thumbnail) }}" />


<link rel="stylesheet" href="{{ asset('frontend/css/add2.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">


@endsection
@section('main')



<!-- Header Container
================================================== -->
@include('frontend.property.body.header2')
<!-- Header Container / End -->




<!-- Content
================================================== -->



<div class="container">

    <div class="row sticky-wrapper">
        <div class="col-md-12">
            <!-- Titlebar -->
            <div class="row">
                <div id="listing-gallery" class="col-md-12 listeo-grid-gallery-title">

                    <div id="titlebar" class="listing-titlebar listing-titlebar-has-logo">
                        <div class="listing-titlebar-title">
                            <div class="listing-titlebar-tags">
                             
                                    
                                <span class="listing-tag">
                                    <a href="{{ route('search.property', [
                                        'lat' => $property->location->lat,
                                        'lon' => $property->location->lon,
                                        'purpose' => $property->property_status,
                                        'category' => $property->property_type_id,
                                        ]) }}">
                                        {{$property->purpose->purpose_name}} {{ $property->type->type_name  }}
                                    </a>
                                </span>
                                 
                              
                                <span class="listing-tag">
                                    <a href="{{ route('search.property', [
                                                'lat' => $property->location->lat,
                                                'lon' => $property->location->lon,
                                                'purpose' => $property->property_status,
                                                'category' => $property->property_type_id,
                                                'category' => $property->property_type_id,
                                                'label' => $property->location->district->name_th ." ".$property->location->province->name_th,]) }}">
                                                {{ $property->location->district->name_th ?? '' }},
                                        {{ $property->location->province->name_th ?? '' }}</a> </span>
                            </div>
                            <h2>{{ $property->detail->property_name }}</h2>
                            <!-- <span>
                                    <a href="#listing-location" class="listing-address">
                                        <i class="fa fa-map-marker"></i>
                                        123 ซอยศรีสมิตร, เทพารักษ์, เมืองสมุทรปราการ, สมุทรปราการ
                                    </a>
                                </span> -->


                        </div>

                        <div class="listing-widget widget listeo_core widget_buttons">
                            <div class="listing-share margin-top-40 margin-bottom-40 no-border">
                                <!-- <button class="like-button listeo_core-bookmark-it" data-post_id="578"
                                        data-confirm="Bookmarked!" data-nonce="9b7915c1e8"><span class="like-icon"></span>
                                        <b class="bookmark-btn-title">เก็บประกาศนี้ไว้ดู</b>
                                    </button> -->


                            </div>
                        </div>
                    </div> <!-- Titlebar -->
                </div> <!-- end listing-gallery -->
            </div><!-- e/ row -->

            <div class="row">
                <div class="col-md-12">

                    <div class="listeo-single-listing-gallery-grid">
                        <div id="single-listing-grid-gallery" class="mfp-gallery-container">


                            <a href="#" id="single-listing-grid-gallery-popup" data-gallery='@json($galleryUrls)'
                                data-gallery-count="{{ $galleryUrls->count() }}" class="slg-button">
                                <i class="sl sl-icon-grid"></i> ดูรูปทั้งหมด
                            </a>
                            <div class="slg-half">
                                <a data-grid-start-index="0"
                                    href="{{ asset('upload/property/'.$gallery[0]->property_id.'/thumbnails/'.$gallery[0]->filename) }}"
                                    class="slg-gallery-img">
                                    <img 
                                        src="{{ asset('upload/property/'.$gallery[0]->property_id.'/thumbnails/'.$gallery[0]->filename) }}" loading="lazy" alt="{{ $property->detail->property_name }}">
                                </a>
                            </div>

                            <div class="slg-half">
                                <div class="slg-grid">
                                    <div class="slg-grid-top">
                                        @foreach($gallery as $index => $img)
                                        @if($index > 0 && $index < 3) <div class="slg-grid-inner">
                                            <a data-grid-start-index="{{ $index}}"
                                                href="{{ asset('upload/property/'.$img->property_id.'/thumbnails/'.$img->filename) }}"
                                                class="slg-gallery-img" data-background-image=""><img
                                                    src="{{ asset('upload/property/'.$img->property_id.'/thumbnails/'.$img->filename) }}" loading="lazy" alt="{{ $property->detail->property_name }}"></a>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                                <div class="slg-grid-bottom">
                                    @foreach($gallery as $index => $img)
                                    @if($index > 2 && $index < 5) <div class="slg-grid-inner"><a
                                            data-grid-start-index="{{ $index }}"
                                            href="{{ asset('upload/property/'.$img->property_id.'/thumbnails/'.$img->filename) }}"
                                            class="slg-gallery-img" data-background-image=""><img
                                                src="{{ asset('upload/property/'.$img->property_id.'/thumbnails/'.$img->filename) }}" loading="lazy" alt="{{ $property->detail->property_name }}"></a>
                                </div>
                                @endif
                                @endforeach
                            </div>




                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div> <!-- end col-md-12 -->
</div>







<div class="row sticky-wrapper">

    <div class="col-lg-8 col-md-8 padding-right-30">

        <h3>
            {{ $property->location->property_address }} {{ $property->location->subdistrict->name_th ?? '' }},
            {{ $property->location->district->name_th ?? '' }}, {{ $property->location->province->name_th ?? '' }}
        </h3>
        <span>
            @if($property->detail->bedrooms != Null && $property->detail->bedrooms != '0')
            <h4 class="font-light">
                {{ $property->detail->bedrooms }} ห้องนอน • {{ $property->detail->bathrooms }} ห้องน้ำ
            </h4>
            @else
            @endif
        </span>



        <!-- Apartment Description -->
        <ul class="apartment-details">
            @if($property->detail->land_size !== null && $property->detail->land_size !== '0')
            <li><i class="im im-icon-Tree-2"></i> พื้นที่ {{ $property->detail->land_size }} ตร.ม.</li>
            @endif
            @if($property->detail->usage_size != Null && $property->detail->usage_size != '0')
            <li><i class="im im-icon-Ruler-2"></i>ใช้สอย {{ $property->detail->usage_size }} ตร.ม.</li>
            @endif
            @if($property->property_built_year != Null && $property->property_built_year != '0')
            <li><i class="im im-icon-Hammer"></i>สร้าง พ.ศ. {{ $property->property_built_year }}</li>
            @endif
        </ul>


        <!-- Listing Nav -->
        <div id="listing-nav" class="listing-nav-container">
            <ul class="listing-nav">
                <li><a href="#listing-overview" class="active">รายละเอียด</a></li>
                <li><a href="#listing-amenities">สิ่งอำนวยความสะดวก</a></li>
                <li><a href="#listing-location">แผนที่</a></li>
                <li><a href="#listing-neighborhood">อสังหาฯใกล้เคียง</a></li>
            </ul>
        </div>

        <!-- Overview -->
        <div id="listing-overview" class="listing-section">

            <!-- Description -->

            {!! $property->detail->long_descp !!}


        </div>
        <!-- Overview / End -->

        <!-- Amenities -->
        <div id="listing-amenities" class="listing-section">
            <!-- Amenities -->
            <h3 class="listing-desc-headline">สิ่งอำนวยความสะดวก</h3>
            <ul class="listing-features checkboxes" id="">

                @foreach($property->amenities as $amenity)
                <li>{{ $amenity->amenity_name }}</li>
                @endforeach

            </ul>
        </div>


        <!-- Location -->
        <div id="listing-location" class="listing-section">
            <h3 class="listing-desc-headline margin-top-60 margin-bottom-30">แผนที่</h3>

            <div id="singleListingMap-container">
                <div id="singleListingMap" data-latitude="{{ $property->location->lat }}"
                    data-longitude="{{ $property->location->lon }}" data-map-icon="fa fa-home">
                </div>
                <a href="#" id="streetView">Street View</a>
            </div>
        </div>
        <!-- Location / End -->










    </div>


    <!-- Sidebar
                        ================================================== -->
    <div class="col-lg-4 col-md-4 sticky margin-top-20">


        <!-- Verified Badge -->
        <div class="verified-badge ">
            @if($property->property_status=='1')
            <div class="font-light price-tag">ขาย {{ addCommas($property->price->sell_price) }} บาท</div>
            @elseif($property->property_status=='2')
            <div class="font-light price-tag">ให้เช่า {{ addCommas($property->price->rent_price) }} บาท</div>
            @else
            <div class="font-light price-tag">ขาย {{ addCommas($property->price->sell_price) }} บาท / เช่า
                {{ addCommas($property->price->rent_price) }} บาท
            </div>
            @endif
        </div>

        <!-- Contact -->
        <div class="boxed-widget margin-top-35">
            <div class="hosted-by-title">
                <h4><a
                        href="{{ route('show.profile', ['identifier' => $owner_data->uuid]) }}">{{ $owner_data->name }}</a>
                </h4>
                <a href="{{ route('show.profile', ['identifier' => $owner_data->uuid]) }}" class="
                    hosted-by-avatar"><img
                        src="{{ $owner_data->photo ? asset('upload/users/' . $owner_data->id . '/' . $owner_data->photo) : asset('upload/users/boy.png') }}"
                        alt="{{ $owner_data->name }}"></a>
            </div>
            <ul class="listing-details-sidebar">
                @if(trim($owner_data->phone ?? '') !== '')
                <li><i class="sl sl-icon-phone"></i> {{ format_thai_phone($owner_data->phone) }}</li>
                @endif
                @if(trim($owner_data->line ?? '') !== '')
                <li><i class="bi bi-line move-up"></i> {{ $owner_data->line }}</li>
                @endif
            </ul>
 

            <div id="app">
                @auth
                <send-message owner-name="{{ e($owner_data->name) }}" receiver-id="{{ $owner_data->id }}"
                    page-url="{{ urldecode(url()->current()) }}">
                </send-message>

                @endauth
            </div>
            <a href="#small-dialog" class="send-message-to-owner button popup-with-zoom-anim"
                data-authenticated="{{ auth()->check() ? 'true' : 'false' }}">
                <i class="sl sl-icon-envelope-open"></i> ส่งข้อความ
            </a>
        </div>
        <!-- Contact / End-->




        <!-- Share / Like -->
        <div class="listing-share margin-top-40 margin-bottom-40 no-border">
            <button class="like-button" data-id="{{ $property->id }}"><span class="like-icon"></span>
                เก็บประกาศนี้</button>
            <span>159 คนเก็บประกาศนี้ไว้ดู</span>

            <!-- Share Buttons -->
            <ul class="share-buttons margin-top-40 margin-bottom-0">
                <li>
                    <a class="fb-share" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::fullUrl()) }}" 
                        target="_blank" rel="noopener" >
                        <i class="fa fa-facebook"></i> แชร์
                    </a>
                    </li>
                <li><a class="twitter-share" href="https://twitter.com/intent/tweet?url={{ urlencode(Request::fullUrl()) }}&text=ดูประกาศนี้สิ!" target="_blank"><i class="fa fa-twitter"></i> X</a></li>
                <li><a class="line-share" href="https://social-plugins.line.me/lineit/share?url={{ urlencode(Request::fullUrl()) }}" target="_blank"><i class="bi bi-line move-up"></i> LINE</a></li>
               
            </ul>
            <div class="clearfix"></div>
        </div>

    </div>
    <!-- Sidebar / End -->


    <div class="col-lg-12 col-md-12 ">

        @if($relatedProperty->isNotEmpty())
        <!-- Neighborhood -->
        <div id="listing-neighborhood" class="listing-section">
            <h3 class="listing-desc-headline margin-top-70 margin-bottom-30">อสังหาฯใกล้เคียง</h3>


            <div class="col-md-12">
                <div class="row">

                    @foreach($relatedProperty as $item)

                            @php
                                $original = $item->detail->cover_photo;
                                $thumbnail = preg_replace('#(upload/property/\d+/)([^/]+)$#', '$1thumbnails/$2', $original);
                            @endphp

                    <!-- Listing Item -->
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('property.details', [$item->id, $item->detail->property_slug]) }}"
                            class="listing-item-container compact">
                            <div class="listing-item">
                                <img 
                                    src="{{ asset($thumbnail) }}" 
                                    onerror="this.onerror=null;this.src='{{ asset($original) }}';" 
                                    alt="{{ $item->detail->property_name }}" 
                                    loading="lazy">
                                <span class="like-icon" data-id="{{ $item->id }}"></span>
                            </div>
                            <div class="listing-item-inner">
                                @if($item->property_status == '1' && $item->price->sell_price != null &&
                                $item->price->sell_price != '0')
                                <span class="price-listing-number">฿{{ formatPrice($item->price->sell_price) }}</span>
                                @elseif($item->property_status == '2' && $item->price->rent_price != null &&
                                $item->price->rent_price != '0')
                                <span class="price-listing-number">฿{{ formatPrice($item->price->rent_price) }}</span>
                                @elseif($item->property_status == '3' && $item->price->sell_price != null &&
                                $item->price->sell_price != '0')
                                <span class="price-listing-number">฿{{ formatPrice($item->price->sell_price) }}</span>
                                @endif
                                <div class="clear"></div>
                                @if($item->detail->bedrooms != null && $item->detail->bedrooms != '0')
                                <span class="bed-bath-number">{{ $item->detail->bedrooms }} ห้องนอน |
                                    {{ $item->detail->bathrooms }} ห้องน้ำ</span>
                                @elseif($item->detail->land_size != null)
                                <span class="bed-bath-number">{{ $item->detail->land_size }} ไร่</span>
                                @endif
                                <p class="listing-location-number">{{ $item->location->subdistrict->name_th }},
                                    {{ $item->location->province->name_th }}
                                </p>


                            </div>

                        </a>

                    </div>
                    <!-- Listing Item / End -->

                    @endforeach

                </div>
            </div>
        </div>
        <!-- Neighborhood / End -->
        @endif
    </div>


</div>
</div>

<div class="margin-top-40"></div>



<!-- Footer
================================================== -->
@include('frontend.home.footer')
<!-- Footer / End -->

<!-- Back To Top Button -->
<div id="backtotop"><a href="#"></a></div>





<!-- Booking Sticky Footer MOBILE-->
<div class="booking-sticky-footer">
    <div class="container">
        <div class="bsf-left">
            @if($property->property_status=='1')
            <div class="price-tag-bottom">ขาย ฿{{ addCommas($property->price->sell_price) }}</div>
            @elseif($property->property_status=='2')
            <div class="price-tag-bottom">เช่า ฿{{ addCommas($property->price->rent_price) }}</div>
            @else
            <div class="price-tag-bottom">ขาย ฿{{ formatPrice($property->price->sell_price) }} / เช่า
                {{ $property->price->rent_price }}
            </div>
            @endif
            <div>
                @if($property->detail->bedrooms != Null && $property->detail->bedrooms != '0')
                {{ $property->detail->bedrooms }} ห้องนอน • {{ $property->detail->bathrooms }} ห้องน้ำ
                @elseif($property->detail->land_size != Null && $property->detail->land_size != '0')
                {{ $property->detail->land_size }} ไร่
                @endif
            </div>
        </div>


        <div class="bsf-right">
            <a href="tel:+66{{ $owner_data->phone }}" class="button popup-with-zoom-anim"><i
                    class="sl sl-icon-phone"></i>
                {{ format_thai_phone($owner_data->phone)}}</a>
        </div>
    </div>
</div>




@endsection

@section('scripts')
@vite(['resources/js/app.js'])

<script>
    // Single Listing Map Init
    if (document.getElementById("singleListingMap") !== null) {
        singleListingMap();
    }

    function singleListingMap() {
        var lng = parseFloat($("#singleListingMap").data("longitude"));
        var lat = parseFloat($("#singleListingMap").data("latitude"));
        var singleMapIco =
            "<i class='" + $("#singleListingMap").data("map-icon") + "'></i>";

        var listeoIcon = L.divIcon({
            iconAnchor: [20, 51], // point of the icon which will correspond to marker's location
            popupAnchor: [0, -51],
            className: "listeo-marker-icon",
            html: '<div class="marker-container no-marker-icon ">' +
                '<div class="marker-card">' +
                '<div class="front face">' +
                singleMapIco +
                "</div>" +
                '<div class="back face">' +
                singleMapIco +
                "</div>" +
                '<div class="marker-arrow"></div>' +
                "</div>" +
                "</div>",
        });

        var mapOptions = {
            center: [lat, lng],
            zoom: 15,
            zoomControl: false,
            gestureHandling: true,
        };

        var map_single = L.map("singleListingMap", mapOptions);
        var zoomOptions = {
            zoomInText: '<i class="fa fa-plus" aria-hidden="true"></i>',
            zoomOutText: '<i class="fa fa-minus" aria-hidden="true"></i>',
        };

        // Zoom Control
        var zoom = L.control.zoom(zoomOptions);
        zoom.addTo(map_single);

        map_single.scrollWheelZoom.disable();

        marker = new L.marker([lat, lng], {
            icon: listeoIcon,
        }).addTo(map_single);

        // Open Street Map
        // -----------------------//
        L.tileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> Contributors',
            maxZoom: 18,
        }).addTo(map_single);

        // Street View Button URL
        $("a#streetView").attr({
            href: "https://www.google.com/maps/search/?api=1&query=" +
                lat +
                "," +
                lng +
                "",
            target: "_blank",
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatBtn = document.querySelector(".send-message-to-owner");

        if (chatBtn) {
            chatBtn.addEventListener("click", function(e) {
                const isAuthenticated = chatBtn.dataset.authenticated === "true";

                if (!isAuthenticated) {
                    e.preventDefault();
                    Toastify({
                        text: "กรุณาเข้าสู่ระบบก่อนใช้งานแชท!",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#f44336",
                        stopOnFocus: true,
                    }).showToast();
                    setTimeout(() => {
                        $.magnificPopup.close();
                    }, 800);
                } else {
                    // Delay to ensure Vue is rendered inside MagnificPopup
                    setTimeout(() => {
                        const app = document.querySelector("#app");
                        if (app && app.__vue_app__) {
                            const vueInstance = app.__vue_app__._instance;
                            if (
                                vueInstance &&
                                vueInstance.subTree &&
                                vueInstance.subTree.component &&
                                vueInstance.subTree.component.refs.chatTextarea
                            ) {
                                vueInstance.subTree.component.refs.chatTextarea.focus();
                            }
                        }
                    }, 100); // Slight delay to ensure DOM is ready
                }
            });
        }

    });
</script>



@endsection