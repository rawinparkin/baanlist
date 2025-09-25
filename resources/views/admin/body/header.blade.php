@php
$bcategory = App\Models\BlogCategory::latest()->get();
@endphp

<header id="header-container" class="fixed fullwidth dashboard">

    <!-- Header -->
    <div id="header" class="not-sticky">
        <div class="container">

            <!-- Left Side Content -->
            <div class="left-side">

                <!-- Logo -->
                <div id="logo" style="background-color:#333;">
                    <a href="{{ route('home.index') }}"><img src="{{ asset('frontend/images/B-8-icon.png') }}"
                            alt=""></a>
                    <a href="{{ route('home.index') }}" class="dashboard-logo"><img
                            src="{{ asset('frontend/images/B-8-removebg.png') }}" alt=""><span
                            class="logo-header2">baanlist</span></a>


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
                    <a href="dashboard-add-listing.html" class="button border with-icon">ลงประกาศ<i
                            class="sl sl-icon-plus"></i></a>

                    @endauth

                </div>
                <!-- Header Widget / End -->
            </div>
            <!-- Right Side Content / End -->






        </div>
    </div>
    <!-- Header / End -->



</header>