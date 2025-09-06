<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ใบเสร็จ | baanlist</title>
<meta name="description" content="{{$seo->description}}">
<meta name="keywords" content="{{$seo->keywords}}">

<!-- Open Graph -->
<meta property="og:title" content="baanlist | ค้นหาบ้าน คอนโด ที่ดิน ซื้อขายง่าย ใกล้คุณ" />
<meta property="og:description" content="ค้นหาอสังหาริมทรัพย์ ซื้อ ขาย บ้าน คอนโด และที่ดินได้ง่าย ๆ กับ baanlist" />
<meta property="og:image" content="{{ asset('frontend/images/banner.jpg') }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:type" content="website" />

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="baanlist | ค้นหาบ้าน คอนโด ที่ดิน ซื้อขายง่าย ใกล้คุณ" />
<meta name="twitter:description" content="ค้นหาอสังหาริมทรัพย์ ซื้อ ขาย บ้าน คอนโด และที่ดินได้ง่าย ๆ กับ baanlist" />
<meta name="twitter:image" content="{{ asset('frontend/images/banner.jpg') }}" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
    <link rel="icon" href="{{ asset('frontend/images/B-8-removebg.png') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/invoice.css')}} ">

</head>

<body>

    <!-- Print Button -->
    <a href="javascript:window.print()" class="print-button">Print this receipt</a>

    <!-- Invoice -->
    <div id="invoice">

        <!-- Header -->
        <div class="row">
            <div class="col-md-6">
                <div id="logo"><img src="images/logo.png" alt=""></div>
            </div>

            <div class="col-md-6">

                <p id="details">
                    <strong>Invoice:</strong> #{{$receipt->invoice}} <br>
                    <strong>ออกวันที่:</strong> {{ $receipt->formatted_activated_at }} <br>

                </p>
            </div>
        </div>


        <!-- Client & Supplier -->
        <div class="row">
            <div class="col-md-12">
                <h2>ใบเสร็จรับเงิน</h2>
            </div> 

            <div class="col-md-6">
                <strong class="margin-bottom-5">ผู้ให้บริการ</strong>
                <p>
                    Baanlsit ltd. <br>
                    {{$setting->company_address}} <br>
                    {{$setting->company_address2}} {{$setting->company_address3}}<br>
                </p>
            </div>

            <div class="col-md-6">
                <strong class="margin-bottom-5">ผู้ชำระเงิน</strong>
                <p>
                    คุณ {{$receipt->billing->card_holder_name}}<br>
                    {{$receipt->billing->phone}}<br>
                    

                </p>
            </div>
        </div>


        <!-- Invoice -->
        <div class="row">
            <div class="col-md-12">
                <table class="margin-top-20">
                    <tr>
                        <th>รายละเอียด</th>
                        <th>จำนวน</th>
                        <th>ราคา(บาท)</th>
                    </tr>

                    <tr>
                        <td>แพ็กเกจ {{$receipt->plan->package_name}}</td>
                        <td>1</td>
                        <td>{{ $receipt->paid_amount}}</td>
                    </tr>
                </table>
            </div>

            <div class="col-md-4 col-md-offset-8">
                <table id="totals">
                    <tr>
                        <th>รวมราคา</th>
                        <th><span>{{ $receipt->paid_amount }}</span></th>
                    </tr>
                </table>
            </div>
        </div>


        <!-- Footer -->
        <div class="row">
            <div class="col-md-12">
                <ul id="footer">
                    <li><span>baanlist.com</span></li>
                    <li>Line: {{$setting->line}}</li>
                    <li>โทร: {{$setting->support_phone}}</li>
                </ul>
            </div>
        </div>

    </div>


</body>

</html>