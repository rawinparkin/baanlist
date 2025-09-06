@extends('frontend.frontend_dashboard')

@section('meta')
<title>แก้ไขประกาศ | baanlist</title>
<meta name="description" content="{{$seo->description}}">
<meta name="keywords" content="{{$seo->keywords}}">

<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- CSS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<link rel="stylesheet" href="{{ asset('frontend/css/Listing.css') }}">
<!-- Choices.js CSS -->
<link href="{{ asset('frontend/css/Choices.css') }}" rel="stylesheet" />

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
                    <h2>แก้ไขประกาศ 
                        @if($property->status == 0)
                        <span style="color:red;">ซ่อนประกาศ</span> 
                        @endif
                    </h2>

                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs">
                        <ul>
                            <li><a href="{{ route('home.index') }}">หน้าหลัก</a></li>
                            <li><a href="{{ route('user.my.listing') }}">ประกาศของฉัน</a></li>
                            <li>แก้ไขประกาศ</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        

        <div class="row">
            <div class="col-lg-12">

                <form method="POST" action="{{ route('update.property') }}" id="myForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="property_id" name="property_id" value="{{ $property->id }}">


                    <div id="add-listing">

                        <!-- Section -->
                        <div class="add-listing-section">

                            <!-- Headline -->
                            <div class="add-listing-headline">
                                <h3><i class="sl sl-icon-doc"></i> ข้อมูลทั่วไป</h3>
                                {{-- <label class="switch">
                                    <input type="checkbox" name="status" id="status" {{$property->status == 1 ? 'checked' : ''}}>
                                    <span class="slider round"></span>
                                </label> --}}
                            </div>

                            <!-- Title -->
                            <div class="row with-forms">
                                <div class="col-md-12">
                                    <h5>ชื่อประกาศ <i class="tip"
                                            data-tip-content="ex.ขายบ้าน 2 ห้องนอน 2 ห้องน้ำ ใกล้แหล่งชุมชน มีร้านสะดวกซื้อหน้าปากซอย"></i>
                                    </h5>
                                    <input class="search-field" type="text" name="property_name" id="property_name"
                                        value="{{ $property->detail->property_name }}" />
                                </div>
                            </div>

                            <!-- Row -->
                            <div class="row with-forms">

                                <!-- Keywords -->
                                <div class="col-md-6">
                                    <h5>จุดประสงค์</h5>
                                    <select class="chosen-select" name="property_status" id="property_status">
                                        <option value="">เลือกจุดประสงค์</option> <!-- no 'disabled' -->
                                        @foreach($purposes as $pp)
                                        <option value="{{ $pp->id }}"
                                            {{ $pp->id == $property->property_status ? 'selected' : '' }}>
                                            {{ $pp->purpose_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Category -->
                                <div class="col-md-6">
                                    <h5>ประเภท</h5>
                                    <select class="chosen-select" name="property_type_id" id="property_type_id">
                                        <option value="">เลือกประเภทอสังหาฯ</option> <!-- no 'disabled' -->
                                        @foreach($propertytype as $ptype)
                                        <option value="{{ $ptype->id }}"
                                            {{ $ptype->id == $property->property_type_id ? 'selected' : '' }}>
                                            {{ $ptype->type_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <!-- Row / End -->

                        </div>
                        <!-- Section / End -->

                        <!-- Section -->
                        <div class="add-listing-section margin-top-45">

                            <!-- Headline -->
                            <div class="add-listing-headline">
                                <h3><i class="sl sl-icon-location"></i> ที่ตั้งของอสังหาฯ</h3>
                            </div>

                            <div class="submit-section">

                                <!-- Row -->
                                <div class="row with-forms">
                                    <div id="search-fields" style="display: none;">
                                        <div class=" col-md-12">
                                            <h5>ค้นหาที่อยู่</h5>
                                            <input type="text" name="search_place" id="search_place"
                                                placeholder="ex.บ้านคลอง, ทุ่งสง, แม่ริม, บางบัวทอง หรือ รหัสไปรษณีย์">
                                        </div>

                                        <div class="col-md-2">
                                            <a class="button border" id="check_place">
                                                ค้นหา
                                            </a>
                                        </div>
                                    </div>
                                    <div style="text-align: right;" id="edit_button_container">
                                        <a class="button border" id="edit_search">แก้ไขการค้นหาที่อยู่</a>
                                    </div>
                                    <div class="leaflet-control"></div>


                                    <div class="address-fields" id="address-fields">

                                        <div class="col-md-9">
                                            <h5>บ้านเลขที่ ซอย ถนน</h5>
                                            <input type="text" name="property_address" placeholder="ex.77 ซอยสวัสดี"
                                                value="{{$property->location->property_address }}">
                                        </div>
                                        <div class="col-md-3">
                                            <h5>รหัสไปรษณีย์</h5>
                                            <input type="text" name="zip_code" id="zip_code"
                                                value="{{$property->location->postal_code }}">
                                        </div>
                                        <div class="leaflet-control"></div>
                                        <div class="col-md-4">
                                            <h5>จังหวัด</h5>
                                            <select name="province_name" id="province_name">
                                                <option value="">เลือกจังหวัด</option>
                                                @foreach($provinces as $p)
                                                <option value="{{ $p->id }}"
                                                    {{ $p->id == $property->location->province_id ? 'selected' : '' }}>
                                                    {{ $p->name_th }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <h5>อำเภอ/เขต</h5>
                                            <select name="district_name" id="district_name">
                                                <option value="">เลือกอำเภอ/เขต</option>
                                                @foreach($districts as $d)
                                                <option value="{{ $d->id }}"
                                                    {{ $d->id == $property->location->district_id ? 'selected' : '' }}>
                                                    {{ $d->name_th }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <h5>ตำบล/แขวง</h5>
                                            <!-- Your Select Dropdown -->
                                            <select name="sub_district_name" id="sub_district_name">
                                                <option value="">เลือกตำบล/แขวง</option> <!-- no 'disabled' -->
                                                @foreach($subdistricts as $sd)
                                                <option value="{{ $sd->id }}"
                                                    {{ $sd->id == $property->location->sub_district_id ? 'selected' : '' }}>
                                                    {{ $sd->name_th }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>


                                    </div>

                                    <input type="hidden" name="lat" id="lat" value="{{ $property->location->lat }}">
                                    <input type="hidden" name="lon" id="lon" value="{{ $property->location->lon }}">
                                    <input type="hidden" name="zoom" id="zoom"
                                        value="{{ $property->location->zoom_level }}">

                                    <div class="col-md-12 margin-top-20">
                                        <!-- Map -->
                                        <div id="map-container">
                                            <div id="map">
                                                <!-- map goes here -->
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <!-- Row / End -->

                            </div>
                        </div>
                        <!-- Section / End -->


                        <!-- Section -->
                        <div class="add-listing-section margin-top-45">

                            <!-- Headline -->
                            <div class="add-listing-headline">
                                <h3><i class="sl sl-icon-picture"></i> รูปภาพ</h3>
                            </div>

                            <!-- Dropzone -->
                            <div class="submit-section">
                                <div class="notification closeable notice">
                                    <p class="description" id="_gallery-description">คลิกที่รูปภาพที่อัปโหลดแล้วเพื่อตั้งให้เป็นรูปปก (เครื่องหมายรูปดาว)
                                        ลากและวางภาพเพื่อจัดลำดับภาพใหม่ในแกลเลอรี (สูงสุด 14 รูป)</p>
                                </div>


                                <!-- Spinner overlay -->
                                <div id="upload-spinner" class="spinner-container">
                                    <div id="spinner-icon" class="spinner"></div>
                                    <div id="upload-progress-text" style="text-align:center; font-size: 16px; margin-top: 6px;"></div>
                                    <div id="success-check" class="checkmark">
                                        
                                        <svg viewBox="0 0 52 52">
                                            <path
                                                d="M26 0C11.6 0 0 11.6 0 26s11.6 26 26 26 26-11.6 26-26S40.4 0 26 0zm0 48C13.2 48 4 38.8 4 26S13.2 4 26 4s22 9.2 22 22-9.2 22-22 22zm10.3-29.7L22 32.6l-6.3-6.3-2.8 2.8L22 38.2l17.1-17.1-2.8-2.8z" />
                                        </svg>
                                    </div>
                                </div>

                                <!-- Gallery display -->
                                <div id="uploaded-gallery" class="dropzone"></div>

                                <!-- Confirm dialog -->
                                <div id="md-dialog-backdrop" class="md-dialog-backdrop">
                                    <div class="md-dialog" role="alertdialog" aria-modal="true">
                                        <div class="md-dialog__header">Confirm</div>
                                        <div class="md-dialog__body"></div>
                                        <div class="md-dialog__actions">
                                            <button id="md-btn-cancel" type="button"
                                                class="md-dialog__button">Cancel</button>
                                            <button id="md-btn-confirm" type="button"
                                                class="md-dialog__button">Delete</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden file input -->
                                <input type="file" id="add-files-input" multiple accept="image/*" hidden>

                                <!-- Add more photos -->
                                <button type="button" id="add-more-photos" style="margin-top: 20px;">
                                    <i class="sl sl-icon-plus"></i> เพิ่มรูป
                                </button>




                            </div>

                        </div>
                        <!-- Section / End -->

                        <!-- Section -->
                        <div class="add-listing-section margin-top-45">

                            <!-- Headline -->
                            <div class="add-listing-headline">
                                <h3><i class="sl sl-icon-docs"></i> รายละเอียด</h3>
                            </div>

                            <!-- Description -->
                            <div class="form">
                                <h5>คำบรรยาย</h5>
                                <textarea class="WYSIWYG" name="long_descp" cols="40" rows="3" id="summary"
                                    spellcheck="true"
                                    placeholder="ให้รายละเอียดเกี่ยวกับประกาศ และอย่าลึมระบุว่ามีอะไรที่โดดเด่น อยู่ใกล้ตลาด/โรงเรียน, เดินทางใกล้/ไกลแค่ไหนจากสถานที่สำคัญ">
                                {{ $property->detail->long_descp }} </textarea>
                            </div>

                            <!-- Row -->
                            <div id="room-fields" class="row with-forms">

                                <!-- Bed -->
                                <div class="col-md-6">
                                    <h5>จำนวนห้องนอน </h5>
                                    <select class="chosen-select" name="bedrooms">
                                        <option value="">เลือกจำนวนห้องนอน</option> <!-- no 'disabled' -->
                                        @for ($i = 1; $i <= 10; $i++) <option value="{{ $i }}"
                                            {{ isset($property->detail) && $i == $property->detail->bedrooms ? 'selected' : '' }}>
                                            {{ $i }}
                                            </option>
                                            @endfor
                                    </select>
                                </div>

                                <!-- Bath -->
                                <div class="col-md-6">
                                    <h5>จำนวนห้องน้ำ </h5>
                                    <select class="chosen-select" name="bathrooms">
                                        <option value="">เลือกจำนวนห้องน้ำ</option> <!-- no 'disabled' -->
                                        @for ($i = 1; $i <= 10; $i++) <option value="{{ $i }}"
                                            {{ isset($property->detail) && $i == $property->detail->bathrooms ? 'selected' : '' }}>
                                            {{ $i }}
                                            </option>
                                            @endfor

                                    </select>
                                </div>


                            </div>
                            <!-- Row / End -->





                            <!-- Row -->
                            <div class="row with-forms margin-top-10">

                                <!-- Land  -->
                                <div class="col-md-4">
                                    <h5>จำนวนพื้นที่ดิน <span>(ตร.ม.)</span></h5>
                                    <input type="text" name="land_size" id="land_size"
                                        value="{{ $property->detail->land_size }}" required>
                                </div>

                                <!-- Usage Area -->
                                <div class="col-md-4">
                                    <h5>จำนวนพื้นที่ใช้สอย <span>(ตร.ม.)</span></h5>
                                    <input type="text" name="usage_size" id="usage_size"
                                        value="{{ $property->detail->usage_size }}">
                                </div>

                                <!-- Build -->
                                <div class="col-md-4">
                                    <h5>สร้างปี พ.ศ. <span>(optional)</span></h5>
                                    <input type="text" name="property_built_year" id="property_built_year"
                                        value="{{ $property->property_built_year }}">
                                </div>

                            </div>
                            <!-- Row / End -->


                            <!-- Checkboxes -->
                            <h5 class="margin-top-10 margin-bottom-10">สิ่งอำนวยความสะดวก <span>(optional)</span></h5>
                            <div class="checkboxes in-row margin-bottom-20">

                                @foreach($amenities as $ameni)
                                <input id="check-{{ $ameni->id }}" type="checkbox" name="amenities_id[]"
                                    value="{{ $ameni->id }}"
                                    {{ in_array($ameni->id, $selectedAmenities) ? 'checked' : '' }}>
                                <label for="check-{{ $ameni->id }}">{{ $ameni->amenity_name }}</label>
                                @endforeach

                            </div>
                            <!-- Checkboxes / End -->

                        </div>
                        <!-- Section / End -->

                        <!-- Section -->
                        <div class="add-listing-section margin-top-45">

                            <!-- Headline -->
                            <div class="add-listing-headline">
                                <h3><i class="sl sl-icon-tag"></i> ราคา</h3>
                            </div>

                            <!-- Listing Options -->
                            <div class="row with-forms">
                                <div class="col-md-12">

                                    <label class="checkbox-container col-md-6">
                                        <input type="checkbox" id="forSale"
                                            {{ ($property->price->sell_price !== null && $property->price->sell_price != '0') ? 'checked' : '' }} />
                                        <div class="checkbox-card">
                                            <h4><i class="im im-icon-Money-2"></i></h4> ขาย
                                        </div>
                                    </label>
                                    <!-- Rent Option -->
                                    <label class="checkbox-container col-md-6">
                                        <input type="checkbox" id="forRent"
                                            {{ ($property->price->rent_price !== null && $property->price->rent_price != '0') ? 'checked' : '' }} />
                                        <div class="checkbox-card">
                                            <h4><i class="im im-icon-Calendar-4"></i></h4> ให้เช่า
                                        </div>
                                    </label>

                                </div>
                            </div>

                            <!-- Price Inputs -->
                            <div class="row with-forms">
                                <div class="col-md-12">
                                    <div class="col-md-6">
                                        <label for="salePrice">ราคาขาย(บาท)</label>
                                        <input type="text" id="salePrice" name="sell_price" placeholder="ex.1,000,000"
                                            value="{{ ($property->price->sell_price !== null && $property->price->sell_price != '0') ? $property->price->sell_price : '' }}"
                                            {{ ($property->price->sell_price === null || $property->price->sell_price == '0') ? 'disabled' : '' }} />
                                    </div>

                                    <div class="col-md-6">
                                        <label for="rentPrice">รายเดือน(บาท)</label>
                                        <input type="text" id="rentPrice" name="rent_price" placeholder="ex.5,000"
                                            value="{{ ($property->price->rent_price !== null && $property->price->rent_price != '0') ? $property->price->rent_price : '' }}"
                                            {{ ($property->price->rent_price === null || $property->price->rent_price == '0') ? 'disabled' : '' }} />
                                    </div>

                                </div>
                            </div>


                        </div>
                        <!-- Section / End -->

                        <button id="preview" class="button preview" type="submit" disabled>
                            บันทึกการแก้ไข
                            <i class="fa fa-arrow-circle-right"></i>
                        </button>

                    </div>

                </form>
            </div>

            <!-- Copyrights -->
            <div class="col-md-12">
                <div class="copyrights">{{$setting->copyright}}</div>
            </div>

        </div>

    </div>
    <!-- Content / End -->



</div>
<!-- Dashboard / End -->


@endsection
@section('scripts')
<!-- Dropzone & Sortable -->


<script src="{{ asset('frontend/scripts/Sortable.min.js')}}"></script>
<!-- DropZone | Documentation: http://dropzonejs.com -->
<script>
    const initialGallery = JSON.parse('{!! json_encode($gallery) !!}');
</script>
<script type="text/javascript" src="{{ asset('frontend/scripts/EditUpload.js') }}"></script>
<!-- JS Listing  -->
<script type="text/javascript" src="{{ asset('frontend/scripts/Listing.js') }}"></script>
<!-- Map  -->
<script type="text/javascript">
    const initialLat = parseFloat(document.getElementById("lat").value) || 13.736717;
    const initialLon = parseFloat(document.getElementById("lon").value) || 100.523186;
    const initialZoom = parseFloat(document.getElementById("zoom").value) || 13;
</script>
<script type="text/javascript" src="{{ asset('frontend/scripts/MapListing.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>


<!-- Tinymce Text Editor  -->
<script type="text/javascript" src="{{ asset('frontend/vendor/tinymce/tinymce.min.js') }}"></script>
<script>
    tinymce.init({
        selector: 'textarea.WYSIWYG',
        height: 300,
        menubar: false,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor',
            'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | formatselect | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat | help',
        license_key: 'gpl',
        statusbar: false,
        content_css: [
            '//www.tiny.cloud/css/codepen.min.css'
        ],

        valid_elements: 'a[href|target=_blank],strong/b,em/i,br,ul,ol,li,p,span[style],img[src|alt|width|height]',

        setup: function(editor) {
            editor.on('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault(); // stop default Enter behavior
                    editor.execCommand('InsertLineBreak'); // insert <br>
                }
            });
        }
    });
</script>

<script>
    document.getElementById('status').addEventListener('change', function () {
        const status = this.checked;

        Toastify({
            text: status ? "แสดงประกาศ" : "ซ่อนประกาศ",
            duration: 3000,
            gravity: "top",
            position: "right",
            style: {
                background: status ? "#4CAF50" : "#f44336"
            },
        }).showToast();
    });
</script>



@endsection