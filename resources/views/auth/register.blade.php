
@extends('frontend.frontend_dashboard')
@section('meta')
<title>สมัครสมาชิก - baanlist</title>
<meta name="description" content="baanlist - ขายบ้าน หาบ้าน คอนโด ที่ดิน">
<meta name="keywords" content="บ้าน, คอนโด, ที่ดิน, ขายบ้าน, หาบ้าน">
<style>
    .full-center-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh; /* Full viewport height */
    background-color: #f9f9f9; /* Optional: light background */
    padding: 20px; /* Optional: prevent overflow on small screens */
}
.login-page-tab{
max-width:500px;
}
.close-login-btn {
    position: absolute;
    top: -20px;
    right: 20px;
    font-size: 32px;
    text-decoration: none;
    color: #333;
    z-index: 10;
    line-height: 1;
}

.close-login-btn:hover {
    color: #84c015;
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
                            <a href="{{ route('home.index') }}" class="close-login-btn" aria-label="Close login page">&times;</a>
                            <h3 class="text-center margin-bottom-20">สมัครสมาชิก</h3>

                            <form method="POST" action="{{ route('register') }}" class="register">
                                @csrf

                                <p class="form-row form-row-wide">
                                    <label>ชื่อ:
                                        <i class="im im-icon-Male"></i>
                                        <input type="text" class="input-text" name="name" id="name1"
                                            autocomplete="off" />
                                            <span class="error-message" id="error-name1"></span>
                                    </label>
                                </p>

                                <p class="form-row form-row-wide">
                                    <label for="email2">อีเมล์:
                                        <i class="im im-icon-Mail"></i>
                                        <input type="text" class="input-text" name="email" id="email2"
                                            autocomplete="off" />
                                            <span class="error-message" id="error-email2"></span>
                                    </label>
                                </p>

                                <p class="form-row form-row-wide">
                                    <label for="password1">รหัสผ่าน:
                                        <i class="im im-icon-Lock-2"></i>
                                        <input class="input-text" type="password" name="password" id="password1" />
                                        <span class="error-message" id="error-password1"></span>
                                    </label>
                                </p>

                                <p class="form-row form-row-wide">
                                    <label for="password2">ยืนยันรหัสผ่าน:
                                        <i class="im im-icon-Lock-2"></i>
                                        <input class="input-text" type="password" name="password_confirmation"
                                            id="password2" />
                                            <span class="error-message" id="error-password2"></span>
                                    </label>
                                </p>

                                <input type="submit" class="button border fw margin-top-10" style="width:100%;padding:10px 20px;border-radius:10px;" value="ลงทะเบียน" />

                                 <div class="or-divider">
                                        <span> หรือ </span>
                                    </div>

                                    <div class="social-login-buttons">
                                    <a href="{{ route('login.provider', 'google') }}" class="btn-social google2">
                                        <img src="https://developers.google.com/identity/images/g-logo.png"
                                            alt="Google"
                                            style="width:20px; height:20px; vertical-align:middle; margin-right:8px;">
                                        เข้าระบบด้วย Google
                                    </a>
                                   
                                

                            </form>
                        </div>
                  
               
            </div>
@endsection
@section('scripts')


@endsection