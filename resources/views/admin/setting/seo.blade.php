@extends('frontend.frontend_dashboard')


@section('meta')
<title>SEO & Adsense - baanlist</title>
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
                    <h2>SEO & Adsense</h2>
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

                        <form id="typeForm" action="{{ route('update.seo.setting') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$setting->id}}">

                            <div class="row with-forms">
                            
                                <!-- description -->
                                <div class="col-md-8 mb-3">
                                    <h5>description</h5>
                                    <input type="text" name="description" class="form-control"
                                        value="{{ $setting->description }}">
                                </div>

                                <!-- Company Address -->
                                <div class="col-md-8 mb-3">
                                    <h5>keywords</h5>
                                    <input type="text" name="keywords" value="{{ $setting->keywords }}">
                                </div>

                                <!-- title1 -->
                                <div class="col-md-8 mb-3">
                                    <h5>title1(index page)</h5>
                                    <input type="text" name="title1" class="form-control"
                                        value="{{ $setting->title1 }}">
                                </div>

                                <!-- title2 -->
                                <div class="col-md-8 mb-3">
                                    <h5>title2(social)</h5>
                                    <input type="text" name="title2" class="form-control"
                                        value="{{ $setting->title2 }}">
                                </div>

                                <!-- title3 -->
                                <div class="col-md-8 mb-3">
                                    <h5>description(social)</h5>
                                    <input type="text" name="title3" class="form-control" value="{{ $setting->title3 }}">
                                </div>

                                
                                <!-- adsense -->
                                <div class="col-md-8 mb-3">
                                    <h5>adsense</h5>
                                    <textarea name="adsense" class="form-control"
                                        rows="4">{{ $setting->adsense }}</textarea>
                                </div>

                                <!-- Policy -->
                                <div class="col-md-8 mb-3">
                                    <h5>adsense_headtag</h5>

                                    <textarea name="adsense_headtag" class="form-control"
                                        rows="4">{{ $setting->adsense_headtag }}</textarea>
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




@endsection