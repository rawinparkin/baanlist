@extends('frontend.frontend_dashboard')


@section('meta')
<title>แก้ไขสมาชิก - baanlist</title>

@endsection
@section('main')

<!-- Header Container
================================================== -->
@include('admin.body.header')
<!-- Header Container / End -->

<!-- Dashboard -->
<div id="dashboard">

    <!-- Navigation
================================================== -->

    @include('admin.body.sidebar')

    <!-- Content
	================================================== -->
    <div class="dashboard-content">

        <!-- Titlebar -->
        <div id="titlebar">
            <div class="row">
                <div class="col-md-12">
                    <h2>แก้ไขสมาชิก</h2>
                    <!-- Breadcrumbs -->
                </div>
            </div>
        </div>

        <!-- Notice -->
        @if(session('notification'))
        <div class="row">
            <div class="col-md-12">
                <div class="notification {{ session('notification_class', 'success') }} closeable margin-bottom-30">
                    <p>{{ session('notification') }}</p>
                    <a class="close" href="#"></a>
                </div>
            </div>
        </div>
        @endif

        <div class="row">

            <!-- Profile -->
            <div class="col-lg-6 col-md-12">
                <div class="dashboard-list-box margin-top-0">
                    <h4 class="gray">บัญชีของคุณ</h4>
                    <div class="dashboard-list-box-static">

                        <form action="{{ route('admin.update.user') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <input type="hidden" name="id" value="{{ $userData->id }}">
                            <input type="hidden" name="old_img" value="{{ $userData->photo }}">

                            <!-- Avatar -->
                            <div class="edit-profile-photo">
                                <img id="showImage"
                                    src="{{ (!empty($userData->photo)) ? url('upload/users/'.$userData->id.'/'.$userData->photo.'') : url('upload/users/boy.png') }}"
                                    alt="{{ $userData->name }}">
                                <div class="change-photo-btn">
                                    <div class="photoUpload">
                                        <span><i class="fa fa-upload"></i> เปลี่ยนรูป</span>
                                        <input type="file" class="upload" name="photo" id="image" />
                                    </div>
                                </div>
                            </div>

                            <!-- Details -->
                            <div class="my-profile">

                                <label>ชื่อ</label>
                                <input value="{{ $userData->name }}" type="text" name="name" required>
                                


                                <label>เบอร์โทร</label>
                                <input value="{{ format_thai_phone($userData->phone) }}" type="text" name="phone"
                                    id="phone" oninput="this.value = this.value.replace(/[^0-9\-]/g, '')"
                                    maxlength="12">

                                <label>ไลน์ไอดี</label>
                                <input value="{{ $userData->line }}" type="text" name="line">

                                <label>Credit</label>
                                <input value="{{ $userData->credit }}" type="text" name="credit" oninput="this.value = this.value.replace(/[^0-9\-]/g, '')">

                                <label>อีเมล์</label>
                                <input value="{{ $userData->email }}" type="text" readonly
                                    style="background-color:#c4c4c4;" name="email">



                                <label>เกี่ยวกับคุณ</label>
                                <textarea name="about" id="notes" cols="30" class="font-light"
                                    rows="10">{{ old('about', $userData->about) }}</textarea>


                            </div>

                            <button class="button margin-top-15" type="submit">บันทึก</button>

                        </form>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.update.user.password') }}" method="POST" class="default-form" id="myFrom"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="user_id" value="{{ $userData->id }}">
                <!-- Change Password -->
                <div class="col-lg-6 col-md-12">
                    <div class="dashboard-list-box margin-top-0">
                        <h4 class="gray">เปลี่ยนรหัสผ่าน</h4>
                        <div class="dashboard-list-box-static">

                            <!-- Change Password -->
                            <div class="my-profile">
                                @if (is_null(auth()->user()->google_provider_id))
                                <label class="margin-top-0">รหัสผ่านเดิม</label>
                                @error('old_password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <input type="password" name="old_password">
                                @endif
                                

                                <label>รหัสผ่านใหม่</label>
                                @error('new_password')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                                <input type="password" name="new_password">

                                <label>รหัสผ่านใหม่ อีกครั้ง</label>
                                <input type="password" name="new_password_confirmation">

                                <button type="submit" class="button margin-top-15">เปลี่ยนรหัสผ่าน</button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>

          

        </div>



    </div>
    <!-- Content / End -->



</div>
<!-- Dashboard / End -->



@endsection
@section('scripts')
<script type="text/javascript">
    $('#image').change(function(e) {
        const file = e.target.files[0];
        const fileType = file.type;

        if (fileType === 'image/heic' || file.name.endsWith('.heic')) {
            $('#showImage').attr('src', '/images/heic-placeholder.png'); // Custom icon or message
            alert('ไม่สามารถแสดงตัวอย่างไฟล์ HEIC ในเบราว์เซอร์ได้ ไฟล์จะถูกแปลงหลังจากอัปโหลดแล้ว');
        } else {
            const reader = new FileReader();
            reader.onload = function(event) {
                $('#showImage').attr('src', event.target.result);
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection