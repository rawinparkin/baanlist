@extends('frontend.frontend_dashboard')


@section('meta')
<title>ประวัติจ่ายเงิน | baanlist</title>
<meta name="description" content="{{$seo->description}}">
<meta name="keywords" content="{{$seo->keywords}}">



<style>
.invoicebtn {
    text-decoration: underline;
    padding: 8px;
    border-radius: 10px;

}

/* @media screen and (max-width: 1250px) {
        .invoicebtn {
            position: relative;
            top: 10px;

        }
    } */
</style>

@endsection
@section('main')

<!-- Header Container
================================================== -->
@include('frontend.dashboard.body.header')
<!-- Header Container / End -->

<!-- Dashboard -->
<div id="dashboard">

    <!-- Navigation
================================================== -->

    @include('frontend.dashboard.body.sidebar')

    <!-- Content
	================================================== -->
    <div class="dashboard-content">

        <!-- Titlebar -->
        <div id="titlebar">
            <div class="row">
                <div class="col-md-12">
                    <h2>ประวัติจ่ายเงิน</h2>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs">
                        <ul>
                            <li><a href="{{ route('home.index') }}">หน้าหลัก</a></li>
                            <li><a href="{{ route('dashboard') }}">แผงควบคุม</a></li>
                            <li>ประวัติจ่ายเงิน</li>
                        </ul>
                    </nav>
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

        <!-- Row / Start -->


        <div class="row">

            <!-- Invoices -->
            <div class="col-lg-12 col-md-12">
                <div class="dashboard-list-box invoices with-icons margin-top-20">
                    <h4>ธุรกรรมทั้งหมด</h4> 
                    <ul>

                        @forelse($payment as $key => $item)
                        <li><i class="list-box-icon sl sl-icon-basket"></i>
                            <strong>แพ็กเกจ {{ $item->plan->package_name }}</strong>
                            <ul>
                                <li class="paid">฿ {{ $item->paid_amount}}</li>
                                <li>invoice: #{{$item->invoice }}</li>
                                <li class="paid">จำนวนประกาศ: <span>{{$item->plan->package_credits }}</span></li>
                                <li>ชำระ: {{ $item->formatted_activated_at }}</li>
                                <li class="unpaid">หมดอายุ: {{ $item->formatted_expire_date }}</li>
                                <li>
                                    <a href="{{ route('user.receipt', $item->id) }}" target="_blank"
                                        title="Download Receipt">
                                        <i class="sl sl-icon-doc"></i> ใบเสร็จ
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @empty
                        <li>ไม่มีข้อมูลการชำระเงิน</li>
                        @endforelse


                    </ul>
                </div>
            </div>





            <!-- Copyrights -->
            <div class="col-md-12">
                <div class="copyrights">© 2021 Listeo. All Rights Reserved.</div>
            </div>
        </div>
        <!-- Row / End -->

    </div>
    <!-- Content / End -->



</div>
<!-- Dashboard / End -->



@endsection
@section('scripts')



@endsection