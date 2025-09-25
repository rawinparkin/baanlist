@extends('frontend.frontend_dashboard')


@section('meta')
<title>แพ็คเกจ - baanlist</title>

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
                    <h2>แพ็คเกจ</h2>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs">
                        <ul>
                            <li><a href="{{ route('home.index') }}">หน้าหลัก</a></li>
                            <li><a href="{{ route('admin.dashboard') }}">การจัดการ</a></li>
                            <li>แพ็คเกจ</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">

            <!-- All Types -->
            <div class="col-lg-12 col-md-12">
                <div class="dashboard-list-box with-icons margin-top-10">
                    <h4>แพ็คเกจทั้งหมด</h4>
                    <ul>
                        @foreach($plan as $item)
                        <li>
                            <i class="list-box-icon sl sl-icon-credit-card"></i> {{$item->package_name}}

                            <div class="buttons-to-right">
                                <a href="{{route('admin.edit.package', $item->id) }}" class="button gray"><i
                                        class="sl sl-icon-note"></i> แก้ไข</a>
                                {{-- <a href="" class="button gray delete-link"
                                    data-id="{{ $item->id }}">
                                    <i class="sl sl-icon-close"></i> ลบ
                                </a> --}}
                            </div>
                        </li>
                        @endforeach
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