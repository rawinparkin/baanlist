@extends('frontend.frontend_dashboard')


@section('meta')
<title>Setting - baanlist</title>
<style>
    .blog-post-img {
        width: 200px;
        height: 200px;
        object-fit: cover;
        aspect-ratio: 1 / 1;
        border-radius: 10px;
    }
</style>
@endsection
@section('main')

<!-- Header Container
================================================== -->
@include('admin.body.header')
<!-- Header Container / End -->

<!-- Dashboard -->
<div id="dashboard">

    <!-- Navigation
================================================== -->

    @include('admin.body.sidebar')

    <!-- Content
	================================================== -->
    <div class="dashboard-content">

        <!-- Titlebar -->
        <div id="titlebar">
            <div class="row">
                <div class="col-md-12">
                    <h2>Setting</h2>
                    <!-- Breadcrumbs -->
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Profile -->
            <div class="col-lg-12 col-md-12">
                <div class="dashboard-list-box margin-top-10">
                    <h4 class="gray">กรอกข้อมูล</h4>
                    <div class="dashboard-list-box-static">

                        <form id="typeForm" action="{{ route('update.site.setting') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$setting->id}}">

                            <div class="row with-forms">
                                <!-- Logo -->
                                <div class="col-md-8 mb-3">
                                    <h5>โลโก้</h5>@if($setting->logo)
                                    <img src="{{ asset('upload/logo/' . $setting->logo) }}" alt="Logo" height="60">
                                    @endif
                                    <input type="file" name="logo" class="form-control">

                                </div>

                                <!-- Logo -->
                                <div class="col-md-8 mb-3">
                                    <h5>favicon</h5> @if($setting->favicon)
                                    <img src="{{ asset('upload/favicon/' . $setting->favicon) }}" alt="favicon"
                                        height="60">
                                    @endif
                                    <input type="file" name="favicon" class="form-control">

                                </div>

                                <!-- Support Phone -->
                                <div class="col-md-8 mb-3">
                                    <h5>เบอร์โทรบริษัท</h5>
                                    <input type="text" name="support_phone" class="form-control"
                                        value="{{ $setting->support_phone }}">
                                </div>

                                <!-- Company Address -->
                                <div class="col-md-8 mb-3">
                                    <h5>ที่อยู่บริษัท</h5>
                                    <input type="text" name="company_address" value="{{ $setting->company_address }}">
                                </div>

                                <!-- Company Latitude -->
                                <div class="col-md-8 mb-3">
                                    <h5>ละติจูด (Latitude)</h5>
                                    <input type="text" name="company_lat" class="form-control"
                                        value="{{ $setting->company_lat }}">
                                </div>

                                <!-- Company Longitude -->
                                <div class="col-md-8 mb-3">
                                    <h5>ลองจิจูด (Longitude)</h5>
                                    <input type="text" name="company_lon" class="form-control"
                                        value="{{ $setting->company_lon }}">
                                </div>

                                <!-- Email -->
                                <div class="col-md-8 mb-3">
                                    <h5>อีเมล</h5>
                                    <input type="email" name="email" class="form-control" value="{{ $setting->email }}">
                                </div>

                                <!-- Facebook -->
                                <div class="col-md-8 mb-3">
                                    <h5>Facebook</h5>
                                    <input type="text" name="facebook" class="form-control"
                                        value="{{ $setting->facebook }}">
                                </div>

                                <!-- Twitter -->
                                <div class="col-md-8 mb-3">
                                    <h5>Pinterest</h5>
                                    <input type="text" name="pinterest" class="form-control"
                                        value="{{ $setting->pinterest }}">
                                </div>

                                <!-- Instagram -->
                                <div class="col-md-8 mb-3">
                                    <h5>Instagram</h5>
                                    <input type="text" name="instagram" class="form-control"
                                        value="{{ $setting->instagram }}">
                                </div>

                                <!-- About Footer -->
                                <div class="col-md-8 mb-3">
                                    <h5>เกี่ยวกับบริษัท Footer</h5>
                                    <textarea name="about_footer" class="form-control"
                                        rows="4">{{ $setting->about_footer }}</textarea>
                                </div>

                                <!-- Policy -->
                                <div class="col-md-8 mb-3">
                                    <h5>นโยบายความเป็นส่วนตัว (Privacy Policy)</h5>

                                    <textarea name="policy" class="WYSIWYG"
                                        rows="4">{{ $setting->policy }}</textarea>
                                </div>

                                <!-- Terms of Service -->
                                <div class="col-md-8 mb-3">
                                    <h5>ข้อตกลงการใช้งาน (Terms of Service)</h5>
                                    <textarea name="terms_of_service" class="WYSIWYG"
                                        rows="4">{{ $setting->terms_of_service }}</textarea>
                                </div>

                                <!-- Copyright -->
                                <div class="col-md-8 mb-3">
                                    <h5>ลิขสิทธิ์ (Copyright)</h5>
                                    <textarea name="copyright" class="form-control"
                                        rows="4">{{ $setting->copyright }}</textarea>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-8">
                                    <button class="button preview margin-top-15" type="submit"><i
                                            class="im im-icon-Edit"></i>
                                        บันทึก</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>



    </div>
    <!-- Content / End -->



</div>
<!-- Dashboard / End -->



@endsection
@section('scripts')


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

@endsection