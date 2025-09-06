@extends('frontend.frontend_dashboard')


@section('meta')
<title>ประเภทประกาศ - baanlist</title>

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
                    <h2>เพิ่มประเภท</h2>
                    <!-- Breadcrumbs -->
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Profile -->
            <div class="col-lg-6 col-md-12">
                <div class="dashboard-list-box margin-top-10">
                    <h4 class="gray">กรอกข้อมูล</h4>
                    <div class="dashboard-list-box-static">

                        <form id="typeForm" action="{{ route('admin.type.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Details -->
                            <div class="my-profile">

                                <label>ชื่อประเภท</label>
                                <input type="text" name="type_name" id="type_name">

                                <label>ไอคอน</label>
                                <input type="text" name="type_icon" id="type_icon">

                                <label>slug</label>
                                <input type="text" name="slug" id="slug">



                            </div>



                            <div class="row">
                                <div class="col-lg-8">
                                    <button class="button margin-top-15" type="submit"><i class="sl sl-icon-plus"></i>
                                        เพิ่ม</button>
                                </div>
                                <div class="col-lg-4">
                                    <a href="{{ route('admin.all.type') }}" class="button border margin-top-15">
                                        <i class="sl sl-icon-close"></i> ยกเลิก
                                    </a>
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
<script>
    document.getElementById('typeForm').addEventListener('submit', function(e) {
        const name = document.getElementById('type_name').value.trim();
        const icon = document.getElementById('type_icon').value.trim();
        const slug = document.getElementById('slug').value.trim();

        if (!name || !icon || !slug) {
            e.preventDefault(); // Stop the form from submitting

            if (!name) {
                Toastify({
                    text: "กรุณากรอกชื่อประเภท",
                    duration: 3000,

                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#f44336"
                    },
                }).showToast();
            }

            if (!icon) {
                Toastify({
                    text: "กรุณากรอกไอคอน",
                    duration: 3000,

                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#f44336"
                    },
                }).showToast();
            }

            if (!slug) {
                Toastify({
                    text: "กรุณากรอก slug",
                    duration: 3000,
                    style: {
                        background: "#f44336"
                    },
                    gravity: "top",
                    position: "right"
                }).showToast();
            }

            return false;
        }
    });
</script>
@endsection