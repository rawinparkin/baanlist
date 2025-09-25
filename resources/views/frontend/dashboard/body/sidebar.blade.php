@php
$route = Route::current()->getName();
@endphp

<!-- Responsive Navigation Trigger -->
<a href="#" class="dashboard-responsive-nav-trigger"><i class="fa fa-reorder"></i>แผงการจัดการ</a>

<div class="dashboard-nav">
    <div class="dashboard-nav-inner">

        <ul data-submenu-title="หลัก">
            <li class="{{ ($route ==  'dashboard')? 'active':  '' }}"><a href="{{route('dashboard') }}"><i
                        class="sl sl-icon-settings"></i> แผงควบคุม</a>
            </li>

            @php
            $unreadCount = \App\Models\ChatMessage::where('receiver_id', auth()->id())
            ->where('is_read', 0)
            ->count();
            @endphp

            <li class="{{ request()->routeIs('user.messages') ? 'active' : '' }}">
                <a href="{{ route('user.messages') }}">
                    <i class="sl sl-icon-envelope-open"></i>
                    ข้อความ
                    @if($unreadCount > 0)
                    <span class="nav-tag messages">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>
            <li class="{{ ($route ==  'user.package.plan')? 'active':  '' }}"><a
                    href="{{ route('user.package.plan' )}}"><i class="sl sl-icon-basket-loaded"></i> ซื้อแพ็กเกจ</a>
            </li>

            <li class="{{ ($route ==  'user.payment.history')? 'active':  '' }}"><a
                    href="{{route('user.payment.history')}}"><i class="sl sl-icon-wallet"></i> ประวัติจ่ายเงิน</a>
            </li>
        </ul>

        <ul data-submenu-title="ประกาศ">
            <li class="{{ ($route ==  'user.my.listing')? 'active':  '' }}"><a href="{{ route('user.my.listing', ['status' => 'active']) }}"><i class="sl sl-icon-layers"></i>
                    ประกาศของฉัน</a></li>
                {{-- <ul>
                    <li><a href="{{ route('user.my.listing', ['status' => 'active']) }}">แสดง <span
                                class="nav-tag green">6</span></a>
                    </li>
                    <li><a href="{{ route('user.my.listing', ['status' => 'pending']) }}">รออนุมัติ <span
                                class="nav-tag yellow">1</span></a></li>
                    <li><a href="{{ route('user.my.listing', ['status' => 'expired']) }}">หมดอายุ <span
                                class="nav-tag red">2</span></a>
                    </li>
                </ul> --}}
            

            <li class="{{ ($route ==  'user.wishlist')? 'active':  '' }}"><a href=" {{route('user.wishlist')}}"><i
                        class="sl sl-icon-heart"></i> ประกาศที่ชอบ</a></li>
            <li class="{{ ($route ==  'add.listing')? 'active':  '' }}"><a href="{{route('add.listing')}}"><i
                        class="sl sl-icon-plus"></i> ลงประกาศ</a></li>
        </ul>

        <ul data-submenu-title="บัญชี">
            <li class="{{ ($route ==  'user.profile')? 'active':  '' }}"><a href="{{ route('user.profile') }}"><i
                        class="sl sl-icon-user"></i> ข้อมูลส่วนตัว</a></li>
            <li><a href="{{ route('show.profile', ['identifier' => auth()->user()->uuid]) }}"><i
                        class="fa fa-list-alt"></i> แสดงประกาศ</a>
            </li> 
            <li><a href="{{ route('user.logout') }}"><i class="sl sl-icon-power"></i> ออกระบบ</a></li>
        </ul>

    </div>
</div>
<!-- Navigation / End -->