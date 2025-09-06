@extends('frontend.frontend_dashboard')


@section('meta')

<title>แพ็กเกจ{{ $selectedPlan->package_name }} | baanlist</title>
<meta name="description" content="{{$seo->description}}">
<meta name="keywords" content="{{$seo->keywords}}">

<!-- Open Graph -->
<meta property="og:title" content="{{$seo->title2}}" />
<meta property="og:description" content="{{$seo->title3}}" />
<meta property="og:image" content="{{ asset('frontend/images/banner.jpg') }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:type" content="website" />

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{$seo->title2}}" />
<meta name="twitter:description" content="{{$seo->title3}}" />
<meta name="twitter:image" content="{{ asset('frontend/images/banner.jpg') }}" />
<!-- CSS -->

<link rel="stylesheet" href="{{ asset('frontend/css/payment.css') }}">


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
                    <h2>แพ็กเกจ {{ $selectedPlan->package_name }}</h2>
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs">
                        <ul>
                            <li><a href="{{ route('home.index') }}">หน้าหลัก</a></li>
                            <li><a href="{{ route('dashboard') }}">แผงควบคุม</a></li>
                            <li>แพ็กเกจ {{ $selectedPlan->package_name }}</li>
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

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif
 
        <!-- Row / Start -->
        <div class="row">

                        

            <form method="post" id="paymentForm" action="{{ route('store.package.plan') }}">
                @csrf
                <!-- Spinner overlay -->
                <div id="upload-spinner" class="spinner-container">
                    <div id="spinner-icon" class="spinner"></div>
                    <div id="success-check" class="checkmark">
                        <svg viewBox="0 0 52 52">
                            <path
                                d="M26 0C11.6 0 0 11.6 0 26s11.6 26 26 26 26-11.6 26-26S40.4 0 26 0zm0 48C13.2 48 4 38.8 4 26S13.2 4 26 4s22 9.2 22 22-9.2 22-22 22zm10.3-29.7L22 32.6l-6.3-6.3-2.8 2.8L22 38.2l17.1-17.1-2.8-2.8z" />
                        </svg>
                    </div>
                </div>
               
                <!-- hidden fields -->
                <input type="hidden" name="package_id" value="{{ $selectedPlan->id }}">
                <input type="hidden" name="amount" id="amount" value="{{ $selectedPlan->price }}">
                <input type="hidden" name="omise_token" id="omise_token">



                <!-- Profile -->
                <div class="col-lg-7 col-md-12">
                    <div class="dashboard-list-box margin-top-0 margin-bottom-30">


                        <h4 class="gray position-wrapper">
                            ชำระเงิน<br><span class="secure-omise">Payments securely processed by <b>Omise</b></span>
                            <span class="payment-icons">
                                <i class="fa fa-cc-visa"></i>
                                <i class="fa fa-cc-mastercard"></i>
                            </span>
                        </h4>

                        

                        <div class="dashboard-list-box-static">

                      

                           <div class="payment-option">
                                <input type="radio" id="promptpay" name="payment_method" value="promptpay" required>
                                <label for="promptpay"><i class="fa fa-qrcode"></i> พร้อมเพย์</label> 

                                <input type="radio" id="card" name="payment_method" value="card">
                                <label for="card"><i class="fa fa-credit-card"></i> เครดิต/เดบิต</label>
                            </div>

                            <!-- PromptPay -->

                            <div class="my-profile text-center" id="promptpay_section" style="display:none;">
                                <div id="qr-container" class="margin-top-30">
                                    <p>กำลังโหลด QR...</p>
                                </div>
                            </div>
                          

                            <!-- Credit Card -->
                            <div class="my-profile " id="creditcard_section" style="display:none;">
                                <!-- Card Number -->
                                <div class="col-md-12 margin-top-30 ">
                                    <label class="margin-top-0">เลขบัตรเครดิต/เดบิต</label>
                                    <input type="text" name="creditcard" id="creditcard" maxlength="19"
                                        placeholder="XXXX XXXX XXXX XXXX" inputmode="numeric" autocomplete="cc-number"
                                        oninput="formatCreditCard(this)">
                                </div>

                               <!-- Expiry -->
                                <div class="col-md-4">
                                    <label class="margin-top-0">เดือนหมดอายุ</label>
                                    <input type="text" name="card_exp_month" placeholder="Exp Month (MM)" id="card_exp_month" maxlength="2"
                                        pattern="^(0?[1-9]|1[0-2])$"
                                        inputmode="numeric"
                                        oninput="
                                            this.value = this.value.replace(/[^0-9]/g, ''); 
                                            if (this.value.length === 2) {
                                                const num = parseInt(this.value, 10);
                                                if (num < 1) this.value = '01';
                                                else if (num > 12) this.value = '12';
                                            }
                                        ">
                                </div>

                                <div class="col-md-4">
                                    <label class="margin-top-0">ปีหมดอายุ</label>
                                    <input
                                        type="text"
                                        id="card_exp_year"
                                        name="card_exp_year"                         
                                        placeholder="Exp Year (YY)"
                                        maxlength="2"
                                        inputmode="numeric"
                                        pattern="^[0-9]{2}$"
                                        oninput="
                                            this.value = this.value.replace(/[^0-9]/g, '');
                                            if (this.value.length > 2) this.value = this.value.slice(0, 2);
                                        "
                                    />
                                </div>

                                <!-- CVV -->
                                <div class="col-md-4">
                                    <label class="margin-top-0">CVV</label>
                                    <input type="text" name="card_cvc" id="card_cvc" placeholder="XXX" inputmode="numeric" pattern="\d*"
                                        maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>

                                <!-- Cardholder Name -->
                                <div class="col-md-12 margin-bottom-30">
                                    <label class="margin-top-0">ชื่อเจ้าของบัตร</label>
                                    <input type="text" name="card_holder_name" id="card_holder_name" placeholder="ชื่อบนบัตร">
                                </div>

                                
                                <div class="clearfix"></div>


                            </div>




                        </div>
        



                    </div>

                 

                </div>

                <!-- Cart Details -->
                <div class="col-lg-5 col-md-12 margin-bottom-30" id="cart_details" style="display:none;">
                    <div class="dashboard-list-box margin-top-0 ">
                        <h4 class="gray">รายละเอียด</h4>
                        <div class="dashboard-list-box-static">

                            <!-- Cart Details -->
                            <div class="cart-details">
                                <div class="summary-section">
                                    <div class="summary-row">

                                        <div>แพ็กเกจ</div>
                                        <div class="summary-value">{{ $selectedPlan->package_name }}</div>
                                    </div>
                                    <div class="summary-row">
                                        <div>ราคา</div>
                                        <div class="summary-value">{{ $selectedPlan->package_cost }}</div>
                                    </div>
                                    <div class="summary-row">
                                        <div>ระยะเวลาใช้งาน</div>
                                        <div class="summary-value">{{ $selectedPlan->validity_days }} วัน
                                            ถึง ({{ $expiryDate }})
                                        </div>
                                    </div>
                                    <div class="summary-row">
                                        <div>จำนวนประกาศ</div>
                                        <div class=" summary-value">{{ $selectedPlan->package_credits }} รายการ</div>
                                    </div>

                                    @if($selectedPlan->is_featured)
                                    <div class="summary-row">
                                        <div>สิทธิพิเศษ</div>
                                        <div class="summary-value">{{$selectedPlan->feature_desc }}</div>
                                    </div>
                                    @endif
                                </div>
                                <div class="summary-footer">
                                    <div>รวมชำระ</div>
                                    <div class="summary-total">฿{{ $selectedPlan->price }}</div>
                                </div>
                            </div>

                        </div>


                    </div>
                    <div class="checkboxes margin-top-20">
                        <input type="checkbox" name="agree" id="agree" value="1">
                        <label for="agree">ฉันยอมรับ <a href="#terms-modal" class="open-modal">เงื่อนไขและข้อตกลง</a>
                            ในการใช้บริการ</label>
                    </div>

                    <button type="submit" class="button" id="place_order" >ยืนยัน และ ชำระเงิน</button>
                </div>
            </form>

            <div class="clearfix"></div>    


        </div>
        <!-- Row / End -->

    </div>
    <!-- Content / End -->

     <!-- Modal: เงื่อนไขการใช้งาน -->
        <div id="terms-modal" class="zoom-anim-dialog mfp-hide">
            <div class="small-dialog-header">
                <h3>เงื่อนไขการใช้งาน</h3>
            </div>
            <div class="modal-content">
                <p>
                    {!! $setting->terms_of_service !!}
                </p>
                <!-- เพิ่มเนื้อหาเงื่อนไขเต็มได้ที่นี่ -->
            </div>
        </div>


</div>
<!-- Dashboard / End -->



@endsection
@section('scripts')
<script src="https://cdn.omise.co/omise.js"></script>
<script>
    window.PaymentConfig = {
        omisePublicKey: "{{ config('omise.public_key') }}",
        promptpayRoute: "{{ route('generate.promptpay') }}",
        csrfToken: "{{ csrf_token() }}",
        testMode: false // or false for Live Mode
    };
</script>
<script type="text/javascript" src="{{ asset('frontend/scripts/payment.js') }}"></script>
@endsection

