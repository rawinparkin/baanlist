@extends('frontend.frontend_dashboard')
@section('meta')
<title>ลืมรหัสผ่าน - baanlist</title>
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
        <h3 class="text-center margin-bottom-20">ลืมรหัสผ่าน?</h3>
        <p>ลืมรหัสผ่านหรือไม่? ไม่ต้องกังวล แจ้งอีเมลของคุณมาได้เลย เราจะส่งลิงก์รีเซ็ตรหัสผ่านไปให้คุณ เพื่อให้คุณตั้งรหัสผ่านใหม่ได้อย่างง่ายดาย</p>
        
        {{-- ✅ SUCCESS STATUS --}}
        @if (session('status'))
            <div class="notification success closeable">
                {{ session('status') }}
            </div>
        @endif

        {{-- ✅ VALIDATION ERROR --}}
        @if ($errors->any())
            <div class="notification error closeable">
                {{ $errors->first('email') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="login">
            @csrf
            <p class="form-row form-row-wide">
                <label><b>อีเมล์:</b>
                    <input type="email" class="input-text" name="email" value="{{ old('email') }}" required autofocus />
                </label>
            </p>

            <input type="submit" class="button border margin-top-20" style="width:100%;padding:10px 20px;border-radius:10px;" value="ส่งอีเมล์เปลี่ยนรหัสผ่าน" />
        </form>
            <div style="text-align: right; margin-top: 10px;">
            <a href="{{route('home.index')}}"  class="button" style="background-color: #f9f9f9;color:#84c015;border:1px solid#84c015;">กลับหน้าแรก</a>
            </div>
            

    </div>
</div>
@endsection