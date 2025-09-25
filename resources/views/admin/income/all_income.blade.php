@extends('frontend.frontend_dashboard')


@section('meta')
<title>รายได้ - baanlist</title>

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
                    <h2>รายได้</h2>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs">
                        <ul>
                            <li><a href="{{ route('home.index') }}">หน้าหลัก</a></li>
                            <li><a href="{{ route('admin.dashboard') }}">การจัดการ</a></li>
                            <li>รายได้</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">

             <!-- All Income Activity -->
            <div class="col-lg-6 col-md-12">
                <div class="dashboard-list-box invoices with-icons margin-top-20">
                    <h4>ทั้งหมด</h4>
                    <ul>
                       
                            <li><i class="list-box-icon fa fa-credit-card-alt"></i>
                            <strong>รายได้รวม</strong>
                            <ul>
                                <li class="paid">฿{{ addCommas($income4->sum('paid_amount')) }}</li>  
                            </ul>
                            
                            </li>
                    </ul>
                </div>
            </div>

            <!-- All Income Activity -->
            <div class="col-lg-6 col-md-12">
                <div class="dashboard-list-box invoices with-icons margin-top-20">
                    <h4>ประจำปี {{ now()->year + 543 }}</h4>
                    <ul>
                       
                            <li><i class="list-box-icon fa fa-credit-card-alt"></i>
                            <strong>รายได้รวม</strong>
                            <ul>
                                <li class="paid">฿{{ addCommas($income3->sum('paid_amount')) }}</li>  
                            </ul>
                            
                            </li>
                    </ul>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-lg-6 col-md-12">
                <div class="dashboard-list-box invoices with-icons margin-top-20">
                    <h4>ประจำเดือน {{ thaiMonthYear(now()) }} | รายได้: ฿{{ addCommas($income1->sum('paid_amount')) }} </h4>
                    <ul>
                        @foreach($income1 as $item)
                           

                            <li><i class="list-box-icon fa fa-credit-card-alt"></i>
                            <strong>{{$item->plan->package_name}}</strong>
                            <ul>
                                <li class="paid">฿{{$item->paid_amount}}</li>
                                <li>ชำระ: {{ thaiDateNoYearWithTime($item->activated_at) }}</li>
                            </ul>
                            <div class="buttons-to-right">
                                <a href="{{ route('admin.receipt', $item->id) }}" class="button gray">ดูใบเสร็จ</a>
                            </div>
                            </li>

                        
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Last Month Activity -->
            <div class="col-lg-6 col-md-12">
                <div class="dashboard-list-box invoices with-icons margin-top-20">
                    <h4>เดือนที่แล้ว {{ thaiMonthYear(now()->subMonth()) }} | รายได้: ฿{{ addCommas($income2->sum('paid_amount')) }}</h4>
                    <ul>
                        @foreach($income2 as $item)
                           

                            <li><i class="list-box-icon fa fa-credit-card-alt"></i>
                            <strong>{{$item->plan->package_name}}</strong>
                            <ul>
                                <li class="paid">฿{{$item->paid_amount}}</li>
                                <li>ชำระ: {{ thaiDateNoYearWithTime($item->activated_at) }}</li>
                            </ul>
                            <div class="buttons-to-right">
                                <a href="{{ route('admin.receipt', $item->id) }}" class="button gray">ดูใบเสร็จ</a>
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