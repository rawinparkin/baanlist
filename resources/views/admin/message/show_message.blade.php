@extends('frontend.frontend_dashboard')


@section('meta')
<title>อ่านข้อความ - baanlist</title>

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
                    <h2>อ่านข้อความ</h2>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs">
                        <ul>
                            <li><a href="{{ route('home.index') }}">หน้าหลัก</a></li>
                            <li><a href="{{ route('admin.dashboard') }}">การจัดการ</a></li>
                            <li>อ่านข้อความ</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">

            <!-- All Types -->
            <div class="col-lg-12 col-md-12">
                <div class="dashboard-list-box with-icons margin-top-10">
                    <h4>From: คุณ {{$data->name}} | Email: {{$data->email}} | Sent: {{$data->created_at}}</h4>
                    <ul>
                        <li>
                            <i class="list-box-icon sl sl-icon-envelope-open"></i> <strong>{{$data->subject}}</strong>
                            {{$data->message}}

                            <div class="buttons-to-right">

                                <a href="{{ route('delete.message.box', $data->id) }}" class="button gray delete-link"
                                    data-id="{{ $data->id }}">
                                    <i class="sl sl-icon-close"></i> ลบ
                                </a>
                            </div>
                        </li>


                    </ul>
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