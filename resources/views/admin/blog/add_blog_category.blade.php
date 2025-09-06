@extends('frontend.frontend_dashboard')


@section('meta')
<title>เพิ่มประเภทของบทความ - baanlist</title>

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
                    <h2>เพิ่มประเภทของบทความ</h2>
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

                        <form id="typeForm" action="{{ route('blog.category.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Details -->
                            <div class="my-profile">

                                <label>ประเภท blog</label>
                                <input type="text" name="category_name" id="category_name">



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
        const name = document.getElementById('category_name').value.trim();


        if (!name) {
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


            return false;
        }
    });
</script>
@endsection