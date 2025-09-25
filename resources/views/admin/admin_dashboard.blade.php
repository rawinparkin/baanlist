@extends('frontend.frontend_dashboard')
@section('meta')
<title>การจัดการ - baanlist</title>

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
                    <h2>สวัสดี {{$userData->name}}!</h2>
                    <!-- Breadcrumbs -->

                </div>
            </div>
        </div>

        <!-- Notice -->
        @if(session('notification'))
        <div class="row">
            <div class="col-md-12">
                <div class="notification {{ session('notification_class', 'success') }} closeable margin-bottom-30">
                    <p>{{ session('notification') }}</p>
                    <a class="close" href="#"></a>
                </div>
            </div>
        </div>
        @endif


        <!-- Content -->
        <div class="row">

            <!-- Item -->
            <div class="col-lg-3 col-md-6">
                <div class="dashboard-stat color-1">
                    <div class="dashboard-stat-content">
                        <h4>{{$property}}</h4> <span>ประกาศทั้งหมด</span>
                    </div>
                    <div class="dashboard-stat-icon"><i class="im im-icon-Home-5"></i></div>
                </div>
            </div>

            <!-- Item -->
            <div class="col-lg-3 col-md-6">
                <div class="dashboard-stat color-2">
                    <div class="dashboard-stat-content">
                        <h4>726</h4> <span>คนเข้าชมวันนี้</span>
                    </div>
                    <div class="dashboard-stat-icon"><i class="im im-icon-Gaugage"></i></div>
                </div>
            </div>


            <!-- Item -->
            <div class="col-lg-3 col-md-6">
                <div class="dashboard-stat color-3">
                    <div class="dashboard-stat-content">
                        <h4>{{$users}}</h4> <span>สมาชิก</span>
                    </div>
                    <div class="dashboard-stat-icon"><i class="im im-icon-User"></i></div>
                </div>
            </div>

            <!-- Item -->
            <div class="col-lg-3 col-md-6">
                <div class="dashboard-stat color-4">
                    <div class="dashboard-stat-content">
                        <h4>{{$blog}}</h4> <span>บทความ</span>
                    </div>
                    <div class="dashboard-stat-icon"><i class="im im-icon-Open-Book"></i></div>
                </div>
            </div>
        </div>


        <div class="row">

            <!-- All Income Activity -->
            <div class="col-lg-6 col-md-12">
                <div class="dashboard-list-box invoices with-icons margin-top-20">
                    <h4>ข้อมูลวันนี้</h4>
                    <ul>
                       
                            <li><i class="list-box-icon fa fa-credit-card-alt"></i>
                            <strong>รายได้รวม</strong>
                            <ul>
                                <li class="paid">฿{{ addCommas($income4->sum('paid_amount')) }}</li>  
                            </ul>
                            </li>
                            <li><i class="list-box-icon fa fa-user"></i>
                            <strong>สมาชิกใหม่วันนี้</strong>
                            <ul>
                                <li class="paid">{{ addCommas($userToday) }} คน</li>  
                            </ul>
                            </li>
                            <li><i class="list-box-icon fa fa-home"></i>
                            <strong>ประกาศใหม่วันนี้</strong>
                            <ul>
                                <li class="paid">{{ addCommas($propertyToday) }} ประกาศ</li>  
                            </ul>
                            </li>
                    </ul>
                </div>
            </div>

            <!-- All Income Activity -->
            <div class="col-lg-6 col-md-12">
                <div class="dashboard-list-box invoices with-icons margin-top-20">
                    <h4>ทั้งหมด</h4>
                    <ul>
                            <li><i class="list-box-icon fa fa-credit-card-alt"></i>
                            <strong>รายได้วันนี้</strong>
                            <ul>
                                <li class="paid">฿{{ addCommas($income5->sum('paid_amount')) }}</li>  
                            </ul>
                            </li>

                            <li><i class="list-box-icon fa fa-credit-card-alt"></i>
                            <strong>รายได้ปีนี้</strong>
                            <ul>
                                <li class="paid">฿{{ addCommas($income3->sum('paid_amount')) }}</li>  
                            </ul>
                            </li>
                       
                            <li><i class="list-box-icon fa fa-credit-card-alt"></i>
                            <strong>รายได้รวม</strong>
                            <ul>
                                <li class="paid">฿{{ addCommas($income4->sum('paid_amount')) }}</li>  
                            </ul>
                            </li>
                    </ul>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-lg-12 col-md-12">
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

            


            <!-- Copyrights -->
            <div class="col-md-12">
                <div class="copyrights">{{$setting->copyright}}</div>
            </div>
        </div>

    </div>
    <!-- Content / End -->


</div>
<!-- Dashboard / End -->

@endsection