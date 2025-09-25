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
                    <h2>เครื่องอำนวยความสะดวก</h2>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs">
                        <ul>
                            <li><a href="{{ route('home.index') }}">หน้าหลัก</a></li>
                            <li><a href="{{ route('admin.dashboard') }}">การจัดการ</a></li>
                            <li>เครื่องอำนวยความสะดวก</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">

            <!-- All Types -->
            <div class="col-lg-12 col-md-12">
                <div class="dashboard-list-box with-icons margin-top-10">
                    <h4>เครื่องอำนวยความสะดวก</h4>
                    <ul>
                        @foreach($pamen as $item)
                        <li>
                            <i class="list-box-icon {{$item->amenity_icon}}"></i> {{$item->amenity_name}}

                            <div class="buttons-to-right">
                                <a href="{{route('admin.edit.amenity', $item->id) }}" class="button gray"><i
                                        class="sl sl-icon-note"></i> แก้ไข</a>
                                <a href="{{ route('admin.delete.amenity', $item->id) }}" class="button gray delete-link"
                                    data-id="{{ $item->id }}">
                                    <i class="sl sl-icon-close"></i> ลบ
                                </a>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>


        </div>

        <div class="row margin-top-30">
            <div class="col-md-12 text-center">
                <a href="{{route('admin.add.amenity')}}" class="button border"><i class="sl sl-icon-plus"></i>
                    เพิ่มเครื่องอำนวยความสะดวก</a>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.delete-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent the default link behavior

            const href = this.getAttribute('href');

            Swal.fire({
                title: "คุณแน่ใจหรือไม่?",
                text: "คุณต้องการลบประเภทนี้",
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