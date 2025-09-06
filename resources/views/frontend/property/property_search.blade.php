
@extends('frontend.frontend_dashboard')
@section('meta')
<title>{{$title}} | baanlist</title>
<meta name="description" content="{{$seo->description}}">
<meta name="keywords" content="{{$seo->keywords}}">

<!-- Open Graph -->
<meta property="og:title" content="{{$title}} | baanlist" />
<meta property="og:description" content="{{$seo->title3}}" />
<meta property="og:image" content="{{ asset('frontend/images/banner.jpg') }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:type" content="website" />

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{$title}} | baanlist" />
<meta name="twitter:description" content="{{$seo->title3}}" />
<meta name="twitter:image" content="{{ asset('frontend/images/banner.jpg') }}" />
@endsection
@section('main')
<link rel="stylesheet" href="{{ asset('frontend/css/swiper.css') }}" />
<link rel="stylesheet" href="{{ asset('frontend/css/searchpage.css') }}" />




<!-- Header Container
================================================== -->
@include('frontend.property.body.header')

<!-- Header Container / End -->
<div class="clearfix"></div>


<!-- Content
================================================== -->
<div class="fs-container">

    <div class="fs-inner-container content">
        <div class="fs-content">

            <!-- Search -->

            <!-- Search -->
            <section class="search">

                <div class="row">
                    <div class="col-md-12">
                        <form id="search-form" method="GET" action="/search">
                            <input type="hidden" id="form-lat" name="lat" value="{{ $lat ?? 13.7563 }}">
                            <input type="hidden" id="form-lon" name="lon" value="{{ $lon ?? 100.5018 }}">
                         
                            <!-- Row With Forms -->
                            <div class="row with-forms">


                                <!-- Main Search Input -->
                                <div class="col-md-6">
                                    
                                    <div class="input-with-icon location">
                                    
                                        <div id="autocomplete-container">
                                            <input id="autocomplete-input" type="text" placeholder="ที่อยู่, พื้นที่ หรือ จังหวัด" autocomplete="off" value="{{ $label ?? '' }}">
                                        </div>
                                        <div id="autocomplete-suggestions" class="suggestions-box suggestions-search-page"></div>
                                        <a href="#" id="search-button" role="button" aria-label="ค้นหา">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                    
                                </div>

                                <!-- Main Search Input -->
                                <div class="col-md-3 margin-bottom-10">
                                    <select id="purpose-select" class="chosen-select"  data-placeholder="ขาย" name="purpose">
                                        @foreach($purposes as $pp)
                                            @if($loop->index == 2)
                                                @break
                                            @endif
                                            <option value="{{ $pp->id }}" {{ $pp->id == $purpose ? 'selected' : '' }}>
                                                {{ $pp->purpose_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Main Search Input -->
                                <div class="col-md-3 margin-bottom-10">
                                    <select id="category-select"  class="chosen-select" data-placeholder="บ้าน" name="category">
                                        @foreach($propertytype as $ptype)
                                            <option value="{{ $ptype->id }}" {{ $ptype->id == $category ? 'selected' : '' }}>
                                                {{ $ptype->type_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <!-- Filters -->
                                <div class="col-fs-12">

                                    <!-- Panel Dropdown -->
                                    <div class="panel-dropdown wide">
                                        <a href="#">ตัวกรอง</a>
                                        <div class="panel-dropdown-content checkboxes">

                                          <!-- Checkboxes -->
                                            @php
                                                $amenitiesChunked = $amenities->chunk(ceil($amenities->count() / 2));
                                            @endphp
                                            <div class="row">
                                                @foreach($amenitiesChunked as $chunk)
                                                    <div class="col-md-6">
                                                        @foreach($chunk as $amen)
                                                            <input
                                                                class="amenity-checkbox"
                                                                id="check-{{ $amen->id }}"
                                                                type="checkbox"
                                                                name="amenities[]"
                                                                value="{{ $amen->id }}"
                                                                {{ in_array($amen->id, request()->input('amenities', [])) ? 'checked' : '' }}
                                                            >
                                                            <label for="check-{{ $amen->id }}">{{ $amen->amenity_name ?? $amen->name }}</label>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Buttons -->
                                            <div class="panel-buttons">
                                                <button class="panel-cancel">ออก</button>
                                                <button class="panel-apply">ตกลง</button>
                                            </div>

                                        </div>
                                    </div>
                                    <!-- Panel Dropdown / End -->

                                    <div class="panel-dropdown">
                                        <a href="#">ช่วงราคา</a>
                                        <div class="panel-dropdown-content">              
                                            <div class="slider-container">
                                                <label for="input-1">เลือกช่วงราคา(บาท)</label>
                                                <div class="histogram" id="histogram"></div>
                                                <div class="slider-track"></div>
                                                <input type="range" min="0" max="{{$absoluteMaxPrice}}" value="{{request('min_price') ?? 1000}}" id="slider-1" oninput="slideOne()" step="1000" name="min_price">
                                                <input type="range" min="0" max="{{$absoluteMaxPrice}}" value="{{request('max_price') ?? $maxPrice}}" id="slider-2" oninput="slideTwo()" step="1000" name="max_price">
                                            </div>
                                            <div class="price-inputs-wrapper">
                                            <div class="price-input">
                                                <label for="input-1">ต่ำสุด</label>
                                                <input type="text" id="input-1" value="{{request('min_price') ?? 1000}}" oninput="updateSliderValue(1)">
                                            </div>
                                            <div class="price-input">
                                                <label for="input-2">สูงสุด</label>
                                                <input type="text" id="input-2" value="{{request('max_price') ?? $maxPrice}}" oninput="updateSliderValue(2)" >
                                            </div>
                                            </div>
                                            <div class="panel-buttons">
                                                <button class="panel-cancel">ออก</button>
                                                <button class="panel-apply" id="price-button">ตกลง</button>
                                            </div>
                                            
                                        </div>
                                    </div>


                                </div>
                                <!-- Filters / End -->

                            </div>
                            <!-- Row With Forms / End -->
                        </form>
                    </div>
                </div>

            </section>
            <!-- Search / End -->

            <!-- Search / End -->


            <section class="listings-container margin-top-20">

                <!-- Sorting / Layout Switcher -->
                <div class="row fs-switcher">


                    <div class="col-md-6">
                        <!-- Showing Results -->
                        <h3 id="listing-label">{{ $label }}</h3>
                        <p id="listing-total">{{ $props->total() }} ประกาศ</p>
                    </div>

                </div>

          

                <!-- Listings -->
                {{-- <div class="row fs-listings">

                    @foreach($props as $item)

                    <!-- Listing Item -->
                    <div class="col-lg-6 col-md-6">
                        <a href="{{ route('property.details', ['id' => $item->id, 'slug' => $item->detail->property_slug]) }}"
                            class="listing-item-container compact" data-marker-id="3">
                            <div class="listing-item swiper mySwiper">

                                <div class="swiper-wrapper">
                                    @forelse($item->galleries as $gallery)
                                    <div class="swiper-slide">
                                        <img src="{{ asset('upload/property/' . $gallery->property_id . '/' . $gallery->filename) }}"
                                            alt="property image">

                                    </div>
                                    @empty
                                    <div class="swiper-slide">
                                        <img src="{{ asset('frontend/images/popular-location-04.jpg') }}"
                                            alt="no image available">
                                    </div>
                                    @endforelse

                                </div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                                <div class="swiper-pagination"></div>
                                <span class="like-icon" data-id="{{ $item->id }}"></span>
                            </div>

                            <div class="listing-item-inner padding-left-10">
                                @if($item->property_status=='2')
                                <span class="price-listing-number">฿{{ addCommas($item->price->rent_price) }} / เดือน</span>
                                @else
                                <span class="price-listing-number">฿{{ addCommas($item->price->sell_price) }}</span>
                                @endif
                                <div class="clear"></div>
                                <span class="bed-bath-number">{{ $item->detail->bedrooms }} ห้องนอน |
                                    {{ $item->detail->bathrooms }} ห้องน้ำ | {{ $item->detail->land_size }} ตร.ม.</span>
                                <p class="listing-location-number">{{ $item->location->subdistrict->name_th }},
                                    {{ $item->location->province->name_th }}
                            </div>

                        </a>

                    </div>
                    <!-- Listing Item / End -->

                    @endforeach







                </div> --}}

                <div class="row fs-listings" id="listings-container">
                    @include('frontend.property.components.listings')
                </div>
                <!-- Listings Container / End -->


                <!-- Pagination Container -->
                {{-- <div class="row fs-listings">
                    <div class="col-md-12">

                        <!-- Pagination -->
                        <div class="clearfix"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Pagination -->
                                <div class="pagination-container margin-top-15 margin-bottom-40">
                                    <nav class="pagination">
                                        {{ $props->appends(request()->query())->links('vendor.pagination.custom') }}
                                        
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <!-- Pagination / End -->

                        <!-- Copyrights -->
                        <div class="copyrights margin-top-0">{{ $setting->copyright ?? '' }}</div>

                    </div>
                </div> --}}
                <!-- Pagination Container / End -->

                <!-- Spinner overlay -->
                    <div id="upload-spinner" class="spinner-container">
                        <div id="spinner-icon" class="spinner"></div>
                        <div id="success-check" class="checkmark">
                            <svg viewBox="0 0 52 52">
                                <path
                                    d="M26 0C11.6 0 0 11.6 0 26s11.6 26 26 26 26-11.6 26-26S40.4 0 26 0zm0 48C13.2 48 4 38.8 4 26S13.2 4 26 4s22 9.2 22 22-9.2 22-22 22zm10.3-29.7L22 32.6l-6.3-6.3-2.8 2.8L22 38.2l17.1-17.1-2.8-2.8z" />
                            </svg>
                        </div>
                    </div>

            </section>

        </div>
    </div>


    <div class="fs-inner-container map-fixed">

        <!-- Map -->
        <div id="map-container">
            <div id="map" data-map-zoom="9" data-map-scroll="true">
                <!-- map goes here -->
            </div>
        </div>

        

    </div>

    <button id="mobile-list-toggle">แสดงรายการ</button>
</div>








<!-- Swiper JS -->
<script src="{{ asset('frontend/scripts/swiper.js') }}"></script>
<script src="{{ asset('frontend/scripts/PropertySearch.js') }}"></script>
<!-- Initialize Swiper -->
<script>
var swiper = new Swiper(".mySwiper", {
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
    },
    simulateTouch: true,

});
</script>


@endsection
@section('scripts')



<script>
    const mapLat = {{ $lat ?? 13.7563 }};
    const mapLon = {{ $lon ?? 100.5018 }};
    let isFirstMove = true;
    let previousCenter = null;
    const MIN_MOVE_DISTANCE = 500; // in meters
    let markers = L.markerClusterGroup({
                spiderfyOnMaxZoom: true,
                showCoverageOnHover: false,
            });
    $(document).ready(function() {
        if (document.getElementById("map") !== null) {
            // Touch Gestures
            var scrollEnabled =
                $("#map").attr("data-map-scroll") == "true" ||
                $(window).width() < 992 ?
                false :
                true;

            var mapOptions = {
                gestureHandling: scrollEnabled,
            };

            // Map Init
            window.map = L.map("map", mapOptions);
            $("#scrollEnabling").hide();

            // ----------------------------------------------- //
            //---- Popup Output Function-----//
            // ----------------------------------------------- //
            function locationData(locationURL, locationImg, locationPrice, locationRoom) {
                return (
                    '<a href="' +
                    locationURL +
                    '" class="leaflet-listing-img-container">' +
                    '<div class="infoBox-close"><i class="fa fa-times"></i></div>' +
                    '<img src="' +
                    locationImg +
                    '" alt="">' +
                    '<div class="leaflet-listing-item-content">' +
                    "<h3>" +
                    locationPrice +
                    "</h3>" +
                    "<span>" +
                    locationRoom +
                    "</span>" +
                    "</div>" +
                    "</a>"
                );
            }

            // ----------------------------------------------- //
            // Dynamic Locations from Backend
            // ----------------------------------------------- //
            @if(isset($maplocations))
            const rawLocations = @json($maplocations);
            var locations = rawLocations.map(loc => {
                return [
                    locationData(loc.url, loc.image, loc.price, loc.room),
                    loc.lat,
                    loc.lng,
                    loc.index,
                    loc.icon
                ];
            });
            @else
            var locations = []; // fallback if no data
            @endif

            // ----------------------------------------------- //
            // Map Tile Provider (OpenStreetMap)
            // ----------------------------------------------- //
            L.tileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> Contributors',
                maxZoom: 18,
            }).addTo(map);

            // ----------------------------------------------- //
            // Marker Cluster Setup
            // ----------------------------------------------- //
            

            let markerArray = [];

            for (let i = 0; i < locations.length; i++) {
                let listeoIcon = L.divIcon({
                    iconAnchor: [20, 51],
                    popupAnchor: [0, -51],
                    className: "listeo-marker-icon",
                    html: '<div class="marker-container">' +
                        '<div class="marker-card">' +
                        '<div class="front face">' +
                        locations[i][4] +
                        "</div>" +
                        '<div class="back face">' +
                        locations[i][4] +
                        "</div>" +
                        '<div class="marker-arrow"></div>' +
                        "</div>" +
                        "</div>",
                });

                let popupOptions = {
                    maxWidth: "270",
                    className: "leaflet-infoBox",
                };

                let marker = new L.marker([locations[i][1], locations[i][2]], {
                    icon: listeoIcon,
                }).bindPopup(locations[i][0], popupOptions);

                markers.addLayer(marker);
            }

            map.addLayer(markers);
            markerArray.push(markers);

            if (markerArray.length > 0) {
                //map.fitBounds(L.featureGroup(markerArray).getBounds().pad(0.2));
                if (locations.length > 0) {
                    map.fitBounds(L.featureGroup(markerArray).getBounds().pad(0.2));
                } else {
                    // Default center (Bangkok or user input)
                   map.setView([mapLat, mapLon], 9);
                }
            }
            else {
                    // Default center (Bangkok or user input)
                    map.setView([13.7563, 100.5018], 9);
                }

            // Custom Zoom Control
            map.removeControl(map.zoomControl);

            let zoomOptions = {
                zoomInText: '<i class="fa fa-plus" aria-hidden="true"></i>',
                zoomOutText: '<i class="fa fa-minus" aria-hidden="true"></i>',
            };

            let zoom = L.control.zoom(zoomOptions);
            zoom.addTo(map);

            //-------------------------------Map Moved-----------------------------
            let previousCenter = map.getCenter();
           

            map.on('moveend', function () {
                const currentCenter = map.getCenter();
                const distanceMoved = map.distance(previousCenter, currentCenter);

                if (distanceMoved > MIN_MOVE_DISTANCE) {
                    previousCenter = currentCenter;

                    // Update hidden inputs
                    document.getElementById('form-lat').value = currentCenter.lat;
                    document.getElementById('form-lon').value = currentCenter.lng;

                  

                    // Call AJAX submission
                    fetchSearchResults();
                    isFirstMove = false;// prevents duplicate fitBounds
                }
            });
//-------------------------------End Map Moved----------------------------------
    
            
        } // end if map



        
    });


    // --------------------- AJAX ------------------------

    function fetchSearchResults() {
    const form = document.getElementById('search-form');
    const formData = new FormData(form);

    // Add current map center lat/lon from your hidden inputs
    formData.set('lat', document.getElementById('form-lat').value);
    formData.set('lon', document.getElementById('form-lon').value);
   
   


    // Show spinner
    document.getElementById('upload-spinner').style.display = 'block';

    fetch('/search?' + new URLSearchParams(formData), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}', // add if necessary, else remove
        },
        method: 'GET',
        cache: "no-cache",
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('upload-spinner').style.display = 'none';

        // Update listings
        document.getElementById('listings-container').innerHTML = data.listings_html; 

        // Update map markers
        updateMapMarkers(data.maplocations);

       
       // Update label and total
        if (data.label) document.getElementById('listing-label').textContent = data.label;
        if (data.total) document.getElementById('listing-total').textContent = data.total + ' ประกาศ';

        initSwipers();
        // Optionally reinit sliders or other UI components here
    })
    .catch(err => {
        console.error('Error fetching search results:', err);
        document.getElementById('upload-spinner').style.display = 'none';
    });
}

function updateMapMarkers(locations) {
    // Remove all previous markers from the cluster
    markers.clearLayers();

    locations.forEach(loc => {
        let listeoIcon = L.divIcon({
            iconAnchor: [20, 51],
            popupAnchor: [0, -51],
            className: "listeo-marker-icon",
            html: '<div class="marker-container">' +
                  '<div class="marker-card">' +
                  '<div class="front face">' + loc.icon + '</div>' +
                  '<div class="back face">' + loc.icon + '</div>' +
                  '<div class="marker-arrow"></div>' +
                  '</div></div>',
        });

        let popupOptions = { maxWidth: "270", className: "leaflet-infoBox" };
        let popupContent = '<a href="' + loc.url + '" class="leaflet-listing-img-container">' +
                           '<div class="infoBox-close"><i class="fa fa-times"></i></div>' +
                           '<img src="' + loc.image + '" alt="">' +
                           '<div class="leaflet-listing-item-content">' +
                           '<h3>' + loc.price + '</h3>' +
                           '<span>' + loc.room + '</span>' +
                           '</div></a>';

        let marker = L.marker([loc.lat, loc.lng], { icon: listeoIcon })
                      .bindPopup(popupContent, popupOptions);

        markers.addLayer(marker);
    });

       

    // ✅ Do NOT re-add cluster to map; it's already added during initialization
    // map.addLayer(markers); <-- REMOVE THIS
}



function initSwipers() {
    document.querySelectorAll('.mySwiper').forEach(swiperEl => {
        new Swiper(swiperEl, {
            navigation: {
                nextEl: swiperEl.querySelector('.swiper-button-next'),
                prevEl: swiperEl.querySelector('.swiper-button-prev'),
            },
            pagination: {
                el: swiperEl.querySelector('.swiper-pagination'),
                dynamicBullets: true,
            },
            simulateTouch: true,
        });
    });
}


document.addEventListener('click', function(event) {
    if(event.target.closest('.pagination a')) {
        event.preventDefault();

        let url = event.target.closest('a').href;
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById('listings-container').innerHTML = data.listings_html;
            if (data.maplocations) {
                updateMapMarkers(data.maplocations);
            } else {
                console.warn('No map locations returned');
            }
        })
        .catch(console.error);
    }
});

   
</script>


<script>
    
    $(document).ready(function () {
        // Chosen initialization
        $('.chosen-select').chosen();
        // Attach change handler using jQuery for Chosen
        $('#purpose-select, #category-select').on('change', function () {
            fetchSearchResults(); // Your existing function
        });

        $('#purpose-select, #purpose-select').on('change', function () {
            fetchSearchResults(); // Your existing function
        });

    });
</script>




@endsection