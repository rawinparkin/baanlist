@extends('frontend.frontend_dashboard')


@section('meta')
<title>แก้ไขจังหวัด, อำเภอ, ตำบล - baanlist</title>

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
                    <h2>แก้ไขจังหวัด, อำเภอ, ตำบล</h2>
                    <!-- Breadcrumbs -->
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Profile -->
            <div class="col-lg-12 col-md-12">
                <div class="dashboard-list-box margin-top-10">
                    <h4 class="gray">จังหวัด</h4>
                    <div class="dashboard-list-box-static">

                        <form id="typeForm" action="{{ route('admin.province.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                          

                            <!-- Details -->
                            <div class="my-profile">

                                <label>ชื่อจังหวัด ไทย</label>
                                <input type="text" name="name_th" id="name_th" value="{{$province->name_th}}">

                                <label>ชื่อจังหวัด eng</label>
                                <input type="text" name="name_en" id="name_en" value="{{$province->name_en}}">

                                <label>ID</label>
                                <input type="text" name="id" id="id" value="{{$province->id}}" readonly>


                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <button class="button margin-top-15" type="submit">
                                        <i class="sl sl-icon-note"></i> บันทึก
                                    </button>
                                </div>
                                <div class="col-lg-4">
                                    <a href="{{ route('admin.all.province') }}" class="button border margin-top-15">
                                        <i class="sl sl-icon-close"></i> ยกเลิก
                                    </a>
                                </div>
                            </div>



                        </form>

                        
                    </div>
                </div>
            </div>

             <!-- Profile -->
            <div class="col-lg-6 col-md-12">
                <div class="dashboard-list-box margin-top-10">
                    <h4 class="gray">อำเภอ</h4>
                    <div class="dashboard-list-box-static">

                        <form id="typeForm" action="{{ route('admin.district.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                        

                            <!-- Details -->
                            <div class="my-profile">

                     
                                    <h5>อำเภอ/เขต</h5>
                                    <!-- Your Select Dropdown -->
                                    <select name="district_name" id="district_name">
                                        <option value="">เลือกอำเภอ/เขต</option> <!-- no 'disabled' -->
                                        @foreach($district as $p)
                                        <option value="{{ $p->id }}">{{ $p->name_th }}</option>
                                        @endforeach
                                    </select>
                                

                                <label>ชื่ออำเภอ/เขต ไทย</label>
                                <input type="text" name="name_th" id="distrcit_name_th" value="">

                                <label>ชื่ออำเภอ/เขต eng</label>
                                <input type="text" name="name_en" id="distrcit_name_en" value="">

                                <label>Province ID</label>
                                <input type="text" name="province_id" id="province_id" value="{{$province->id}}">

                                <label>District ID</label>
                                <input type="text" name="district_id" id="district_id" readonly>

                            


                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <button class="button margin-top-15" type="submit">
                                        <i class="sl sl-icon-note"></i> บันทึก
                                    </button>
                                </div>
                                <div class="col-lg-4">
                                    <a href="{{ route('admin.all.province') }}" class="button border margin-top-15">
                                        <i class="sl sl-icon-close"></i> ยกเลิก
                                    </a>
                                </div>
                            </div>



                        </form>

                        
                    </div>
                </div>
            </div>

            <!-- Profile -->
            <div class="col-lg-6 col-md-12">
                <div class="dashboard-list-box margin-top-10">
                    <h4 class="gray">ตำบล</h4>
                    <div class="dashboard-list-box-static">

                        <form id="typeForm" action="{{ route('admin.subdistrict.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                        

                            <!-- Details -->
                            <div class="my-profile">

                     
                                    <h5>ตำบล</h5>
                                    <!-- Your Select Dropdown -->
                                   <select name="subdistrict_name" id="subdistrict_dropdown">
                                        <option value="">เลือกตำบล</option>
                                    </select>
                                
                                <label>ชื่อตำบล ไทย</label>
                                <input type="text" name="name_th" id="subdistrict_name_th" value="">

                                <label>ชื่อตำบล eng</label>
                                <input type="text" name="name_en" id="subdistrict_name_en" value="">

                                <label>Zipcode</label>
                                <input type="text" name="zip_code" id="zip_code" value="">

                                <label>District ID</label>
                                <input type="text" name="district_id" id="district_id_from_sub" >

                                <label>Sub-District ID</label>
                                <input type="text" name="sub_district_id" id="sub_district_id_from_sub" readonly>


                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <button class="button margin-top-15" type="submit">
                                        <i class="sl sl-icon-note"></i> บันทึก
                                    </button>
                                </div>
                                <div class="col-lg-4">
                                    <a href="#" id="delete_subdistrict_btn" class="button border margin-top-15 delete-link">
                                        <i class="sl sl-icon-close"></i> ลบตำบล
                                    </a>
                                </div>
                            </div>



                        </form>

                        
                    </div>
                </div>
            </div>

            <!-- Profile -->
            <div class="col-lg-6 col-md-12">
                <div class="dashboard-list-box margin-top-10">
                    <h4 class="gray">เพิ่มตำบล</h4>
                    <div class="dashboard-list-box-static">

                        <form id="typeForm" action="{{ route('admin.subdistrict.new') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                        

                            <!-- Details -->
                            <div class="my-profile">

                                <label>District ID</label>
                                <input type="text" name="new_district_id" id="new_district_id_from_sub" autocomplete="off">

                                <label>Zipcode</label>
                                <input type="text" name="new_zip_code" autocomplete="off">

                                
                                <label>ชื่อตำบล ไทย</label>
                                <input type="text" name="new_name_th"  autocomplete="off">

                                <label>ชื่อตำบล eng</label>
                                <input type="text" name="new_name_en" autocomplete="off">

                                

                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <button class="button margin-top-15" type="submit">
                                        <i class="sl sl-icon-note"></i> เพิ่ม
                                    </button>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#district_name').on('change', function () {
        const districtId = $(this).val();

        if (districtId) {
            $.ajax({
                url: `/get-district-details/${districtId}`,
                type: 'GET',
                success: function (data) {
                    $('#distrcit_name_th').val(data.name_th);
                    $('#distrcit_name_en').val(data.name_en);
                    $('#district_id').val(data.district_id);
                    $('#province_id').val(data.province_id);
                    $('#new_district_id_from_sub').val(data.district_id);
                    
                },
                error: function () {
                    alert('เกิดข้อผิดพลาดในการดึงข้อมูล');
                }
            });
        } else {
            // Reset fields if no district selected
            $('#name_th').val('');
            $('#name_en').val('');
            $('#district_id').val('');
           
        }
    });
</script>

<script>
    $('#district_name').on('change', function () {
    const districtId = $(this).val();
    $('#subdistrict_dropdown').html('<option>Loading...</option>');

    if (districtId) {
        $.ajax({
            url: '/get-subdistricts/' + districtId,
            type: 'GET',
            success: function (data) {
                $('#subdistrict_dropdown').empty().append('<option value="">เลือกตำบล</option>');
                $.each(data, function (key, item) {
                    $('#subdistrict_dropdown').append(
                        '<option value="' + item.id + '">' + item.name_th + '</option>'
                    );
                });
            }
        });
    } else {
        $('#subdistrict_dropdown').html('<option value="">เลือกตำบล</option>');
    }
});

</script>

<script>
    $('#subdistrict_dropdown').on('change', function () {
        const subdistrictId = $(this).val();

        if (subdistrictId) {
            $.ajax({
                url: `/get-subdistrict-details/${subdistrictId}`,
                type: 'GET',
                success: function (data) {
                    $('#subdistrict_name_th').val(data.name_th);
                    $('#subdistrict_name_en').val(data.name_en);
                    $('#zip_code').val(data.zip_code);
                    $('#district_id_from_sub').val(data.district_id);
                    $('#sub_district_id_from_sub').val(subdistrictId);
                    

                    // ✅ Update delete button href
                    $('#delete_subdistrict_btn').attr('href', `/admin/delete/subdistrict/${subdistrictId}`);
                },
                error: function () {
                    alert('ไม่สามารถโหลดข้อมูลตำบลได้');
                }
            });
        } else {
            $('#subdistrict_name_th').val('');
            $('#subdistrict_name_en').val('');
            $('#zip_code').val('');
            $('#district_id_from_sub').val('');
            $('#sub_district_id_from_sub').val('');

            // Reset delete button
            $('#delete_subdistrict_btn').attr('href', '#');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent the default link behavior

            const href = this.getAttribute('href');

            Swal.fire({
                title: "คุณแน่ใจหรือไม่?",
                text: "คุณต้องการลบตำบลนี้",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#aaa",
                confirmButtonText: "ลบเลย",
                cancelButtonText: "ยกเลิก",
                customClass: {
                    popup: "swal-wide"
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect manually to the delete URL
                    window.location.href = href;
                }
            });
        });
    });
});
</script>


@endsection
