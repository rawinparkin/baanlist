@php
$setting = App\Models\SiteSetting::first();
@endphp

<div id="footer" class="sticky-footer">
    <!-- Main -->
    <div class="container">
        <div class="row">
            <div class="col-md-5 col-sm-6">
                <img class="footer-logo" src="{{ asset('frontend/images/B-8.png') }}" alt="">
                <br><br>
                <p>{{$setting->about_footer}}</p>
            </div>

            <div class="col-md-4 col-sm-6 ">
                <h4>ลิงค์ที่เป็นประโยชน์</h4>
                <ul class="footer-links">
                    
                    <li><a href="#sign-in-dialog">เข้าสู่ระบบ</a></li>
                    <li><a href="#sign-in-dialog">สมัครสมาชิก</a></li>
                    @auth
                    <li><a href="{{route('user.package.plan')}}">แพ็กเกจ</a></li>
                    <li><a href="{{route('add.listing')}}">เพิ่มประกาศ</a></li>
                    @else
                    <li><a href="#sign-in-dialog">เพิ่มประกาศ</a></li>
                    <li><a href="{{route('show.package')}}">แพ็กเกจ</a></li>

                    @endauth
                    
                </ul>

                <ul class="footer-links">
                    {{-- <li><a href="#">คำถามที่พบบ่อย</a></li> --}}
                    <li><a href="{{route('blog.list')}}">บล็อก</a></li>
                    <li><a href="{{route('show.contact')}}">ติดต่อ</a></li>
                    <li><a href="#terms-modal" class="open-modal">เงื่อนไขการใช้งาน</a></li>
                    <li><a href="#privacy-modal" class="open-modal">นโยบายความเป็นส่วนตัว</a></li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="col-md-3  col-sm-12">
                <h4>ติดต่อเรา</h4>
                <div class="text-widget">
                    <span>{{$setting->company_address}}</span> <br>
                    <span>{{$setting->company_address2}}, {{$setting->company_address3}}</span> <br>
                    <!-- โทร: <span>{{$setting->support_phone}} </span><br> -->
                    อีเมล์:<span> <a href="#">{{$setting->email}}</a> </span><br>
                </div>
                <div class="text-widget margin-top-10">
                    <ul class="social-icons2">
                        <li><a href="{{$setting->facebook}}"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="{{$setting->instagram}}"><i class="fa fa-instagram"></i></a></li>
                        <li><a href="{{$setting->pinterest}}"><i class="fa fa-pinterest"></i></a></li>
                    </ul>
                </div>

             

            </div>

        </div>

        <!-- Copyright -->
        <div class="row">
            <div class="col-md-12">
                <div class="copyrights">{{$setting->copyright}}</div>
            </div>
        </div>


            <!-- Modal: เงื่อนไขการใช้งาน -->
        <div id="terms-modal" class="zoom-anim-dialog mfp-hide">
            <div class="small-dialog-header">
                <h3>เงื่อนไขการใช้งาน</h3>
            </div>
            <div class="modal-content">
                <p>
                    {!! $setting->terms_of_service !!}
                </p>
                <!-- เพิ่มเนื้อหาเงื่อนไขเต็มได้ที่นี่ -->
            </div>
        </div>


        <!-- Modal: นโยบายความเป็นส่วนตัว -->
        <div id="privacy-modal" class="zoom-anim-dialog mfp-hide">
            <div class="small-dialog-header">
                <h3>นโยบายความเป็นส่วนตัว</h3>
            </div>
            <div class="modal-content">
                <p>
                    {!! $setting->policy !!}
                </p>
                <!-- เพิ่มเนื้อหานโยบายเต็มได้ที่นี่ -->
            </div>
        </div>

    </div>

</div>


