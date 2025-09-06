@extends('frontend.frontend_dashboard')
@section('meta')
<title>ติดต่อ | baanlist</title>
<meta name="description" content="{{$seo->description}}">
<meta name="keywords" content="{{$seo->keywords}}">

<!-- Open Graph -->
<meta property="og:title" content="ติดต่อ | {{$seo->title2}}" />
<meta property="og:description" content="{{$seo->title3}}" />
<meta property="og:image" content="{{ asset('frontend/images/banner.jpg') }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:type" content="website" />

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="ติดต่อ | {{$seo->title2}}" />
<meta name="twitter:description" content="{{$seo->title3}}" />
<meta name="twitter:image" content="{{ asset('frontend/images/banner.jpg') }}" />

<link rel="stylesheet" href="{{ asset('frontend/css/add2.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
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





<!-- Content
================================================== -->

<!-- Map Container -->
<div class="contact-map margin-bottom-60">

    <!-- Google Maps -->
    <div id="singleListingMap-container">
        <div id="singleListingMap" data-latitude="12.908026803531408" data-longitude="100.90881444466486"
            data-map-icon="sl sl-icon-home"></div>
        <a href="#" id="streetView">Street View</a>
    </div>
    <!-- Google Maps / End -->

    <!-- Office -->
    <div class="address-box-container">
        <div class="address-container" data-background-image="asset('frontend/images/our-office.jpg')">
            <div class="office-address">
                <h3>ที่ตั้งของเรา</h3>
                <ul>
                    <li>{{$setting->company_address}}, </li>
                    <li>{{$setting->company_address2}}, </li>
                    <li>{{$setting->company_address3}}</li>
                    <!-- <li>โทร {{$setting->support_phone}} </li> -->
                </ul>
            </div>
        </div>
    </div>
    <!-- Office / End -->

</div>
<div class="clearfix"></div>
<!-- Map Container / End -->


<!-- Container / Start -->
<div class="container margin-bottom-70">

    <div class="row">

        <!-- Contact Details -->
        <div class="col-md-4">

            <h4 class="headline margin-bottom-30">ติดต่อเราได้ที่นี่</h4>

            <!-- Contact Details -->
            <div class="sidebar-textbox">
                <p>เราพร้อมให้บริการและตอบคำถามของคุณผ่านระบบออนไลน์ทุกช่องทาง ไม่ว่าจะเป็นเรื่องการใช้งาน การลงประกาศ
                    หรือข้อสงสัยอื่น ๆ ทีมงานของเรายินดีให้ความช่วยเหลือ</p>

                <ul class="contact-details">
                    <!-- <li><i class="im im-icon-Phone-2"></i> <strong>Phone:</strong> <span>(123) 123-456 </span></li> -->
                    <!-- <li><i class="im im-icon-Fax"></i> <strong>Fax:</strong> <span>(123) 123-456 </span></li> -->
                    <li><i class="bi bi-line"></i><strong>Line:</strong> <span><a
                                href="#">{{$setting->line}}</a></span></li>
                    <li><i class="im im-icon-Globe"></i> <strong>Web:</strong> <span><a
                                href="#">www.baanlist.com</a></span></li>
                    <li><i class="im im-icon-Envelope"></i> <strong>E-Mail:</strong> <span><a
                                href="#">{{$setting->email}}</a></span></li>
                </ul>
            </div>

        </div>

        <!-- Contact Form -->
        <div class="col-md-8">

            <section id="contact">
                <h4 class="headline margin-bottom-35">กล่องข้อความ</h4>

                <div id="contact-message"></div>
                @auth
                <form method="post" action="{{ route('store.contact.box') }}" id="typeForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div>
                                <input name="name" type="text" placeholder="ชื่อ" value="{{ auth()->user()->name }}"
                                    autocomplete="off" required />
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div>
                                <input name="email" type="email" style="background-color: #c4c4c4;" placeholder="อีเมล์"
                                    pattern="^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$"
                                    value="{{ old('email', auth()->user()->email ?? '') }}" readonly />
                            </div>
                        </div>
                    </div>

                    <div>
                        <input name="subject" type="text" placeholder="หัวข้อ" autocomplete="off" />
                    </div>

                    <div>
                        <textarea name="message" cols="40" rows="3" placeholder="ข้อความ" spellcheck="true"
                            required="required"></textarea>
                    </div>

                    <input type="submit" class="submit button" value="ส่งข้อความ" />

                </form>
                @else
                <div class="alert alert-warning">
                    กรุณา <a href="#sign-in-dialog">เข้าสู่ระบบ</a> เพื่อส่งข้อความ
                </div>
                @endauth
            </section>
        </div>
        <!-- Contact Form / End -->

    </div>

</div>
<!-- Container / End -->








<!-- Footer
================================================== -->
@include('frontend.home.footer')
<!-- Footer / End -->

<!-- Back To Top Button -->
<div id="backtotop"><a href="#"></a></div>








@endsection



@section('scripts')
@vite(['resources/js/app.js'])



@if(session('success'))
<script>
    Toastify({
        text: "{{ session('success') }}",
        duration: 3000,
        gravity: "top",
        position: "right",
        style: {
            background: "#84c015"
        },
    }).showToast();
</script>
@endif


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
            zoom: 17,
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


@endsection