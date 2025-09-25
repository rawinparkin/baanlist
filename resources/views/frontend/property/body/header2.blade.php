
@php
$bcategory = App\Models\BlogCategory::latest()->get();
@endphp
<header id="header-container">

    <!-- Header -->
    <div id="header" class="not-sticky">
        <div class="container">

            <!-- Left Side Content -->
            <div class="left-side">

                <!-- Logo -->
                <div id="logo">
                    <a href="{{ route('home.index') }}"><img src="{{ asset('frontend/images/B-8.png') }}" alt="">
                        <span class="logo-header">baanlist</span></a>
                </div>

                <!-- Mobile Navigation -->
                <div class="mmenu-trigger">
                    <button class="hamburger hamburger--collapse" type="button">
                        <span class="hamburger-box">
                            <span class="hamburger-inner"></span>
                        </span>
                    </button>
                </div>

                <!-- Main Navigation -->
                <nav id="navigation" class="style-1">
                    <ul id="responsive">

                        <li><a
                                href="{{ route('search.property', ['lat' => '13.7563309','lon' => '100.5017651','label' => 'กรุงเทพมหานคร','purpose' => 1,'category' => 1,]) }}">บ้านขาย</a>
                            <ul>
                                <li><a href="{{ route('search.property', ['lat' => '13.7563309','lon' => '100.5017651','label' => 'กรุงเทพมหานคร','purpose' => 1,'category' => 1,]) }}">กรุงเทพ</a></li>
                                <li><a href="{{ route('search.property', ['lat' => '12.9281','lon' => '100.8261','label' => 'พัทยา','purpose' => 1,'category' => 1,]) }}">พัทยา</a></li>
                                <li><a href="{{ route('search.property', ['lat' => '18.7884','lon' => '98.9853','label' => 'เชียงใหม่','purpose' => 1,'category' => 1,]) }}">เชียงใหม่</a></li>
                                <li><a href="{{ route('search.property', ['lat' => '7.8804','lon' => '98.3923','label' => 'ภูเก็ต','purpose' => 1,'category' => 1,]) }}">ภูเก็ต</a></li>

                            </ul>
                        </li>

                        <li><a href="{{ route('search.property', ['lat' => '13.7563309','lon' => '100.5017651','label' => 'กรุงเทพมหานคร','purpose' => 2,'category' => 1,]) }}">บ้านเช่า</a>
                            <ul>
                                <li><a href="{{ route('search.property', ['lat' => '13.7563309','lon' => '100.5017651','label' => 'กรุงเทพมหานคร','purpose' => 2,'category' => 1,]) }}">กรุงเทพ</a></li>
                                <li><a href="{{ route('search.property', ['lat' => '12.9281','lon' => '100.8261','label' => 'พัทยา','purpose' => 2,'category' => 1,]) }}">พัทยา</a></li>
                                <li><a href="{{ route('search.property', ['lat' => '18.7884','lon' => '98.9853','label' => 'เชียงใหม่','purpose' => 2,'category' => 1,]) }}">เชียงใหม่</a></li>
                                <li><a href="{{ route('search.property', ['lat' => '7.8804','lon' => '98.3923','label' => 'ภูเก็ต','purpose' => 2,'category' => 1,]) }}">ภูเก็ต</a></li>
                            </ul>
                        </li>

                        <li><a href="{{ route('search.property', ['lat' => '13.7563309','lon' => '100.5017651','label' => 'กรุงเทพมหานคร','purpose' => 1,'category' => 2,]) }}">คอนโดขาย</a>
                            <ul>
                                <li><a href="{{ route('search.property', ['lat' => '13.7554152','lon' => '100.5731805','label' => 'พระราม 9','purpose' => 1,'category' => 2,]) }}">พระราม 9</a></li>
                                <li><a href="{{ route('search.property', ['lat' => '13.7307942','lon' => '100.5864445','label' => 'วัฒนา','purpose' => 1,'category' => 2,]) }}">วัฒนา</a></li>
                                <li><a href="{{ route('search.property', ['lat' => '13.777196499999999','lon' => '100.57989169999999','label' => 'ห้วยขวาง','purpose' => 1,'category' => 2,]) }}">ห้วยขวาง</a></li>
                                <li><a href="{{ route('search.property', ['lat' => '13.8167455','lon' => '100.5493011','label' => 'จตุจักร','purpose' => 1,'category' => 2,]) }}">จตุจักร</a></li>
                            </ul>
                        </li>

                        <li><a href="{{route('blog.list')}}">บทความ</a>
                            <ul>
                                @foreach($bcategory as $item)
                                <li><a href="{{route('blog.category', $item->id)}}">{{$item->category_name}}</a></li>
                                @endforeach

                            </ul>
                        </li>



                    </ul>

                </nav>
                <div class="clearfix"></div>
                <!-- Main Navigation / End -->

            </div>
            <!-- Left Side Content / End -->


            <!-- Right Side Content / End -->
            <div class="right-side">
                <!-- Header Widget -->
                <div class="header-widget">

                    @auth
                    <!-- User Menu -->
                    <div class="user-menu">
                        <div class="user-name">
                            <span><img
                                    src="{{ (!empty(auth()->user()->photo)) ? url('upload/users/'.auth()->user()->id.'/'.auth()->user()->photo.'') : url('upload/users/boy.png') }}"
                                    alt=""></span>สวัสดี {{ \Illuminate\Support\Str::limit(auth()->user()->name, 10) }}!
                        </div>
                        <ul>
                            @if (Auth::user()->role === 'admin')
                            <li>
                                <a href="{{ route('admin.dashboard') }}">
                                    <i class="sl sl sl-icon-wrench"></i> การจัดการ
                                </a>
                            </li>
                            @endif
                            <li>
                                <a href="{{ route('dashboard') }}">
                                    <i class="sl sl-icon-settings"></i> แผงควบคุม
                                </a>
                            </li>
                             <li>
                                <a href="{{ route('user.profile') }}">
                                    <i class="sl sl-icon-user"></i> ข้อมูลส่วนตัว
                                </a>
                            </li>

                            
                            <li><a href="{{ route('show.profile', ['identifier' => auth()->user()->uuid]) }}"><i
                                        class="fa fa-list-alt"></i>
                                    แสดงประกาศ</a>
                            </li>
                            @php
                            $unreadCount = \App\Models\ChatMessage::where('receiver_id', auth()->id())
                            ->where('is_read', 0)
                            ->count();
                            @endphp
                            <li><a href="{{ route('user.messages') }}"><i class="sl sl-icon-envelope-open"></i>
                                    ข้อความ @if($unreadCount > 0)
                                    <span class="readCount">{{ $unreadCount }}</span>
                                    @endif</a></li>
                            <li><a href=" {{route('user.wishlist')}}"><i class="sl sl-icon-heart"></i>
                                    ประกาศที่ชอบ</a></li>
                            <li><a href="{{ route('user.logout') }}"><i class="sl sl-icon-power"></i> ออกระบบ</a></li>
                        </ul>
                    </div>

                    <a href="{{ route('add.listing') }}" class="button border with-icon">ลงประกาศ <i
                            class="sl sl-icon-plus"></i></a>

                    @else

                    <a href="#sign-in-dialog" class="sign-in popup-with-zoom-anim"><i
                            class="fa fa-sign-in"></i>เข้าระบบ</a>
                    <a href="dashboard-add-listing.html" class="button border with-icon" onclick="alert('กรุณาเข้าสู่ระบบก่อน'); return false;">ลงประกาศ<i
                            class="sl sl-icon-plus"></i></a>

                    @endauth

                </div>
                <!-- Header Widget / End -->
            </div>
            <!-- Right Side Content / End -->

            <!-- Sign In Popup -->
            <div id="sign-in-dialog" class="zoom-anim-dialog mfp-hide">

                <div class="small-dialog-header">
                    <h3>เข้าระบบ</h3>
                </div>

                <!--Tabs -->
                <div class="sign-in-form style-1">

                    <ul class="tabs-nav">
                        <li class=""><a href="#tab1">ล็อคอิน</a></li>
                        <li><a href="#tab2">สมัครสมาชิก</a></li>
                    </ul>

                    <div class="tabs-container alt">

                        <!-- Login -->
                        <div class="tab-content" id="tab1" style="display: none;">

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

                                <input type="submit" class="button border margin-top-20" style="width:100%;padding:15px 20px;border-radius:10px;" value="เข้าระบบ" />
                              
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
                                    {{-- <a href="{{ route('login.provider', 'facebook') }}" class="btn-social facebook2">
                                        <i class="fa fa-facebook"></i>
                                        เข้าระบบด้วย Facebook
                                    </a> --}}
                                </div>

                            </form>
                        </div>

                        <!-- Register -->
                        <div class="tab-content" id="tab2" style="display: none;">

                            <form method="POST" action="{{ route('register') }}" class="register">
                                @csrf

                                <p class="form-row form-row-wide">
                                    <label>ชื่อ:
                                        <i class="im im-icon-Male"></i>
                                        <input type="text" class="input-text" name="name" id="name1"
                                            autocomplete="no" />
                                    </label>
                                    <span class="error-message" id="error-name1"></span>
                                </p>

                                <p class="form-row form-row-wide">
                                    <label for="email2">อีเมล์:
                                        <i class="im im-icon-Mail"></i>
                                        <input type="text" class="input-text" name="email" id="email2"
                                            autocomplete="no" />
                                    </label>
                                    <span class="error-message" id="error-email2"></span>
                                </p>

                                <p class="form-row form-row-wide">
                                    <label for="password1">รหัสผ่าน:
                                        <i class="im im-icon-Lock-2"></i>
                                        <input class="input-text" type="password" name="password" id="password1" />
                                    </label>
                                    <span class="error-message" id="error-password1"></span>
                                </p>

                                <p class="form-row form-row-wide">
                                    <label for="password2">ยืนยันรหัสผ่าน:
                                        <i class="im im-icon-Lock-2"></i>
                                        <input class="input-text" type="password" name="password_confirmation"
                                            id="password2" />
                                    </label>
                                    <span class="error-message" id="error-password2"></span>
                                </p>

                                <input type="submit" class="button border fw margin-top-10" value="ลงทะเบียน" />

                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <!-- Sign In Popup / End -->



        </div>
    </div>
    <!-- Header / End -->



</header>