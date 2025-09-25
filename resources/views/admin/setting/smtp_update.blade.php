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
                    <h2>Setting SMTP</h2>
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

                        <form id="typeForm" action="{{ route('update.smpt.setting') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id" value="{{$setting->id}}">

                            <div class="row with-forms">
                                <!-- SMTP Name -->
                                <div class="col-md-8 mb-3">
                                    <h5>ชื่อการตั้งค่า SMTP</h5>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ $setting->name ?? '' }}">
                                </div>

                                <!-- Mailer -->
                                <div class="col-md-8 mb-3">
                                    <h5>Mailer</h5>
                                    <input type="text" name="mailer" class="form-control"
                                        value="{{ $setting->mailer ?? '' }}" placeholder="smtp, mailgun, etc.">
                                </div>

                                <!-- Host -->
                                <div class="col-md-8 mb-3">
                                    <h5>Host</h5>
                                    <input type="text" name="host" class="form-control"
                                        value="{{ $setting->host ?? '' }}">
                                </div>

                                <!-- Port -->
                                <div class="col-md-8 mb-3">
                                    <h5>Port</h5>
                                    <input type="text" name="port" class="form-control"
                                        value="{{ $setting->port ?? '' }}">
                                </div>

                                <!-- Username -->
                                <div class="col-md-8 mb-3">
                                    <h5>Username</h5>
                                    <input type="text" name="username" class="form-control"
                                        value="{{ $setting->username ?? '' }}">
                                </div>

                                <!-- Password -->
                                <div class="col-md-8 mb-3">
                                    <h5>Password</h5>
                                    <input type="password" name="password" class="form-control"
                                        value="{{ $setting->password ?? '' }}">
                                </div>

                                <!-- Encryption -->
                                <div class="col-md-8 mb-3">
                                    <h5>Encryption</h5>
                                    <input type="text" name="encryption" class="form-control"
                                        value="{{ $setting->encryption ?? '' }}" placeholder="tls or ssl">
                                </div>

                                <!-- From Address -->
                                <div class="col-md-8 mb-3">
                                    <h5>From Email Address</h5>
                                    <input type="email" name="from_address" class="form-control"
                                        value="{{ $setting->from_address ?? '' }}">
                                </div>

                                <!-- From Name -->
                                <div class="col-md-8 mb-3">
                                    <h5>From Name</h5>
                                    <input type="text" name="from_name" class="form-control"
                                        value="{{ $setting->from_name ?? '' }}">
                                </div>

                                <!-- Active -->
                                <div class="col-md-8 mb-3">
                                    <h5>สถานะการใช้งาน</h5>
                                    <select name="active" class="form-control">
                                        <option value="1" {{ (isset($setting) && $setting->active) ? 'selected' : '' }}>
                                            ใช้งาน
                                        </option>
                                        <option value="0"
                                            {{ (isset($setting) && !$setting->active) ? 'selected' : '' }}>
                                            ไม่ใช้งาน</option>
                                    </select>
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