
@extends('frontend.frontend_dashboard')
@section('meta')
<title>เข้าระบบ - baanlist</title>
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
                            
                            <h3 class="text-center margin-bottom-20">เข้าระบบ</h3>


                            
                                <form method="POST" action="{{ route('login') }}" class="login">
                                    @csrf

                                    <p class="form-row form-row-wide">
                                        <label>อีเมล์:
                                            <i class="im im-icon-Male"></i>
                                            <input type="text" class="input-text" name="email" autocomplete="email" />
                                        </label>
                                    </p>

                                    <p class="form-row form-row-wide">
                                        <label for="password">รหัสผ่าน:
                                            <i class="im im-icon-Lock-2"></i>
                                            <input class="input-text" type="password" name="password" id="password" />
                                        </label>
                                        
                                    </p>
                                    
                                
                                    <div class="form-row">
                                       
                                        
                                        <span class="checkboxes margin-top-10">
                                            <input id="remember-me" type="checkbox" name="remember">
                                            <label for="remember-me">จดจำการเข้าสู่ระบบ</label> 
                                        </span>
                                        <span class="lost_password" style="float:right;">
                                            <a href="{{route('password.request')}}">ลืมรหัสผ่าน?</a>
                                        </span>
                                        
                                        
                                    </div>

                                    <input type="submit" class="button border margin-top-20" style="width:100%;padding:10px 20px;border-radius:10px;" value="เข้าระบบ" />
                                
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
                                    {{-- <a href="{{ route('register') }}" class="btn-social google2">
                                        <img src="{{ asset('frontend/images/B-8-removebg.png') }}"
                                            alt="register"
                                            style="width:20px; height:20px; vertical-align:middle; margin-right:8px;">
                                        สมัครสมาชิกด้วยอีเมล
                                    </a> --}}
                                        {{-- <a href="{{ route('login.provider', 'facebook') }}" class="btn-social facebook2">
                                            <i class="fa fa-facebook"></i>
                                            เข้าระบบด้วย Facebook
                                        </a> --}}
                                    </div>

                                </form>
                        </div>
                  
               
            </div>
@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form.login');

    form.addEventListener('submit', function (e) {
        let isValid = true;

        // Remove existing error labels
        form.querySelectorAll('label.error').forEach(label => label.remove());

        const emailInput = form.querySelector('input[name="email"]');
        const passwordInput = form.querySelector('input[name="password"]');

        // Validate email
        if (emailInput.value.trim() === '') {
            addErrorLabel(emailInput, 'กรุณากรอกอีเมล์');
            isValid = false;
        } else if (!validateEmail(emailInput.value)) {
            addErrorLabel(emailInput, 'รูปแบบอีเมล์ไม่ถูกต้อง');
            isValid = false;
        }

        // Validate password
        if (passwordInput.value.trim() === '') {
            addErrorLabel(passwordInput, 'กรุณากรอกรหัสผ่าน');
            isValid = false;
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    function addErrorLabel(inputElement, message) {
        const errorLabel = document.createElement('label');
        errorLabel.classList.add('error');
        errorLabel.style.color = 'red';
        errorLabel.style.fontSize = '14px';
        errorLabel.textContent = message;

        // Insert error label after input
        const parent = inputElement.parentNode;
        parent.appendChild(errorLabel);
    }

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
});
</script>
@endsection


