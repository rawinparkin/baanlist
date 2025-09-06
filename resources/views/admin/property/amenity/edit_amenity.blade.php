@extends('frontend.frontend_dashboard')


@section('meta')
<title>เครื่องอำนวยความสะดวก - baanlist</title>

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
                    <h2>แก้ไขเครื่องอำนวยความสะดวก</h2>
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

                        <form id="typeForm" action="{{ route('admin.update.amenity') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="id" value="{{$pamen->id}}">

                            <!-- Details -->
                            <div class="my-profile">

                                <label>เครื่องอำนวยความสะดวก</label>
                                <input type="text" name="amenity_name" id="amenity_name"
                                    value="{{$pamen->amenity_name}}">

                                <label>ไอคอน</label>
                                <input type="text" name="amenity_icon" id="amenity_icon"
                                    value="{{$pamen->amenity_icon}}">





                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <button class="button margin-top-15" type="submit">
                                        <i class="sl sl-icon-note"></i> บันทึก
                                    </button>
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
    const name = document.getElementById('amenity_name').value.trim();
    const icon = document.getElementById('amenity_icon').value.trim();


    if (!name || !icon) {
        e.preventDefault(); // Stop the form from submitting

        if (!name) {
            Toastify({
                text: "กรุณากรอกชื่อประเภท",
                duration: 3000,
                backgroundColor: "#f44336",
                gravity: "top",
                position: "right"
            }).showToast();
        }

        if (!icon) {
            Toastify({
                text: "กรุณากรอกไอคอน",
                duration: 3000,
                backgroundColor: "#f44336",
                gravity: "top",
                position: "right"
            }).showToast();
        }

        return false;
    }
});
</script>
@endsection