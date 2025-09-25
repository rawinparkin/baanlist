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
                    <h2>ประเภท</h2>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs">
                        <ul>
                            <li><a href="{{ route('home.index') }}">หน้าหลัก</a></li>
                            <li><a href="{{ route('admin.dashboard') }}">การจัดการ</a></li>
                            <li>ประเภท</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">

            <!-- All Types -->
            <div class="col-lg-12 col-md-12">
                <div class="dashboard-list-box with-icons margin-top-10">
                    <h4>ประเภทของอสังหาฯ</h4>
                    <ul>
                        @foreach($ptype as $item)
                        <li>
                            <i class="list-box-icon {{$item->type_icon}}"></i> {{$item->type_name}}

                            <div class="buttons-to-right">
                                <a href="{{route('admin.edit.type', $item->id) }}" class="button gray"><i
                                        class="sl sl-icon-note"></i> แก้ไข</a>
                                <a href="{{ route('admin.delete.type', $item->id) }}" class="button gray delete-link"
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
                <a href="{{route('admin.add.type')}}" class="button border"><i class="sl sl-icon-plus"></i>
                    เพิ่มประเภท</a>
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
$('#delete_subdistrict_btn').on('click', function(e) {
    const confirmed = confirm('คุณแน่ใจว่าต้องการลบตำบลนี้?');
    if (!confirmed) {
        e.preventDefault();
    }
});
</script>


@endsection