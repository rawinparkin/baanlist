@extends('frontend.frontend_dashboard')
@section('meta')
<title>ยืนยันอีเมล์ - baanlist</title>
<meta name="description" content="baanlist - ขายบ้าน หาบ้าน คอนโด ที่ดิน">
<meta name="keywords" content="บ้าน, คอนโด, ที่ดิน, ขายบ้าน, หาบ้าน">
<style>
    .full-center-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-color: #f9f9f9;
        padding: 20px;
    }
    .login-page-tab {
        max-width: 500px;
    }
    @media (max-width: 767px) {
        .login-page-tab {
            width: calc(100% + 60px);
        }
    }
</style>
@endsection

@section('main')
<div class="full-center-wrapper">
    <div class="tab-content login-page-tab">
        <h3 class="text-center margin-bottom-20">ยืนยันอีเมล์</h3>
        <p>ขอบคุณที่สมัครใช้งานกับ baanlist!
            ก่อนเริ่มใช้งาน กรุณายืนยันที่อยู่อีเมลของคุณโดยคลิกที่ลิงก์ที่เราส่งไปยังอีเมลของคุณ
            หากคุณไม่ได้รับอีเมล เรายินดีที่จะส่งให้ใหม่อีกครั้ง</p>
        
        {{-- ✅ SUCCESS STATUS --}}
         @if (session('status') == 'verification-link-sent')
        <div class="notification success closeable">
            {{ __('ลิงก์ยืนยันใหม่ได้ถูกส่งไปยังที่อยู่อีเมลที่คุณใช้ในการสมัครเรียบร้อยแล้ว') }}
        </div>
        @endif

       

        <form method="POST" action="{{ route('verification.send') }}" class="login">
            @csrf
            <input type="submit" class="button border margin-top-20" style="width:100%;padding:10px 20px;border-radius:10px;" value="ส่งอีเมล์ยืนยันอีกครั้ง" />
        </form>

        <form method="POST" action="{{ route('logout') }}" class="login" style="text-align: right; margin-top: 10px;">
            @csrf
            <button type="submit" class="button" style="background-color: #f9f9f9;color:#84c015;border:1px solid#84c015;">กลับหน้าแรก</button>
        </form>
    </div>
</div>
@endsection