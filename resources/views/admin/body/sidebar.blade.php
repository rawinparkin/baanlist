@php
$route = Route::current()->getName();
@endphp

<!-- Responsive Navigation Trigger -->
<a href="#" class="dashboard-responsive-nav-trigger"><i class="fa fa-reorder"></i> Dashboard Navigation</a>

<div class="dashboard-nav admin-nav">
    <div class="dashboard-nav-inner">

        <ul data-submenu-title="หลัก">
            <li class="{{ ($route ==  'admin.dashboard')? 'active':  '' }}"><a href="{{route('admin.dashboard') }}"><i
                        class="sl sl sl-icon-wrench"></i> การจัดการ</a>
            </li>
            <li class="{{ ($route ==  'admin.all.package')? 'active':  '' }}"><a href="{{route('admin.all.package') }}"><i
                        class="im im-icon-Dollar-Sign"></i> แพ็กเกจ</a>
            </li>
            <li class="{{ ($route ==  'admin.all.income')? 'active':  '' }}"><a href="{{route('admin.all.income') }}"><i
                        class="im im-icon-Credit-Card2"></i> รายได้</a>
            </li>
            @php
            $unreadCount = \App\Models\ContactBox::count();
            @endphp
            <li class="{{ ($route ==  'admin.all.message')? 'active':  '' }}"><a
                    href="{{route('admin.all.message') }}"><i class="sl sl-icon-envelope-open"></i>
                    ข้อความ@if($unreadCount > 0)
                    <span class="nav-tag messages">{{ $unreadCount }}</span>
                    @endif</a>
            </li>

           

        </ul>

        <ul data-submenu-title="ประกาศ">
            <li class="{{ ($route ==  'admin.property.all.type')? 'active':  '' }}"><a
                    href="{{route('admin.all.type') }}"><i class="sl sl-icon-list"></i> ประเภท</a>
            </li>

            <li class="{{ ($route ==  'admin.all.amenity')? 'active':  '' }}"><a
                    href="{{route('admin.all.amenity') }}"><i class="sl sl-icon-game-controller"></i>
                    เครื่องอำนวยความสะดวก</a>
            </li>

            <li class="{{ ($route ==  'admin.all.property')? 'active':  '' }}"><a
                    href="{{route('admin.all.property') }}"><i class="sl sl-icon-home"></i> ประกาศทั้งหมด</a>
            </li>
        </ul>

        <ul data-submenu-title="บทความ">
            <li class="{{ ($route ==  'add.blog.post')? 'active':  '' }}"><a href="{{route('add.blog.post') }}"><i
                        class="sl sl-icon-plus"></i> เพิ่ม</a>
            </li>
            <li class="{{ ($route ==  'all.blog.category')? 'active':  '' }}"><a
                    href="{{route('all.blog.category') }}"><i class="sl sl-icon-list"></i> ประเภท</a>
            </li>
            <li class="{{ ($route ==  'all.blog.post')? 'active':  '' }}"><a href="{{route('all.blog.post') }}"><i
                        class="sl sl-icon-book-open"></i> บทความทั้งหมด</a>
            </li>
        </ul>

        <ul data-submenu-title="ตั้งค่าเว็บ">
            <li class="{{ ($route ==  'admin.all.user')? 'active':  '' }}"><a href="{{route('admin.all.user') }}"><i
                        class="sl sl-icon-user"></i> สมาชิก</a>
            </li>
            <li class="{{ ($route ==  'smtp.setting')? 'active':  '' }}"><a href="{{route('smtp.setting') }}"><i
                        class="sl sl-icon-envelope-open"></i> STMP setting</a>
            </li>
            <li class="{{ ($route ==  'site.setting')? 'active':  '' }}"><a href="{{route('site.setting') }}"><i
                        class="sl sl-icon-note"></i> Site setting</a>
            </li>
            <li class="{{ ($route ==  'seo.setting')? 'active':  '' }}"><a href="{{route('seo.setting') }}"><i
                        class="fa fa-bar-chart"></i> SEO & Adsense</a>
            </li>

             <li class="{{ ($route ==  'admin.all.province')? 'active':  '' }}"><a href="{{route('admin.all.province') }}"><i
                        class="fa fa-map-pin"></i> จังหวัด</a>
            </li>



        </ul>
        </li>
        </ul>

    </div>
</div>
<!-- Navigation / End -->