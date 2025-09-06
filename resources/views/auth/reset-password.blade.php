
@extends('frontend.frontend_dashboard')
@section('meta')
<title>เปลี่ยนรหัสผ่าน - baanlist</title>
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
                            <h3 class="text-center margin-bottom-20">เปลี่ยนรหัสผ่าน</h3>

                                @if (session('status'))
                                    <div class="notification success closeable">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="notification error closeable">
                                        {{ $errors->first() }}
                                    </div>
                                @endif
                               <form method="POST" action="{{ route('password.store') }}" class="login">
                                    @csrf

                                    {{-- Hidden Token --}}
                                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                    <p class="form-row form-row-wide">
                                        <label>อีเมล์:
                                            <i class="im im-icon-Male"></i>
                                            <input type="email" class="input-text" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" />
                                        </label>
                                    </p>

                                    <p class="form-row form-row-wide">
                                        <label for="password1">รหัสผ่าน:
                                            <i class="im im-icon-Lock-2"></i>
                                            <input class="input-text" type="password" name="password" id="password1" required autocomplete="new-password" />
                                            <span class="error-message" id="error-password1"></span>
                                        </label>
                                    </p>

                                    <p class="form-row form-row-wide">
                                        <label for="password2">ยืนยันรหัสผ่าน:
                                            <i class="im im-icon-Lock-2"></i>
                                            <input class="input-text" type="password" name="password_confirmation" id="password2" required autocomplete="new-password" />
                                            <span class="error-message" id="error-password2"></span>
                                        </label>
                                    </p>

                                    <input type="submit" class="button border margin-top-20" style="width:100%;padding:10px 20px;border-radius:10px;" value="รีเซ็ตรหัสผ่าน" />
                                </form>

                        </div>
                  
               
            </div>
@endsection




